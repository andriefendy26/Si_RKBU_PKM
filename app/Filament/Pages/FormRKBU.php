<?php

namespace App\Filament\Pages;

use App\Models\RKBU;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
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

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data RKBU')
                    ->description('Lengkapi data rencana kebutuhan barang unit.')
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
                            ->required(),

                        Select::make('id_barang')
                            ->label('Barang')
                            ->relationship(
                                name: 'barang',
                                titleAttribute: 'nama_barang'
                            )
                            ->searchable()
                            ->preload()
                            ->required(),

                        TextInput::make('jumlah')
                            ->label('Jumlah')
                            ->integer()
                            ->minValue(1)
                            ->required(),

                        TextInput::make('total')
                            ->label('Total')
                            ->numeric()
                            ->prefix('Rp')
                            ->minValue(0)
                            ->required(),
                    ])
                    ->columns(2),
            ])
            ->statePath('data')
            ->model(RKBU::class);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        RKBU::create($data);

        Notification::make()
            ->title('Data RKBU berhasil disimpan')
            ->success()
            ->send();

        $this->form->fill();
    }
}