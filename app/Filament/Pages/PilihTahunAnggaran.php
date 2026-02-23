<?php

namespace App\Filament\Pages;

use App\Models\TahunAnggaran;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use BackedEnum;
use UnitEnum;
use Illuminate\Support\Facades\Session;

class PilihTahunAnggaran extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendar;
    protected static string|UnitEnum|null $navigationGroup = 'Pengaturan';
    protected static ?string $title = 'Pilih Tahun Anggaran';
    protected static ?string $navigationLabel = 'Pilih Tahun Anggaran';
    protected static ?string $slug = 'pilih-tahun-anggaran';

    public ?string $tahun_anggaran_id = null;

    public function mount(): void
    {
        $this->tahun_anggaran_id = (string) Session::get('tahun_anggaran_id', '');
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('tahun_anggaran_id')
                    ->label('Tahun Anggaran Aktif')
                    ->options(
                        TahunAnggaran::orderBy('name', 'desc')->pluck('name', 'id')
                    )
                    ->searchable()
                    ->live()
                    ->default(Session::get('tahun_anggaran_id'))
                    ->afterStateUpdated(function ($state): void {
                        if ($state) {
                            Session::put('tahun_anggaran_id', $state);
                            Notification::make()
                                ->title('Tahun anggaran dipilih')
                                ->success()
                                ->send();
                        }
                    }),
            ]);
    }

    public static function getNavigationSort(): ?int
    {
        return 1;
    }
}
