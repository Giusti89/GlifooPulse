<?php

namespace App\Filament\Catalogo\Resources;

use App\Filament\Catalogo\Resources\SpotResource\Pages;
use App\Filament\Catalogo\Resources\SpotResource\RelationManagers;
use App\Filament\Helpers\SeoVisibilityHelper;
use App\Models\Landing;
use App\Models\Spot;
use App\Models\Suscripcion;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Set;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Filament\Forms\Components\ViewField;
use Illuminate\Support\Facades\Storage;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ColorPicker;

class SpotResource extends Resource
{
    protected static ?string $model = Spot::class;

    protected static ?string $navigationIcon = 'heroicon-m-wrench';
    protected static ?string $navigationLabel = 'Datos iniciales';
    protected static ?string $pluralModelLabel = 'Configuracion Inicial';
    protected static ?string $navigationGroup = 'Configuracion Inicial';

    protected static ?int $navigationSort = 1;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('suscripcion', fn($q) => $q->where('user_id', auth()->id()))
            ->with('seo');
    }
    public static function form(Form $form): Form
    {
        $user = Auth::user();
        $isFree = $user?->suscripcion?->paquete?->precio == 0;
        return $form
            ->schema([
                Wizard::make(array_filter([
                    Step::make('Datos generales')
                        ->schema([
                            Forms\Components\TextInput::make('titulo')
                                ->label('Nombre de Empresa')
                                ->unique(ignoreRecord: true)
                                ->required()
                                ->maxLength(255),

                            Forms\Components\TextInput::make('slug')
                                ->label('Nombre Link')
                                ->unique(ignoreRecord: true)
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

                                    return $landings->mapWithKeys(fn($landing) => [
                                        $landing->id => $landing->nombrecomercial ?? $landing->nombre ?? 'Sin nombre',
                                    ]);
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
                        ]),

                    !$isFree ? Step::make('SEO')
                        ->schema([
                            TextInput::make('seo_title')
                                ->label('Título SEO')
                                ->maxLength(60)
                                ->visible(fn() => SeoVisibilityHelper::visibleForSeoLevel('basico')),

                            Textarea::make('descripcion')
                                ->label('Descripcion larga')
                                ->visible(fn() => SeoVisibilityHelper::visibleForSeoLevel('basico'))
                                ->maxLength(500),

                            Textarea::make('seo_descripcion')
                                ->label('Descripción SEO')
                                ->visible(fn() => SeoVisibilityHelper::visibleForSeoLevel('medio', 'completo'))

                                ->maxLength(160),

                            TextInput::make('seo_keyword')
                                ->label('Palabras clave')
                                ->visible(fn() => SeoVisibilityHelper::visibleForSeoLevel('completo'))
                                ->helperText('Separadas por coma'),
                        ])
                        : null,
                    Step::make('Logotipo de empresa')
                        ->schema([
                            Forms\Components\FileUpload::make('logo_url')
                                ->label('Logotipo de empresa')
                                ->image()
                                ->imageEditor()
                                ->directory(fn() => 'paquetes/' . Str::slug(auth()->user()->name)),

                            Forms\Components\FileUpload::make('banner_url')
                                ->label('Banner principal')
                                ->image()
                                ->imageEditor()
                                ->directory(fn() => 'paquetes/' . Str::slug(auth()->user()->name)),


                            ColorPicker::make('background')
                                ->label('Color primario ')
                                ->helperText('Se admiten valores hexadecimales')
                                ->default('#ffffff')
                                ->rgb(),

                            ColorPicker::make('ctexto')
                                ->label('Color del los textos')
                                ->default('#ffffff')
                                ->helperText('Se admiten valores hexadecimales')
                                ->rgb(),

                            ColorPicker::make('colsecond')
                                ->label('Color secundario')
                                ->default('#ffffff')
                                ->helperText('Se admiten valores hexadecimales')
                                ->rgb(),

                            TextInput::make('phone')
                                ->label(__('Numero de contacto para los articulos'))
                                ->tel()
                                ->maxLength(12)
                                ->minLength(8)
                                ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/')
                                ->helperText('ejemplo: +591111111')
                                ->required(),
                        ])

                ]))
                    ->columnSpan('full'),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->paginated(false)
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
                    ->label('Configuración'),
                Action::make('vista_preliminar')
                    ->label('Vista preliminar')
                    ->icon('heroicon-o-eye')
                    ->url(fn($record) => $record->slug ? route('publicidad', ['slug' => $record->slug]) : null)
                    ->openUrlInNewTab()
                    ->visible(fn($record) => filled($record->slug)),
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
            'index' => Pages\ListSpots::route('/'),
            'edit' => Pages\EditSpot::route('/{record}/edit'),
        ];
    }
}
