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
                                return \App\Models\Enlace::pluck('nombre', 'id')->toArray();
                            })
                            ->searchable()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $enlace = \App\Models\Enlace::find($state);

                                if ($enlace) {
                                    $set('nombre', $enlace->nombre);

                                    $userDirectory = 'paquetes/' . \Str::slug(auth()->user()->name);
                                    $timestamp = now()->format('Ymd_His');
                                    $originalName = pathinfo($enlace->logo_path, PATHINFO_FILENAME);
                                    $extension = pathinfo($enlace->logo_path, PATHINFO_EXTENSION);
                                    
                                    $imageName =  "{$originalName}_{$timestamp}.{$extension}";
                                    $newPath = "$userDirectory/$imageName";

                                    if (!\Storage::disk('public')->exists($newPath)) {
                                        \Storage::disk('public')->copy($enlace->logo_path, $newPath);
                                    }

                                    $set('image_url', [$newPath]);
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

                        Forms\Components\FileUpload::make('image_url')
                            ->image()
                            
                            ->maxSize(2048)
                            ->visibility('public')
                            ->label('Logo red social')
                            ->imageEditor()
                            ->directory(function () {
                                $user = auth()->user();

                                return 'paquetes/' . Str::slug($user->name);
                            }),



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
