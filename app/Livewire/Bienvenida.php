<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Categoria;
use App\Models\ImagenProducto;
use App\Models\Producto;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Support\Str;
use Filament\Forms\Components\Wizard\Step;
use Illuminate\Support\HtmlString;

class Bienvenida extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    // Paso 1: Categoría
                    Wizard\Step::make('Categoría')
                        ->description('Crea tu primera categoría')
                        ->schema([
                            TextInput::make('categoria_nombre')
                                ->label('Nombre de la Categoría')
                                ->required()
                                ->live(onBlur: true),
                            TextInput::make('categoria_descripcion')
                                ->label('Descripcion'),

                        ]),

                    // Paso 2: Producto
                    Wizard\Step::make('Producto')
                        ->description('Agrega tu primer producto')
                        ->schema([
                            TextInput::make('producto_nombre')
                                ->label('Nombre del Producto')
                                ->required(),
                            Textarea::make('producto_descripcion')
                                ->label('Descripción'),
                            TextInput::make('precio')
                                ->numeric()
                                ->prefix('$')
                                ->required(),
                        ]),

                    Wizard\Step::make('imagenProducto')
                        ->label('Imagen del producto')
                        ->description('Agrega tu primera imagen')
                        ->schema([
                            FileUpload::make('url')
                                ->label('Imagen')
                                ->image()
                                ->directory(fn($record) => 'imagenes-productos/' . Str::slug(auth()->user()->name . '-' . auth()->user()->lastname))
                                ->required()
                                ->maxSize(5120)
                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                ->helperText('Formatos permitidos: JPG, PNG, WEBP. Máximo 5MB. Proporción recomendada: 1:1 (cuadrada)')
                                ->rules([
                                    'dimensions:min_width=400,min_height=400,max_width=2000,max_height=2000,ratio=1/1',
                                ])
                                ->validationMessages([
                                    'dimensions' => 'La imagen debe ser cuadrada y tener entre 400x400 y 2000x2000 píxeles.',
                                ])
                                ->imageEditor()
                                ->imageResizeTargetWidth(800)
                                ->imageResizeTargetHeight(800)
                                ->imageResizeMode('cover'),

                        ]),
                ])
                    ->submitAction(new HtmlString('<button type="submit">Guardar</button>')),
            ])
            ->statePath('data');
    }

    public function create(): void
    {
        $formData = $this->form->getState();

        // 1. Crear Categoría (Usando user_id como indica tu esquema)
        $categoria = Categoria::create([
            'nombre' => $formData['categoria_nombre'],
            'descripcion' => $formData['categoria_descripcion'],
        ]);

        // 2. Crear Producto
        $producto = Producto::create([
            'nombre' => $formData['producto_nombre'],
            'descripcion' => $formData['producto_descripcion'],
            'precio' => $formData['precio'],
            'categoria_id' => $categoria->id,
        ]);

        ImagenProducto::create([
            'nombre' => $formData['producto_nombre'],
            'url' => $formData['url'],
            'orden' => 0,
            'producto_id' => $producto->id,
        ]);

        redirect()->to('/catalogo/spots');
    }

    public function render()
    {
        return view('livewire.bienvenida');
    }
}
