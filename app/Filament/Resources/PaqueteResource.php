<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaqueteResource\Pages;
use App\Filament\Resources\PaqueteResource\RelationManagers;
use App\Models\Paquete;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\ColorPicker;

class PaqueteResource extends Resource
{
    protected static ?string $model = Paquete::class;

    protected static ?string $navigationIcon = 'heroicon-s-briefcase';
    protected static ?string $navigationGroup = 'Productos Glifoo';
    protected static ?int $navigationSort = 5;

    protected static function getLandingFromLocalId($landing_id)
    {
        if (!$landing_id) return null;

        $evento = Paquete::where('id', $landing_id)->first();
        return $evento ? $evento->id : null;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Publicidad')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('nombre')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('tipoproducto_id')
                            ->label('Paquete')
                            ->relationship('tipoproducto', 'nombre')
                            ->required()
                            ->reactive(),

                        Forms\Components\TextInput::make('precio')
                            ->required()
                            ->numeric()
                            ->default(0.00),

                        Forms\Components\TextInput::make('enlace')
                            ->prefix('https://wa.me/')
                            ->maxLength(255),
                            
                        ColorPicker::make('marco')
                            ->label('Color del marco')
                            ->default('#ffffff')

                            ->rgb(),

                        Forms\Components\Select::make('tipo_estadisticas')
                            ->label('Tipo de estadísticas')
                            ->options([
                                'ninguna' => 'Ninguna',
                                'basica' => 'Básica',
                                'avanzada' => 'Avanzada',
                            ])
                            ->default('ninguna')
                            ->visible(fn($get) => $get('tipoproducto_id') == 2),


                        Forms\Components\TextInput::make('max_redes_sociales')
                            ->label('Cantidad maxima de redes sociales'),

                        Forms\Components\TextInput::make('max_productos')
                            ->label('Cantidad maxima de productos para el store')
                            ->visible(fn($get) => $get('tipoproducto_id') == 2),

                        Forms\Components\TextInput::make('max_imagenes_producto')
                            ->label('Cantidad maxima de imagenes por producto')
                            ->visible(fn($get) => $get('tipoproducto_id') == 2),

                        Forms\Components\TextInput::make('max_categorias')
                            ->label('Cantidad maxima de categorias')
                            ->visible(fn($get) => $get('tipoproducto_id') == 2),

                        Forms\Components\TextInput::make('max_videos')
                            ->label('Cantidad maxima de videos')
                            ->visible(fn($get) => $get('tipoproducto_id') == 2),

                        Forms\Components\Select::make('seo_level')
                            ->label('Control de Seo')
                            ->options([
                                'basico' => 'Basico',
                                'medio' => 'Medio',
                                'completo' => 'Completo',
                            ])
                            ->default('ninguna'),


                        Forms\Components\FileUpload::make('image_url')
                            ->image()
                            ->imageEditor()
                            ->directory('paquetes')
                            ->required(),

                        Forms\Components\Toggle::make('estado')
                            ->label('Estado Activo')
                            ->hiddenOn(['create'])
                            ->default(false),
                    ]),

                Section::make('Descripción del paquete')
                    ->columns(1)
                    ->schema([

                        RichEditor::make('descripcion')
                            ->required(),
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->searchable(),

                Tables\Columns\TextColumn::make('precio')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\IconColumn::make('estado')
                    ->boolean(),

                Tables\Columns\TextColumn::make('tipo_estadisticas')
                    ->label('tipo de estadisticas')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('max_redes_sociales')
                    ->label('limite redes sociales')
                    ->numeric(),

                Tables\Columns\TextColumn::make('max_productos')
                    ->label('limite productos')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('max_imagenes_producto')
                    ->label('limite imagenes/productos')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('max_categorias')
                    ->label('limite categorias')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('max_videos')
                    ->label('limite de videos')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('seo_level')
                    ->label('nivel de seo')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //max_redes_sociales
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make(),

                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([]),
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
            'index' => Pages\ListPaquetes::route('/'),
            'create' => Pages\CreatePaquete::route('/create'),
            'edit' => Pages\EditPaquete::route('/{record}/edit'),
        ];
    }
}
