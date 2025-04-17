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
use Illuminate\Support\Facades\Storage;


class PaqueteResource extends Resource
{
    protected static ?string $model = Paquete::class;

    protected static ?string $navigationIcon = 'heroicon-s-briefcase';
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
                    ->columns(3)
                    ->schema([
                        Forms\Components\TextInput::make('nombre')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Textarea::make('descripcion')
                            ->required(),

                        Forms\Components\TextInput::make('precio')
                            ->required()
                            ->numeric()
                            ->default(0.00),

                        Forms\Components\TextInput::make('enlace')
                            ->prefix('https://wa.me/')
                            ->maxLength(255),

                        Forms\Components\Select::make('landing_id')
                            ->label('Landing')
                            ->relationship('landing', 'nombre')
                            ->required()
                            ->default(fn() => self::getLandingFromLocalId(request()->query('landing_id')))
                            ->live(),

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
                Tables\Columns\TextColumn::make('landing.nombre')
                    ->numeric()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\ImageColumn::make('image_url')
                    ->disk('public')
                    ->label('Imagen'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function (paquete $paquete) {
                        Storage::delete('public/' . $paquete->image_url);
                    })


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
            'index' => Pages\ListPaquetes::route('/'),
            'create' => Pages\CreatePaquete::route('/create'),
            'edit' => Pages\EditPaquete::route('/{record}/edit'),
        ];
    }
}
