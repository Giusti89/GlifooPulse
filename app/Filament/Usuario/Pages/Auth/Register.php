<?php

namespace App\Filament\Pages\Auth;

use App\Mail\Pedidos;
use App\Models\Contenido;
use App\Models\Landing;
use Filament\Pages\Auth\Register as BaseRegister;

use App\Models\Paquete;
use App\Models\Spot;
use App\Models\Suscripcion;
use App\Models\User;
use App\Models\Sell;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;



class Register extends BaseRegister
{
    public ?int $paquete_id = null;

    public function mount(): void
    {
        parent::mount();
        $request = app(Request::class);

        $paqueteEncriptado = $request->query('paquete');

        if ($paqueteEncriptado) {
            try {
                $paqueteId = Crypt::decrypt($paqueteEncriptado);
                $this->paquete_id = $paqueteId;

                if (Paquete::find($paqueteId)) {
                    $this->form->fill([
                        'paquete_id' => $paqueteId
                    ]);
                }
            } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {

                abort(403, 'El paquete proporcionado no es válido.');
            }
        }
    }

    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getNameFormComponent(),
                        $this->getLastNameFormComponent(),
                        $this->getEmailFormComponent(),
                        $this->getPhoneNumberFormComponent(),
                        $this->getPaqueteFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                        $this->getLastMesesSuscripcion(),
                    ])

                    ->statePath('data'),
            ),
        ];
    }

    protected function getPaqueteFormComponent(): Component
    {
        return Select::make('paquete_id')
            ->label('Selecciona tu paquete')
            ->options(Paquete::pluck('nombre', 'id'))
            ->required()
            ->default($this->paquete_id)
            ->disabled(fn() => !is_null($this->paquete_id))
            ->hidden(fn() => !is_null($this->paquete_id))
            ->live();
    }

    protected function getLastNameFormComponent(): Component
    {
        return TextInput::make('lastname')
            ->label(__('Apellido'))
            ->required()
            ->maxLength(255)
            ->autofocus();
    }

    protected function getLastMesesSuscripcion(): Component
    {
        return TextInput::make('meses')
            ->label(__('Meses de suscripción'))
            ->required()
            ->numeric()
            ->minValue(1)
            ->default(function () {
                // Caso 1: Paquete viene desde botón
                if ($this->paquete_id) {
                    $paquete = Paquete::find($this->paquete_id);
                    return $paquete->precio == 0 ? 12 : null;
                }

                return null;
            })
            ->hidden(function (callable $get) {
                // Caso 1: Paquete viene desde botón
                if ($this->paquete_id) {
                    $paquete = Paquete::find($this->paquete_id);
                    return $paquete->precio == 0;
                }

                // Caso 2: Paquete seleccionado desde formulario
                $paqueteId = $get('paquete_id');
                if ($paqueteId) {
                    $paquete = Paquete::find($paqueteId);
                    return $paquete->precio == 0;
                }

                return false;
            })
            ->reactive();
    }


    protected function getPhoneNumberFormComponent(): Component
    {
        return TextInput::make('phone')
            ->label(__('Celular'))
            ->tel()
            ->minLength(8) 
            ->maxLength(15)
            ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/')
            ->required()
            ->autofocus();
    }

    protected function handleRegistration(array $data): User
    {
        if (!isset($data['paquete_id'])) {
            $data['paquete_id'] = $this->paquete_id;

            if (is_null($data['paquete_id'])) {
                throw new \RuntimeException('Se debe seleccionar un paquete para el registro');
            }
        }


        // 1. Crear el usuario
        $user = User::create([
            'name' => $data['name'],
            'lastname' => $data['lastname'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => $data['password'],
        ]);


        // 2. Crear la suscripción
        $paquete = Paquete::findOrFail($data['paquete_id']);
        $meses = $data['meses'] ?? 1;

        $suscripcion = Suscripcion::create([
            'user_id' => $user->id,
            'paquete_id' => $data['paquete_id'],
            'fecha_inicio' => now(),
            'fecha_fin' => now()->addMonths($meses),
            'estado' => $paquete->precio == 0,
        ]);


        // 3 Crear la venta 
        $cuenta = Sell::create([
            'suscripcion_id' => $suscripcion->id,
            'total' => number_format($paquete->precio * $meses, 2),
            'fecha' => now(),
            'concepto' => "suscripcion",
        ]);


        // 4. Crear el spot asociado
        $tipoLanding = Landing::where('paquete_id', $data['paquete_id'])->firstOrFail();

        $spot = Spot::create([
            'suscripcion_id' => $suscripcion->id,
            'tipolanding' => $tipoLanding->id,
            'estado' => 0,
        ]);
        // 5. Crear contenido por defecto si es un paquete tipo landing page
        if ($paquete->tipoproducto->nombre === "Landing page") {
            Contenido::create([
                'spot_id' => $spot->id,
            ]);
        }

        // 6. Envio email
        $adminEmails = User::where('id', 1)->pluck('email')->toArray();
        if (!empty($adminEmails)) {
            Mail::to($adminEmails)->send(new Pedidos($user, $paquete, $meses));
        }

        if ($paquete->precio == 0) {
            Filament::auth()->login($user);
            session()->regenerate();
            session()->regenerate();

            Notification::make()
                ->title('¡Bienvenido a tu suscripción gratuita!')
                ->success()
                ->send();
        }

        Notification::make()
            ->title('¡Se completo la suscripcion recibira un mensaje en su correo!')
            ->success()
            ->send();

        return $user;
    }
    protected function getRedirectUrl()
    {
        return Redirect::route('inicio')->with('msj', 'suscripcion');
    }
}
