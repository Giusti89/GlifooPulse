<?php

namespace App\Filament\Catalogo\Resources;

use App\Filament\Catalogo\Resources\VideosResource\Pages;
use App\Filament\Catalogo\Resources\VideosResource\RelationManagers;
use App\Models\Video;
use App\Models\Videos;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;


class VideosResource extends Resource
{
    protected static ?string $model = Video::class;


    protected static ?string $navigationIcon = 'heroicon-o-film';
    protected static ?string $navigationLabel = 'Video Publicitarios';
    protected static ?string $navigationGroup = 'Configuracion Catalogo';
    protected static ?string $pluralModelLabel = 'Galeria de imagenes';
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
                        Forms\Components\TextInput::make('titulo')
                            ->label('titulo del video')
                            ->required()
                            ->helperText('Ingrese titulo del video')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('url')
                            ->label('url del video')
                            ->required()
                            ->helperText('Ingrese titulo del video')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('orden')
                            ->label('Orden')
                            ->numeric()
                            ->default(0)
                            ->required()
                            ->minValue(0)
                            ->maxValue(100)
                            ->helperText('Define el orden de visualización (0 = primero)'),

                        Forms\Components\Toggle::make('estado')
                            ->label('estado del video')
                            ->hiddenOn(['create'])
                            ->default(false),

                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                tables\Columns\TextColumn::make('titulo')
                    ->label('Titulo'),
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
            'index' => Pages\ListVideos::route('/'),
            'create' => Pages\CreateVideos::route('/create'),
            'edit' => Pages\EditVideos::route('/{record}/edit'),
        ];
    }
}
