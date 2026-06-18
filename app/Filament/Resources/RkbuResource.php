<?php

namespace App\Filament\Resources;

use App\Exports\RKBUExport;
use App\Filament\Resources\RkbuResource\Pages;
use App\Models\RKBU;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
// use Filament\Tables\Actions\Action as TableAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Maatwebsite\Excel\Facades\Excel;
use UnitEnum;

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
                                'B' => 'B — Baik',
                                'RR' => 'RR — Rusak Ringan',
                                'RB' => 'RB — Rusak Berat',
                            ])
                            ->required()
                            ->columnSpan(1),
                    ])
                    ->columns(2),

                Section::make('Data Kuantitas')
                    ->description('Isi jumlah barang yang dimiliki, dibutuhkan, dan kekurangannya.')
                    ->icon(Heroicon::OutlinedCalculator)
                    ->schema([
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
                    ->color(fn (?string $state): string => match ($state) {
                        'B' => 'success',
                        'RR' => 'warning',
                        'RB' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'B' => 'Baik',
                        'RR' => 'Rusak Ringan',
                        'RB' => 'Rusak Berat',
                        default => '-',
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

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'pending' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        'pending' => 'Menunggu',
                        default => 'Menunggu',
                    })
                    ->sortable(),

                TextColumn::make('approvedBy.name')
                    ->label('Disetujui Oleh')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('approved_at')
                    ->label('Tanggal Approve')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('rejected_at')
                    ->label('Tanggal Reject')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

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
                        'B' => 'B — Baik',
                        'RR' => 'RR — Rusak Ringan',
                        'RB' => 'RB — Rusak Berat',
                    ]),

                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Menunggu',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                    ]),
            ])
            ->recordActions([
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (RKBU $record): bool => 
                        auth()->user()->can('approve_rkbu')
                    )
                    ->authorize(fn (): bool => auth()->user()->can('approve_rkbu'))
                    ->action(function (RKBU $record): void {
                        $record->update([
                            'status' => 'approved',
                            'approved_by' => auth()->id(),
                            'approved_at' => now(),
                            'rejected_at' => null,
                        ]);

                        Notification::make()
                            ->title('Data RKBU berhasil disetujui')
                            ->success()
                            ->send();
                    }),

                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (RKBU $record): bool => 
                        auth()->user()->can('reject_rkbu')
                    )
                    ->authorize(fn (): bool => auth()->user()->can('reject_rkbu'))
                    ->action(function (RKBU $record): void {
                        $record->update([
                            'status' => 'rejected',
                            'approved_by' => auth()->id(),
                            'approved_at' => null,
                            'rejected_at' => now(),
                        ]);

                        Notification::make()
                            ->title('Data RKBU berhasil ditolak')
                            ->danger()
                            ->send();
                    }),

            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ])
            ->headerActions([
                Action::make('export_excel')
                    ->label('Export Excel')
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