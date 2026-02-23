<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RkbuResource\Pages;
use App\Models\RKBU;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Support\Icons\Heroicon;
use BackedEnum;
use UnitEnum;

class RkbuResource extends Resource
{
    protected static ?string $model = RKBU::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;
    protected static string|UnitEnum|null $navigationGroup = 'Transaksi';
    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('id_tahun_anggaran')
                    ->label('Tahun Anggaran')
                    ->relationship('tahunAnggaran', 'name')
                    ->default(fn () => session('tahun_anggaran_id'))
                    ->required()
                    ->searchable()
                    ->preload(),
                Select::make('id_barang')
                    ->label('Barang')
                    ->relationship(
                        'barang',
                        'nama_barang',
                        fn (\Illuminate\Database\Eloquent\Builder $query) => session()->has('tahun_anggaran_id')
                            ? $query->where('id_tahun_anggaran', session('tahun_anggaran_id'))
                            : $query
                    )
                    ->required()
                    ->searchable()
                    ->preload(),
                TextInput::make('jumlah')
                    ->numeric()
                    ->required()
                    ->minValue(1)
                    ->default(1),
                TextInput::make('total')
                    ->numeric()
                    ->minValue(0)
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tahunAnggaran.name')->label('Tahun Anggaran')->sortable(),
                TextColumn::make('barang.nama_barang')->label('Barang')->searchable()->sortable(),
                TextColumn::make('barang.kode_barang')->label('Kode')->sortable(),
                TextColumn::make('jumlah')->sortable(),
                TextColumn::make('total')->numeric(decimalPlaces: 2)->sortable(),
                TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRkbu::route('/'),
            'create' => Pages\CreateRkbu::route('/create'),
            'edit' => Pages\EditRkbu::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();

        if (session()->has('tahun_anggaran_id')) {
            $query->where('id_tahun_anggaran', session('tahun_anggaran_id'));
        }

        return $query;
    }
}
