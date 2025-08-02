<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LandingResource\Pages;
use App\Filament\Resources\LandingResource\RelationManagers;
use App\Models\Landing;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Carbon\Carbon;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class LandingResource extends Resource
{
    protected static ?string $model = Landing::class;
    protected static ?string $navigationLabel = 'Maquetas';
    protected static ?string $navigationIcon = 'heroicon-m-rocket-launch';
    protected static ?string $navigationGroup = 'Productos Glifoo';
    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nombre')
                    ->required()
                    ->maxLength(255)
                    ->helperText('Este nombre debe ser identico al del archivo html'),


                Forms\Components\Textarea::make('descripcion')
                    ->required(),

                Forms\Components\TextInput::make('precio')
                    ->required()
                    ->numeric()
                    ->default(0.00),

                Forms\Components\Select::make('paquete_id')
                    ->label('Paquete')
                    ->relationship('paquete', 'nombre')
                    ->required()
                    ->live(),

                Forms\Components\FileUpload::make('preview_url')
                    ->label('Muestra visual')
                    ->image()
                    ->imageEditor()
                    ->directory('plantilla')
                    ->getUploadedFileNameForStorageUsing(
                        fn(TemporaryUploadedFile $file): string =>
                        Carbon::now()->format('Ymd_His') . '_' . $file->getClientOriginalName()
                    )
                    ->helperText(function ($state) {
                        return $state ? 'Subido: ' . Carbon::now()->format('d/m/Y H:i') : 'Suba su archivo aquí';
                    }),

                Forms\Components\TextInput::make('grupo')
                    ->required()
                    ->maxLength(255)
                    ->helperText('Nombre de la carpeta en que se almacenara la plantilla'),

                Forms\Components\Toggle::make('pago')
                    ->label('Es de pago?')
                    ->default(false),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('descripcion'),

                Tables\Columns\TextColumn::make('paquete.nombre')
                    ->searchable(),

                Tables\Columns\TextColumn::make('grupo')
                    ->searchable(),

                Tables\Columns\TextColumn::make('pago')
                    ->label('plantilla de pago')
                    ->badge()
                    ->formatStateUsing(fn(bool $state): string => $state ? 'Sí' : 'No')
                    ->color(fn(bool $state): string => $state ? 'success' : 'danger'),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListLandings::route('/'),
            'create' => Pages\CreateLanding::route('/create'),
            'edit' => Pages\EditLanding::route('/{record}/edit'),
        ];
    }
}
