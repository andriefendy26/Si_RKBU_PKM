<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TahunAnggaranResource\Pages;
use App\Models\TahunAnggaran;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Support\Icons\Heroicon;
use BackedEnum;
use UnitEnum;

class TahunAnggaranResource extends Resource
{
    protected static ?string $model = TahunAnggaran::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendar;
    protected static string|UnitEnum|null $navigationGroup = 'Master';
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Tahun Anggaran')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Tahun Anggaran')->searchable()->sortable(),
                TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('name', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTahunAnggaran::route('/'),
            'create' => Pages\CreateTahunAnggaran::route('/create'),
            'edit' => Pages\EditTahunAnggaran::route('/{record}/edit'),
        ];
    }
}
