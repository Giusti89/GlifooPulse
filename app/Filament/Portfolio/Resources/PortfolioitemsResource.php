<?php

namespace App\Filament\Portfolio\Resources;

use App\Filament\Portfolio\Resources\PortfolioitemsResource\Pages;
use App\Filament\Portfolio\Resources\PortfolioitemsResource\RelationManagers;
use App\Models\Portfolio;
use App\Models\Portfolioitem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Support\Facades\Crypt;
use Filament\Tables\Filters\SelectFilter;

class PortfolioitemsResource extends Resource
{
    protected static ?string $model = Portfolioitem::class;

    protected static ?string $navigationIcon = 'heroicon-s-camera';
    protected static ?string $navigationLabel = 'Fotos portfolio';
    protected static ?string $pluralModelLabel = 'Fotos portfolio';
    protected static ?string $navigationGroup = 'Configuracion Portfolio';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('portfolio_id')
                    ->label('Seleccione el producto')
                    ->relationship(
                        name: 'portfolio',
                        titleAttribute: 'titulo',
                        modifyQueryUsing: function ($query) {
                            $userId = auth()->id();

                            $query->whereHas('spot.suscripcion', function ($sq) use ($userId) {
                                $sq->where('user_id', $userId);
                            });
                        }
                    )
                    ->default(function () {
                        $encrypted = request()->get('portfolio');
                        return $encrypted ? Crypt::decrypt($encrypted) : null;
                    })->required(),

                Forms\Components\TextInput::make('titulo')
                    ->label('Nombre de la imagen')
                    ->helperText('Nombre de tu proyecto')
                    ->maxLength(255),

                Forms\Components\TextInput::make('descripcion')
                    ->label('Descripción')
                    ->helperText('Titulo/Nombre de tu proyecto')
                    ->maxLength(255),

                Forms\Components\TextInput::make('orden')
                    ->label('Orden de visualización')
                    ->numeric()
                    ->default(0)
                    ->helperText('Define el orden en que aparecerá la imagen'),

                Forms\Components\FileUpload::make('imagen')
                    ->label('Imagen del proyecto')
                    ->imageEditor()
                    ->helperText('Sube una imagen del proyecto.')
                    ->directory(fn() => 'portfolio/' . Str::slug(auth()->user()->name)),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                tables\Columns\TextColumn::make('portfolio.titulo')
                    ->searchable()
                    ->label('Titulo del proyecto'),

                Tables\Columns\ImageColumn::make('imagen')
                    ->label('Imagen')
                    ->size(150),

                tables\Columns\TextColumn::make('orden')
                    ->sortable()
                    ->label('Orden'),


            ])
            ->filters([
                SelectFilter::make('portfolio_id')
                    ->label('Filtrar por portfolio')
                    ->options(fn() => Portfolio::getCachedForSpot())
                    ->searchable()
                    ->preload()
                    ->placeholder('Todos los portfolios'),
            ])
            ->persistFiltersInSession()

            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->color('primary'),
                    Tables\Actions\DeleteAction::make(),


                ])
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
            'index' => Pages\ListPortfolioitems::route('/'),
            'create' => Pages\CreatePortfolioitems::route('/create'),
            'edit' => Pages\EditPortfolioitems::route('/{record}/edit'),
        ];
    }
}
