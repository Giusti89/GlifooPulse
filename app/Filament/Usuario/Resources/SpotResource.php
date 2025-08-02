<?php

namespace App\Filament\Usuario\Resources;

use App\Filament\Usuario\Resources\SpotResource\Pages;
use App\Filament\Usuario\Resources\SpotResource\RelationManagers;
use App\Livewire\LandingVista;
use App\Models\Landing;
use App\Models\Spot;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Filament\Forms\Set;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\ViewField;
use Illuminate\Support\Facades\Storage;

class SpotResource extends Resource
{
    protected static ?string $model = Spot::class;

    protected static ?string $navigationIcon = 'heroicon-m-wrench';
    protected static ?string $navigationLabel = 'Configuracion Inicial';
    protected static ?string $pluralModelLabel = 'Configuracion Inicial';
    protected static ?string $navigationGroup = 'Configuracion pagina web';

    protected static ?int $navigationSort = 1;


    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('suscripcion', function ($query) {
                $query->where('user_id', auth()->id());
            });
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make('Publicidad')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('titulo')
                            ->label('Nombre de Empresa')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('slug')
                            ->label('Nombre Link')
                            ->prefix('https://glifoo.org/')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state)))
                            ->maxLength(255),

                        Forms\Components\Select::make('tipolanding')
                            ->label('Plantilla (Landing) disponible')
                            ->options(function () {
                                $user = Auth::user();

                                if (!$user || !$user->suscripcion || !$user->suscripcion->paquete) {
                                    return [];
                                }

                                $landingsGratis = $user->suscripcion->paquete->landings()
                                    ->where('pago', false)
                                    ->get();

                                $landingsCompradas = Landing::join('landing_user_compras', 'landings.id', '=', 'landing_user_compras.landing_id')
                                    ->where('landing_user_compras.user_id', $user->id)
                                    ->select('landings.id', 'landings.nombre')
                                    ->get();

                                $landings = $landingsGratis->concat($landingsCompradas)->unique('id');

                                return $landings->pluck('nombre', 'id');
                            })
                            ->searchable()
                            ->reactive()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $landing = \App\Models\Landing::find($state);

                                if ($landing) {
                                    $landing->preview_url = Storage::url($landing->preview_url);
                                    $set('landing_preview', $landing->toArray());
                                }
                            }),

                        Forms\Components\Hidden::make('landing_preview')
                            ->dehydrated(false),
 
                        ViewField::make('landing_preview')
                            ->label('Vista previa de plantilla')
                            ->view('filament.forms.components.landing-preview')
                            ->dehydrated(false)
                            ->columnSpan('full'),

                        Forms\Components\Toggle::make('estado')
                            ->label('Publicar web')
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
                    ->label('Nombre de Empresa'),

                tables\Columns\TextColumn::make('slug')
                    ->label('Url'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Llenar datos'),
                Action::make('vista_preliminar')
                    ->label('Vista preliminar')
                    ->icon('heroicon-o-eye')
                    ->url(fn($record) => $record->slug ? route('publicidad', ['slug' => $record->slug]) : null)
                    ->openUrlInNewTab()
                    ->visible(fn($record) => filled($record->slug)),

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
            'index' => Pages\ListSpots::route('/'),

            'edit' => Pages\EditSpot::route('/{record}/edit'),
        ];
    }
}
