<?php

namespace App\Filament\Pages\Auth;

use App\Models\TahunAnggaran;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Session;

class Login extends \Filament\Auth\Pages\Login
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                Select::make('tahun_anggaran_id')
                    ->label('Tahun Anggaran')
                    ->options(TahunAnggaran::orderBy('name', 'desc')->pluck('name', 'id'))
                    ->required(fn () => TahunAnggaran::exists())
                    ->searchable()
                    ->native(false)
                    ->placeholder(TahunAnggaran::exists() ? 'Pilih tahun anggaran' : 'Belum ada tahun—login sebagai super admin untuk menambah')
                    ->helperText(fn () => TahunAnggaran::exists() ? null : 'Super admin dapat menambah tahun anggaran setelah login.'),
                $this->getRememberFormComponent(),
            ]);
    }

    public function authenticate(): ?LoginResponse
    {
        $response = parent::authenticate();

        if ($response !== null) {
            $data = $this->form->getState();
            Session::put('tahun_anggaran_id', $data['tahun_anggaran_id'] ?? null);
        }

        return $response;
    }
}
