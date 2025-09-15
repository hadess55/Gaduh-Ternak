<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FarmerResource\Pages;
use App\Models\Farmer;
use Filament\Forms;
use Filament\Forms\Components\{TextInput, Textarea, Section, Select};
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class FarmerResource extends Resource
{
    protected static ?string $model = Farmer::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Data Peternak';
    protected static ?string $modelLabel = 'Peternak';
    protected static ?string $pluralModelLabel = 'Peternak';

    /** 👇 IZIN HALAMAN */
    public static function canViewAny(): bool
    {
        return Auth::user()?->hasAnyRole(['admin','perangkat desa']) ?? false;
    }
    public static function canCreate(): bool
    {
        return Auth::user()?->hasRole('admin') ?? false; // hanya admin
    }
    public static function canEdit(Model $record): bool
    {
        return Auth::user()?->hasRole('admin') ?? false; // hanya admin
    }
    public static function canDelete(Model $record): bool
    {
        return Auth::user()?->hasRole('admin') ?? false; // hanya admin
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Section::make('Data Peternak')->schema([
                TextInput::make('nama')->label('Nama')->required(),
                TextInput::make('nik')->label('NIK')->required()->maxLength(20)->unique(ignoreRecord: true),
                TextInput::make('telp')->label('No. Telepon'),
                Textarea::make('alamat')->label('Alamat')->columnSpanFull(),
                TextInput::make('desa')->label('Desa')->required(),
                TextInput::make('kecamatan')->label('Kecamatan')->required(),
                TextInput::make('jenis_ternak')->label('Jenis Ternak')->required(),
                TextInput::make('jumlah_ternak')->label('Jumlah Ternak')->numeric()->required(),

                // status hanya terlihat saat create/edit oleh admin
                Select::make('status')->label('Status')->options([
                    'pending'   => 'Menunggu',
                    'validated' => 'Tervalidasi',
                    'rejected'  => 'Ditolak',
                ])->default('pending'),

                Textarea::make('catatan')->label('Catatan')->columnSpanFull(),
            ])->columns(2),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')->label('Nama')->searchable(),
                TextColumn::make('nik')->label('NIK')->searchable(),
                TextColumn::make('desa')->label('Desa'),
                TextColumn::make('kecamatan')->label('Kecamatan'),
                TextColumn::make('jenis_ternak')->label('Jenis'),
                TextColumn::make('jumlah_ternak')->label('Jml'),
                TextColumn::make('status')
                    ->label('Status')->badge()
                    ->formatStateUsing(fn (string $state) => [
                        'pending'=>'Menunggu','validated'=>'Tervalidasi','rejected'=>'Ditolak',
                    ][$state] ?? $state)
                    ->color(fn (string $state) => match ($state) {
                        'pending'   => 'warning',
                        'validated' => 'success',
                        'rejected'  => 'danger',
                        default     => 'gray',
                    }),
                TextColumn::make('validated_at')->label('Divalidasi')->since()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')->label('Status')->options([
                    'pending'=>'Menunggu','validated'=>'Tervalidasi','rejected'=>'Ditolak',
                ]),
            ])
            ->actions([
                /** ✅ Aksi validasi khusus perangkat_desa */
                Tables\Actions\Action::make('validate')
                    ->label('Validasi')->color('success')
                    ->visible(fn ($record) =>
                        Auth::user()?->hasRole('perangkat desa') && $record->status === 'pending')
                    ->requiresConfirmation()
                    ->action(function (Farmer $record) {
                        $record->update([
                            'status'        => 'validated',
                            'validated_by'  => Auth::id(),
                            'validated_at'  => now(),
                        ]);
                    }),

                Tables\Actions\Action::make('reject')
                    ->label('Tolak')->color('danger')
                    ->visible(fn ($record) =>
                        Auth::user()?->hasRole('perangkat desa') && $record->status === 'pending')
                    ->form([
                        Forms\Components\Textarea::make('catatan')->label('Alasan')->required(),
                    ])
                    ->action(function (Farmer $record, array $data) {
                        $record->update([
                            'status'        => 'rejected',
                            'validated_by'  => Auth::id(),
                            'validated_at'  => now(),
                            'catatan'       => $data['catatan'] ?? null,
                        ]);
                    }),

                /** Aksi untuk admin saja */
                Tables\Actions\ViewAction::make()->label('Lihat'),
                Tables\Actions\EditAction::make()
                    ->label('Ubah')
                    ->visible(fn () => Auth::user()?->hasRole('admin')),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->visible(fn () => Auth::user()?->hasRole('admin')),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListFarmers::route('/'),
            'create' => Pages\CreateFarmer::route('/create'),
            'edit'   => Pages\EditFarmer::route('/{record}/edit'),
        ];
    }
}
