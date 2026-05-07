<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RkbuResource\Pages;
use App\Models\RKBU;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Support\Icons\Heroicon;
use BackedEnum;
use pxlrbt\FilamentExcel\Actions\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Columns\Column;
use UnitEnum;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\Action;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RKBUExport;

class RkbuResource extends Resource
{
    protected static ?string $model = RKBU::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;
    protected static string|UnitEnum|null $navigationGroup = 'Transaksi';
    protected static ?string $navigationLabel = 'Data RKBU';
    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([

                // ── 1. Informasi Umum ──────────────────────────────────────
                Section::make('Informasi Umum')
                    ->description('Pilih tahun anggaran dan isi informasi dasar barang.')
                    ->icon(Heroicon::OutlinedInformationCircle)
                    ->schema([
                        Select::make('id_tahun_anggaran')
                            ->label('Tahun Anggaran')
                            ->relationship('tahunAnggaran', 'name')
                            ->default(fn () => session('tahun_anggaran_id'))
                            ->required()
                            ->searchable()
                            ->preload()
                            ->columnSpan(1),

                        TextInput::make('nama_barang')
                            ->label('Nama Barang')
                            ->placeholder('Contoh: Kursi Kantor')
                            ->required()
                            ->columnSpan(1),

                        TextInput::make('satuan')
                            ->label('Satuan')
                            ->placeholder('Contoh: Unit, Buah, Set')
                            ->required()
                            ->columnSpan(1),

                        Select::make('kondisi')
                            ->label('Kondisi Barang')
                            ->options([
                                'B'  => 'B — Baik',
                                'RR' => 'RR — Rusak Ringan',
                                'RB' => 'RB — Rusak Berat',
                            ])
                            ->required()
                            ->columnSpan(1),
                    ])
                    ->columns(2),

                // ── 2. Data Kuantitas ──────────────────────────────────────
                Section::make('Data Kuantitas')
                    ->description('Isi jumlah barang yang dimiliki, dibutuhkan, dan kekurangannya.')
                    ->icon(Heroicon::OutlinedCalculator)
                    ->schema([
                        // TextInput::make('jumlah')
                        //     ->label('Jumlah Barang (Total)')
                        //     ->integer()
                        //     ->minValue(1)
                        //     ->suffix('unit')
                        //     ->required()
                        //     ->columnSpan(1),

                        TextInput::make('tersedia')
                            ->label('Jumlah Yang Ada / Tersedia')
                            ->integer()
                            ->minValue(0)
                            ->suffix('unit')
                            ->required()
                            ->columnSpan(1),

                        TextInput::make('kebutuhan')
                            ->label('Kebutuhan')
                            ->integer()
                            ->minValue(1)
                            ->suffix('unit')
                            ->required()
                            ->columnSpan(1),

                        TextInput::make('kekurangan')
                            ->label('Kekurangan')
                            ->integer()
                            ->minValue(0)
                            ->suffix('unit')
                            ->required()
                            ->columnSpan(1),
                    ])
                    ->columns(2),

                // ── 3. Data Biaya ──────────────────────────────────────────
                Section::make('Data Biaya')
                    ->description('Masukkan estimasi biaya per unit dan total keseluruhan.')
                    ->icon(Heroicon::OutlinedBanknotes)
                    ->schema([
                        TextInput::make('perkiraan_biaya')
                            ->label('Perkiraan Biaya (per unit)')
                            ->numeric()
                            ->prefix('Rp')
                            ->minValue(0)
                            ->required()
                            ->columnSpan(1),

                        TextInput::make('total')
                            ->label('Total Biaya')
                            ->numeric()
                            ->prefix('Rp')
                            ->minValue(0)
                            ->required()
                            ->columnSpan(1),
                    ])
                    ->columns(2),

                // ── 4. Analisa & Keterangan ────────────────────────────────
                Section::make('Analisa & Keterangan')
                    ->description('Tuliskan analisa singkat terkait kebutuhan barang ini.')
                    ->icon(Heroicon::OutlinedDocumentText)
                    ->schema([
                        TextInput::make('analisa')
                            ->label('Analisa Kebutuhan')
                            ->placeholder('Jelaskan alasan kebutuhan barang secara singkat...')
                            ->required()
                            ->columnSpanFull(),
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // ── Selalu tampil (kolom utama) ────────────────────────────
                TextColumn::make('user.name')
                    ->label('Ruangan')
                    ->badge()
                    ->color('primary')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('tahunAnggaran.name')
                    ->label('Tahun Anggaran')
                    ->badge()
                    ->color('secondary')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('nama_barang')
                    ->label('Nama Barang')
                    ->sortable()
                    ->searchable()
                    ->wrap()
                    ->description(fn (RKBU $record): string => $record->satuan ?? ''),

                TextColumn::make('kondisi')
                    ->label('Kondisi')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'B'  => 'success',
                        'RR' => 'warning',
                        'RB' => 'danger',
                        default => 'gray',
                    })
                    ->alignCenter()
                    ->sortable(),

                TextColumn::make('kebutuhan')
                    ->label('Kebutuhan')
                    ->suffix(' unit')
                    ->alignCenter()
                    ->sortable()
                    ->description(fn (RKBU $record): string => 'Kurang: ' . ($record->kekurangan ?? 0) . ' unit'),

                TextColumn::make('total')
                    ->label('Total Biaya')
                    ->money('IDR')
                    ->alignEnd()
                    ->sortable()
                    ->description(fn (RKBU $record): string => 'Satuan: Rp ' . number_format($record->perkiraan_biaya ?? 0, 0, ',', '.')),

                // ── Tersembunyi by default (bisa ditampilkan via toggle) ───
                TextColumn::make('jumlah')
                    ->label('Jumlah')
                    ->suffix(' unit')
                    ->alignCenter()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('tersedia')
                    ->label('Tersedia')
                    ->suffix(' unit')
                    ->alignCenter()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('kekurangan')
                    ->label('Kekurangan')
                    ->suffix(' unit')
                    ->alignCenter()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('perkiraan_biaya')
                    ->label('Harga Satuan')
                    ->money('IDR')
                    ->alignEnd()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('analisa')
                    ->label('Analisa')
                    ->limit(40)
                    ->tooltip(fn (RKBU $record): string => $record->analisa ?? '')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('id_tahun_anggaran')
                    ->label('Tahun Anggaran')
                    ->relationship('tahunAnggaran', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('kondisi')
                    ->label('Kondisi')
                    ->options([
                        'B'  => 'B — Baik',
                        'RR' => 'RR — Rusak Ringan',
                        'RB' => 'RB — Rusak Berat',
                    ]),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ])
            // ->recordActions([
            //     Action::make('delete')
            //         ->requiresConfirmation()
            //         ->action(fn (Post $record) => $record->delete()),

            // ])
            ->headerActions([
                // ExportAction::make()
                //     ->label('Export Excel')
                //     ->exports([
                //         ExcelExport::make()
                //             ->withFilename(date('Y-m-d') . ' - RKBU Export')
                //             ->withColumns([
                //                 Column::make('users.name')->heading('Ruangan'),
                //                 Column::make('tahunAnggaran.name')->heading('Tahun Anggaran'),
                //                 Column::make('nama_barang')->heading('Nama Barang'),
                //                 Column::make('satuan')->heading('Satuan'),
                //                 Column::make('kondisi')->heading('Kondisi'),
                //                 Column::make('jumlah')->heading('Jumlah'),
                //                 Column::make('tersedia')->heading('Tersedia'),
                //                 Column::make('kebutuhan')->heading('Kebutuhan'),
                //                 Column::make('kekurangan')->heading('Kekurangan'),
                //                 Column::make('perkiraan_biaya')->heading('Perkiraan Biaya'),
                //                 Column::make('total')->heading('Total Biaya'),
                //                 Column::make('analisa')->heading('Analisa'),
                //             ]),
                //         ExcelExport::make('Export_e')
                //             ->withFilename(date('Y-m-d') . ' - RKBU Export')
                //             ->withSheets([
                //                 new OverriddenDataSheet(),
                //             ]),
                //     ]),
                Action::make('export_excel')
                    ->label('Export Excel')
                    // ->icon('heroicon-o-document-download')
                    ->action(function () {
                        $tahunAnggaranId = session('tahun_anggaran_id');

                        return Excel::download(new RKBUExport($tahunAnggaranId), 'RKBU.xlsx');
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped();
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListRkbu::route('/'),
            'create' => Pages\CreateRkbu::route('/create'),
            'edit'   => Pages\EditRkbu::route('/{record}/edit'),
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