<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Register as BaseRegister;

use App\Models\Paquete;
use App\Models\Suscripcion;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Illuminate\Http\Request;



class Register extends BaseRegister
{
    public ?int $paquete_id = null;

    public function mount(): void
    {
        parent::mount();

        // Obtenemos el request a través del contenedor de servicios
        $request = app(Request::class);
        $this->paquete_id = $request->query('paquete');

        if ($this->paquete_id && Paquete::find($this->paquete_id)) {
            $this->form->fill([
                'paquete_id' => $this->paquete_id
            ]);
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
            ->hidden(fn() => !is_null($this->paquete_id));
    }

    protected function getLastNameFormComponent(): Component
    {
        return TextInput::make('lastname')
            ->label(__('Apellido'))
            ->required()
            ->maxLength(255)
            ->autofocus();
    }

    protected function getPhoneNumberFormComponent(): Component
    {
        return TextInput::make('phone')
            ->label(__('Celular'))
            ->tel()
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


        $user = User::create([
            'name' => $data['name'],
            'lastname' => $data['lastname'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => $data['password'],
        ]);


        $paquete = Paquete::findOrFail($data['paquete_id']);
        $meses = $paquete->meses_suscripcion ?? 1;


        Suscripcion::create([
            'user_id' => $user->id,
            'paquete_id' => $data['paquete_id'],
            'fecha_inicio' => now(),
            'fecha_fin' => now()->addMonths($meses),
            'meses_suscripcion' => $meses
        ]);
        return $user;
    }
}
