<?php

namespace App\Filament\Portfolio\Resources;

use App\Filament\Portfolio\Resources\PortfoliodatosResource\Pages;
use App\Filament\Portfolio\Resources\PortfoliodatosResource\RelationManagers;
use App\Models\Portfoliodatos;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PortfoliodatosResource extends Resource
{
    protected static ?string $model = Portfoliodatos::class;

    protected static ?string $navigationIcon = 'heroicon-o-server';
    protected static ?string $navigationLabel = 'Datos del portfolio';
    protected static ?string $pluralModelLabel = 'Datos del portfolio';
    protected static ?string $navigationGroup = 'Configuracion Portfolio';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información del Proyecto')
                    ->schema([
                        Forms\Components\Textarea::make('implicacion')
                            ->label('Implicación / Rol en el proyecto')
                            ->rows(4)
                            ->maxLength(2000)
                            ->placeholder('Describe tu participación en el proyecto...')
                            ->columnSpanFull(),

                        Forms\Components\TagsInput::make('tecnologias')
                            ->label('Tecnologías utilizadas')
                            ->separator(',')
                            ->nestedRecursiveRules([
                                'min:1',
                                'max:50',
                            ])
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('cliente')
                            ->label('Cliente / Empresa')
                            ->maxLength(255)
                            ->placeholder('Nombre del cliente o empresa'),

                        Forms\Components\TextInput::make('enlace_proyecto')
                            ->label('Enlace del Proyecto')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://ejemplo.com')
                            ->helperText('URL completa del proyecto en línea'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('portfolio.titulo')
                    ->label('Portfolio')
                    ->searchable()
                    ->sortable()
                    ->limit(30)
                    ->tooltip(function ($state) {
                        return $state;
                    }),

                Tables\Columns\TextColumn::make('cliente')
                    ->searchable()
                    ->sortable()
                    ->limit(25)
                    ->toggleable(),

                Tables\Columns\TagsColumn::make('tecnologias')
                    ->separator(',')
                    ->limit(3)
                    ->toggleable(),

                Tables\Columns\TextColumn::make('implicacion')
                    ->label('Implicación')
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('enlace_proyecto')
                    ->label('Enlace')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('portfolio')
                    ->relationship('portfolio', 'titulo')
                    ->searchable()
                    ->preload()
                    ->label('Filtrar por Portfolio'),               
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
            'index' => Pages\ListPortfoliodatos::route('/'),

            'edit' => Pages\EditPortfoliodatos::route('/{record}/edit'),
        ];
    }
}
