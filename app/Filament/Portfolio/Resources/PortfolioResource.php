<?php

namespace App\Filament\Portfolio\Resources;

use App\Filament\Portfolio\Resources\PortfolioResource\Pages;
use App\Filament\Portfolio\Resources\PortfolioResource\RelationManagers;
use App\Models\Portfolio;
use App\Models\Portfolioitem;
use Illuminate\Support\Str;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Support\Facades\Crypt;

class PortfolioResource extends Resource
{
    protected static ?string $model = Portfolio::class;

    protected static ?string $navigationIcon = 'heroicon-c-tv';
    protected static ?string $navigationLabel = 'Portfolio';
    protected static ?string $pluralModelLabel = 'portfolio';
    protected static ?string $navigationGroup = 'Configuracion Portfolio';

    protected static ?int $navigationSort = 2;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('spot.suscripcion', function ($query) {
                $query->where('user_id', auth()->id());
            });
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // === CAMPOS PRINCIPALES (Estructura general de 2 columnas) ===
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('titulo')
                            ->label('Nombre del proyecto')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('estado')
                            ->label('Estado')
                            ->options([
                                'activo' => 'Activo',
                                'inactivo' => 'Inactivo',
                            ])
                            ->default('activo')
                            ->hiddenOn(['create']),

                        Forms\Components\Textarea::make('descripcion')
                            ->label('Descripción')
                            ->required()
                            ->maxLength(500)
                            ->rows(3), // Ajusta la altura para que no deforme la fila

                        Forms\Components\TextInput::make('video_url')
                            ->label('URL del video')
                            ->helperText('YouTube, Vimeo, etc.')
                            ->maxLength(255)
                            ->url(),

                        Forms\Components\FileUpload::make('portada')
                            ->label('Carátula')
                            ->imageEditor()
                            ->directory(fn() => 'portfolio/' . Str::slug(auth()->user()->name))
                            ->image()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->maxSize(5120),

                        Forms\Components\TextInput::make('orden')
                            ->label('Orden de visualización')
                            ->numeric()
                            ->default(0),
                    ]),

                // === GALERÍA (Efecto Grid/Cuadrícula para los elementos) ===
                Forms\Components\Repeater::make('galeria')
                    ->label('Galería de imágenes')
                    ->relationship('galeria')
                    ->schema([
                        // Campos internos del elemento de la galería
                        Forms\Components\TextInput::make('titulo')
                            ->label('Título')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('orden')
                            ->label('Orden')
                            ->numeric()
                            ->default(0),

                        Forms\Components\TextInput::make('descripcion')
                            ->label('Descripción')
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('imagen')
                            ->label('Imagen')
                            ->image()
                            ->imageEditor()
                            ->directory(fn() => 'portfolio/' . Str::slug(auth()->user()->name))
                            ->maxSize(5120)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->helperText('Formatos: JPG, PNG, WEBP (máx 5MB)')
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->grid(2) // <- ESTO hace que los bloques se pongan uno al lado del otro
                    ->columns(1) // <- Define que los componentes INTERNOS ocupen todo el ancho de su bloque
                    ->defaultItems(1)
                    ->collapsible()
                    ->itemLabel(fn(array $state): ?string => $state['titulo'] ?? 'Nueva imagen')
                    ->createItemButtonLabel('Añadir imagen')
                    ->columnSpanFull(), // Ocupa todo el ancho del formulario principal
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                tables\Columns\TextColumn::make('orden')
                    ->sortable()
                    ->label('N°'),

                tables\Columns\TextColumn::make('titulo')
                    ->searchable()
                    ->label('Titulo del proyecto'),

                Tables\Columns\ImageColumn::make('portada')
                    ->label('Imagen')
                    ->size(50)
                    ->circular(),

                tables\Columns\IconColumn::make('estado')
                    ->boolean(),

            ])
            ->filters([
                //
            ])
            ->actions([ActionGroup::make([
                Tables\Actions\EditAction::make()
                    ->color('primary'),

                Tables\Actions\Action::make('crearimagen')
                    ->label('Agregar Imagen')->icon('heroicon-m-plus-circle')
                    ->color('info')
                    ->url(
                        fn(Portfolio $record): string => route('filament.portfolio.resources.portfolioitems.create', ['portfolio' => Crypt::encrypt($record->id)])
                    ),

                Tables\Actions\DeleteAction::make(),

            ])])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPortfolios::route('/'),
            'create' => Pages\CreatePortfolio::route('/create'),
            'edit' => Pages\EditPortfolio::route('/{record}/edit'),
        ];
    }
}
