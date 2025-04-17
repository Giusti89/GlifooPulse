<?php

namespace App\Filament\Usuario\Resources;

use App\Filament\Usuario\Resources\SocialResource\Pages;
use App\Filament\Usuario\Resources\SocialResource\RelationManagers;
use App\Models\Social;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms\Components\Section;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class SocialResource extends Resource
{
    protected static ?string $model = Social::class;

    protected static ?string $navigationIcon = 'heroicon-m-chat-bubble-oval-left-ellipsis';
    protected static ?string $navigationLabel = 'Configuracion Enlaces Sociales';
    protected static ?string $pluralModelLabel = 'Configuracion Enlaces';

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
                Section::make('Social')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('spot_id')
                            ->label('Seleccione Proyecto')
                            ->options(
                                \App\Models\Spot::whereHas('suscripcion', function ($query) {
                                    $query->where('user_id', Auth::id());
                                })->pluck('titulo', 'id')
                            )
                            ->required(),

                        Forms\Components\TextInput::make('nombre')
                            ->label(' Nombre red social')
                            ->helperText('Your full name here, including any middle names.')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('url')
                            ->label(' Enlace red social')
                            ->helperText('Your full name here, including any middle names.')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\FileUpload::make('image_url')
                            ->image()
                            ->maxSize(2048)
                            ->label('Logo red social')
                            ->imageEditor()
                            ->directory(function () {
                                $user = auth()->user();

                                return 'paquetes/' . Str::slug($user->name);
                            })
                            ->required(),

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
                 Tables\Actions\DeleteAction::make()
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
            'create' => Pages\CreateSocial::route('/create'),
            'edit' => Pages\EditSocial::route('/{record}/edit'),
        ];
    }
}
