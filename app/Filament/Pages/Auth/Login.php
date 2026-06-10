<?php

namespace App\Filament\Pages\Auth;

use App\Models\TahunAnggaran;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class Login extends \Filament\Auth\Pages\Login
{
    // public function form(Schema $schema): Schema
    // {
    //     return $schema
    //         ->components([
    //             $this->getEmailFormComponent(),
    //             $this->getPasswordFormComponent(),
    //             Select::make('tahun_anggaran_id')
    //                 ->label('Tahun Anggaran')
    //                 ->options(TahunAnggaran::orderBy('name', 'desc')->pluck('name', 'id'))
    //                 ->required(fn () => TahunAnggaran::exists())
    //                 ->searchable()
    //                 ->native(false)
    //                 ->placeholder(TahunAnggaran::exists() ? 'Pilih tahun anggaran' : 'Belum ada tahun—login sebagai super admin untuk menambah')
    //                 ->helperText(fn () => TahunAnggaran::exists() ? null : 'Super admin dapat menambah tahun anggaran setelah login.'),
    //             $this->getRememberFormComponent(),
    //         ]);
    // }

    // public function authenticate(): ?LoginResponse
    // {
    //     $response = parent::authenticate();

    //     if ($response !== null) {
    //         $data = $this->form->getState();
    //         Session::put('tahun_anggaran_id', $data['tahun_anggaran_id'] ?? null);
    //     }

    //     return $response;
    // }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('username')
                    ->label('Username')
                    
                    ->required()
                    ->autofocus(),
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
        $data = $this->form->getState();

        $credentials = [
            'username' => $data['username'],
            'password' => $data['password'],
        ];

        if (! Auth::attempt($credentials, $data['remember'] ?? false)) {
            $this->form->getField('username')->state(null); // optional reset field
            $this->addError('username', 'Username atau password salah.');
            return null;
        }

        // simpan tahun_anggaran_id ke session
        Session::put('tahun_anggaran_id', $data['tahun_anggaran_id'] ?? null);

        return app(LoginResponse::class);
    }
}
