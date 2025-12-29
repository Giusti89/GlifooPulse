<?php

namespace App\Filament\Portfolio\Resources;

use App\Filament\Portfolio\Resources\SocialsResource\Pages;
use App\Filament\Portfolio\Resources\SocialsResource\RelationManagers;
use App\Models\Social;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Components\Section;

class SocialsResource extends Resource
{
    protected static ?string $model = Social::class;

    protected static ?string $navigationIcon = 'heroicon-m-chat-bubble-oval-left-ellipsis';
    protected static ?string $navigationLabel = 'Configuracion Enlaces Sociales';
    protected static ?string $navigationGroup = 'Redes Sociales';


    protected static ?string $pluralModelLabel = 'Configuracion Enlaces';


    protected static ?int $navigationSort = 5;

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
                Section::make('Social')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('url')
                            ->label(' Enlace red social')
                            ->required()
                            ->helperText('Ingrese la url de su enlace')
                            ->maxLength(255),

                    ]),

                Section::make('Recursos')
                    ->columns()
                    ->schema([
                        Forms\Components\Select::make('enlace_id')
                            ->label('Seleccione su red social')
                            ->options(function () {
                                $userId = auth()->id();
                                return \App\Models\Social::getBotonesDisponiblesPorUsuario($userId)
                                    ->pluck('nombre', 'id')
                                    ->toArray();
                            })
                            ->searchable()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $enlace = \App\Models\Enlace::find($state);

                                if ($enlace) {
                                    $set('nombre', $enlace->nombre);
                                    $set('tipored_id', $enlace->tipored_id);

                                    // Copiar imagen al storage del usuario
                                    $userDirectory = 'paquetes/' . Str::slug(auth()->user()->name);
                                    $newPath = "{$userDirectory}/" . Str::uuid() . '.' . pathinfo($enlace->logo_path, PATHINFO_EXTENSION);

                                    Storage::disk('public')->makeDirectory($userDirectory);
                                    Storage::disk('public')->copy($enlace->logo_path, $newPath);

                                    $set('image_url', $newPath); // Guarda la nueva ruta
                                }
                            }),

                        Forms\Components\TextInput::make('nombre')
                            ->label(' Nombre red social')
                            ->helperText('Llenar en caso de ser otro enlace')
                            ->maxLength(255),

                        Forms\Components\Select::make('tipored_id')
                            ->label('Tipo red')
                            ->helperText('Seleecionar si es una red social u otra red')
                            ->relationship('tipored', 'nombre')
                            ->required()
                            ->default(fn() => request()->query('tipored_id'))
                            ->live(),

                        Forms\Components\Hidden::make('image_url')
                            ->dehydrated(),

                        ViewField::make('vista')
                            ->label('Vista previa de plantilla')
                            ->view('filament.forms.components.vistaboton')
                            ->columnSpan('full'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                tables\Columns\TextColumn::make('spot.titulo')
                    ->label('Titulo')
                    ->searchable(),

                tables\Columns\TextColumn::make('nombre')
                    ->label('Nombre'),

                tables\Columns\TextColumn::make('clicks')
                    ->label('Interacciones'),

                tables\Columns\TextColumn::make('url')
                    ->limit(20)
                    ->label('Enlace'),

                tables\Columns\TextColumn::make('clicks')
                    ->label('Interacciones'),

                Tables\Columns\ImageColumn::make('image_url')
                    ->disk('public')
                    ->label('Logo'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
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
            'index' => Pages\ListSocials::route('/'),
            'create' => Pages\CreateSocials::route('/create'),
            'edit' => Pages\EditSocials::route('/{record}/edit'),
        ];
    }
}
