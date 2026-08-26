<?php

namespace App\Filament\Portfolio\Resources;

use App\Filament\Portfolio\Resources\SuportColorResource\Pages;
use App\Filament\Portfolio\Resources\SuportColorResource\RelationManagers;
use App\Models\SuportColor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\ColorColumn;
use Filament\Forms\Components\ColorPicker;


class SuportColorResource extends Resource
{
    protected static ?string $model = SuportColor::class;

    protected static ?string $navigationIcon = 'heroicon-o-paint-brush';
    protected static ?string $navigationLabel = 'Colorimetria';
    protected static ?string $pluralModelLabel = 'Colorimetría';
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
                Forms\Components\Section::make('Colores principales de la web')
                    ->schema([
                        ColorPicker::make('background')
                            ->label('Color primario o background de la web')
                            ->default('#ffffff')
                            ->helperText('HEX (#fff, #ffffff, #ffffff80), RGB).')
                            ->rgb()
                            ->validationMessages([
                                'max' => 'El código de color no puede superar los 30 caracteres.',
                            ]),

                        ColorPicker::make('colsecond')
                            ->label('Color secundario (titulos Subtitulos)')
                            ->default('#ffffff')
                            ->rgb()
                            ->validationMessages([
                                'max' => 'El código de color no puede superar los 30 caracteres.',
                            ]),

                        ColorPicker::make('ctexto')
                            ->label('Color principal del texto de la web')
                            ->default('#ffffff')
                            ->rgb()
                            ->validationMessages([
                                'max' => 'El código de color no puede superar los 30 caracteres.',
                            ]),
                    ]),
                Forms\Components\Section::make('Colores secundarios de la web ')
                    ->schema([
                        ColorPicker::make('fondocolor')
                            ->label('Color primario de elementos secundarios')
                            ->default('#ffffff')
                            ->helperText('HEX (#fff, #ffffff, #ffffff80), RGB).')
                            ->rgb()
                            ->validationMessages([
                                'max' => 'El código de color no puede superar los 30 caracteres.',
                            ]),
                        ColorPicker::make('text')
                            ->label('Color titulos y subtitulos de los elementos secundarios')
                            ->default('#333333')
                            ->helperText('HEX (#fff, #ffffff, #ffffff80), RGB).')
                            ->rgb()
                            ->validationMessages([
                                'max' => 'El código de color no puede superar los 30 caracteres.',
                            ]),
                        ColorPicker::make('secondary')
                            ->label('Color de textos de los elementos secundarios')
                            ->default('#333333')
                            ->helperText('HEX (#fff, #ffffff, #ffffff80), RGB).')
                            ->rgb()
                            ->validationMessages([
                                'max' => 'El código de color no puede superar los 30 caracteres.',
                            ]),
                        ColorPicker::make('primary_button')
                            ->label('Color de los botones')
                            ->default('#333333')
                            ->helperText('HEX (#fff, #ffffff, #ffffff80), RGB).')
                            ->rgb()
                            ->validationMessages([
                                'max' => 'El código de color no puede superar los 30 caracteres.',
                            ]),
                        ColorPicker::make('button_text')
                            ->label('Color texto botones')
                            ->default('#ffffff')
                            ->helperText('HEX (#fff, #ffffff, #ffffff80), RGB).')
                            ->rgb()
                            ->validationMessages([
                                'max' => 'El código de color no puede superar los 30 caracteres.',
                            ]),
                        ColorPicker::make('footer')
                            ->label('Color pie de pagina')
                            ->default('#333333')
                            ->helperText('HEX (#fff, #ffffff, #ffffff80), RGB).')
                            ->rgb()
                            ->validationMessages([
                                'max' => 'El código de color no puede superar los 30 caracteres.',
                            ]),
                    ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ColorColumn::make('spot.contenido.background')
                    ->label('Fondo principal'),

                ColorColumn::make('spot.contenido.colsecond')
                    ->label('Color de soporte principal'),

                ColorColumn::make('spot.contenido.ctexto')
                    ->label('Color principal texto'),

                ColorColumn::make('fondocolor')
                    ->label('Fondo secundario'),
                ColorColumn::make('secondary')
                    ->label('Titulos y subtitulos secundarios'),
                ColorColumn::make('text')
                    ->label('Color de textos secundarios'),

                ColorColumn::make('primary_button')
                    ->label('Color botones'),
                ColorColumn::make('button_text')
                    ->label('Color texto botones'),
                ColorColumn::make('footer')
                    ->label('Color pie de pagina'),

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
