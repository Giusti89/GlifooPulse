<?php

namespace App\Filament\Catalogo\Resources;

use App\Filament\Catalogo\Resources\SuportColorResource\Pages;
use App\Filament\Catalogo\Resources\SuportColorResource\RelationManagers;
use App\Models\SuportColor;
use Filament\Forms;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\Layout\View;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Components\Grid;

class SuportColorResource extends Resource
{
    protected static ?string $model = SuportColor::class;


    protected static ?string $navigationIcon = 'heroicon-o-paint-brush';
    protected static ?string $navigationLabel = 'Personalización';
    protected static ?string $pluralModelLabel = 'Personalización';
    protected static ?string $navigationGroup = 'Configuracion principal';

    protected static ?int $navigationSort = 3;


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
                // Creamos un Grid principal de 3 columnas para organizar el espacio
                Grid::make([
                    'default' => 1,
                    'lg' => 3,
                ])
                    ->schema([

                        // COLUMNA IZQUIERDA: Ocupa 2 columnas del Grid para tus inputs
                        Grid::make(1)
                            ->schema([
                                Section::make('Colores principales de la web')
                                    ->schema([
                                        ColorPicker::make('background')
                                            ->label('Color primario o background de la web')
                                            ->default('#ffffff')
                                            ->rgb()
                                            ->live(), // 🌟 Envía el cambio en tiempo real al servidor

                                        ColorPicker::make('colsecond')
                                            ->label('Color secundario (titulos Subtitulos)')
                                            ->default('#ffffff')
                                            ->rgb()
                                            ->live(),

                                        ColorPicker::make('ctexto')
                                            ->label('Color principal del texto de la web')
                                            ->default('#ffffff')
                                            ->rgb()
                                            ->live(),
                                    ]),

                                Section::make('Colores secundarios de la web')
                                    ->schema([
                                        ColorPicker::make('fondocolor')
                                            ->label('color principal de las tarjetas')
                                            ->default('#ffffff')
                                            ->rgb()
                                            ->live(),

                                        ColorPicker::make('text')
                                            ->label('Color titulos y subtitulos ')
                                            ->default('#333333')
                                            ->rgb()
                                            ->live(),

                                        ColorPicker::make('secondary')
                                            ->label('Color de textos')
                                            ->default('#333333')
                                            ->rgb()
                                            ->live(),

                                        ColorPicker::make('primary_button')
                                            ->label('Color de los botones')
                                            ->default('#333333')
                                            ->rgb()
                                            ->live(),

                                        ColorPicker::make('button_text')
                                            ->label('Color texto botones')
                                            ->default('#ffffff')
                                            ->rgb()
                                            ->live(),

                                        ColorPicker::make('footer')
                                            ->label('Color pie de pagina')
                                            ->default('#333333')
                                            ->rgb()
                                            ->live(),
                                    ]),
                            ])
                            ->columnSpan(['lg' => 2]),

                        Section::make('Vista previa en tiempo real')
                            ->description('Así es como se verán reflejados los colores en tu catálogo comercial.')
                            ->schema([
                                ViewField::make('preview')
                                    ->view('filament.forms.components.catalog-preview') // Nombre de tu nuevo archivo Blade
                            ])
                            ->columnSpan(['lg' => 1])
                            ->extraAttributes(['class' => 'lg:sticky lg:top-6']),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->contentGrid([
                'sm' => 1,
                'md' => 1,
                'lg' => 1,
            ])

            ->columns([
                Stack::make([
                    View::make('filament.tables.columns.vertical-colors')
                        ->components([
                            ColorColumn::make('spot.contenido.background')->label('Fondo principal'),
                            ColorColumn::make('spot.contenido.colsecond')->label('Color de soporte principal'),
                            ColorColumn::make('spot.contenido.ctexto')->label('Color principal texto'),
                            ColorColumn::make('fondocolor')->label('Fondo secundario'),
                            ColorColumn::make('secondary')->label('Titulos y subtitulos secundarios'),
                            ColorColumn::make('text')->label('Color de textos secundarios'),
                            ColorColumn::make('primary_button')->label('Color botones'),
                            ColorColumn::make('button_text')->label('Color texto botones'),
                            ColorColumn::make('footer')->label('Color pie de pagina'),
                        ]),
                ])
                    ->space(4),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListSuportColors::route('/'),
            'edit' => Pages\EditSuportColor::route('/{record}/edit'),
        ];
    }
}
