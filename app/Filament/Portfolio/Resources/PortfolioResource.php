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
                Forms\Components\TextInput::make('titulo')
                    ->label('Nombre del proyecto')
                    ->required()
                    ->helperText('Nombre de tu proyecto')
                    ->maxLength(255),

                Forms\Components\Textarea::make('descripcion')
                    ->label('Descripción')
                    ->required()
                    ->maxLength(500),

                Forms\Components\FileUpload::make('portada')
                    ->label('Caratula de proyecto')
                    ->imageEditor()
                    ->helperText('Sube la caratula de tu proyecto.')
                    ->directory(fn() => 'portfolio/' . Str::slug(auth()->user()->name)),

                Forms\Components\Toggle::make('estado')
                    ->label('Estado Activo')
                    ->hiddenOn(['create'])
                    ->default(false),

                Forms\Components\TextInput::make('video_url')
                    ->label('url del video')
                    ->helperText('Ingrese url del video')
                    ->maxLength(255),

                Forms\Components\TextInput::make('orden')
                    ->label('Orden de visualización')
                    ->numeric()
                    ->default(0)
                    ->helperText('Define el orden en que aparecerá el portfolio'),
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
