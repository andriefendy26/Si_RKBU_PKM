<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BarangResource\Pages;
use App\Models\Barang;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Support\Icons\Heroicon;
use BackedEnum;
use UnitEnum;

class BarangResource extends Resource
{
    protected static ?string $model = Barang::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCube;
    protected static string|UnitEnum|null $navigationGroup = 'Master';
    protected static ?string $recordTitleAttribute = 'nama_barang';

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
                Select::make('id_kategori')
                    ->label('Kategori Barang')
                    ->relationship(
                        'kategori',
                        'name',
                        fn (\Illuminate\Database\Eloquent\Builder $query) => session()->has('tahun_anggaran_id')
                            ? $query->where('id_tahun_anggaran', session('tahun_anggaran_id'))
                            : $query
                    )
                    ->required()
                    ->searchable()
                    ->preload(),
                TextInput::make('kode_barang')->required()->maxLength(255),
                TextInput::make('nama_barang')->required()->maxLength(255),
                TextInput::make('spesifikasi')->maxLength(65535)->columnSpanFull(),
                TextInput::make('satuan')->required()->maxLength(255),
                TextInput::make('harga_estimasi')
                    ->numeric()
                    ->minValue(0)
                    ->default(0),
                Toggle::make('is_active')->label('Aktif')->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_barang')->searchable()->sortable(),
                TextColumn::make('nama_barang')->searchable()->sortable(),
                TextColumn::make('kategori.name')->label('Kategori')->sortable(),
                TextColumn::make('tahunAnggaran.name')->label('Tahun')->sortable(),
                TextColumn::make('satuan'),
                TextColumn::make('harga_estimasi')->numeric(decimalPlaces: 2)->sortable(),
                IconColumn::make('is_active')->boolean()->label('Aktif'),
                TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('nama_barang');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBarang::route('/'),
            'create' => Pages\CreateBarang::route('/create'),
            'edit' => Pages\EditBarang::route('/{record}/edit'),
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
