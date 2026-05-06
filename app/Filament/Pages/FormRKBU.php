<?php

namespace App\Filament\Pages;

use App\Models\RKBU;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class FormRKBU extends Page implements HasSchemas
{
    use InteractsWithSchemas;

    protected string $view = 'filament.pages.form-r-k-b-u';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendar;

    protected static string|UnitEnum|null $navigationGroup = 'Transaksi';

    protected static ?string $navigationLabel = 'Form RKBU';

    protected static ?string $title = 'Form RKBU';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        return $data;
    }

    public function form(Schema $schema): Schema
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
                            ->relationship(
                                name: 'tahunAnggaran',
                                titleAttribute: 'name'
                            )
                            ->default(fn () => session('tahun_anggaran_id'))
                            ->searchable()
                            ->preload()
                            ->required()
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
                        TextInput::make('jumlah')
                            ->label('Jumlah Barang (Total)')
                            ->integer()
                            ->minValue(1)
                            ->suffix('unit')
                            ->required()
                            ->columnSpan(1),

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

            ])
            ->statePath('data')
            ->model(RKBU::class);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $data = $this->mutateFormDataBeforeCreate($data);

        RKBU::create($data);

        Notification::make()
            ->title('Data RKBU berhasil disimpan')
            ->success()
            ->send();

        $this->form->fill();
    }
}