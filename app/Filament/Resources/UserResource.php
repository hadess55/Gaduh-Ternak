<?php

namespace App\Filament\Resources;

use App\Models\User;
use Filament\Facades\Filament;            // ✅ pakai guard milik Filament
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Illuminate\Database\Eloquent\Model;   // untuk type hint canEdit/canDelete
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Manajemen Sistem';
    protected static ?string $modelLabel = 'Pengguna';
    protected static ?string $pluralModelLabel = 'Pengguna';

    // ========= Akses hanya admin =========
    public static function canViewAny(): bool
    {
        return Filament::auth()?->user()?->hasRole('admin') ?? false;   // ✅
    }

    public static function canCreate(): bool
    {
        return Filament::auth()?->user()?->hasRole('admin') ?? false;   // ✅
    }

    public static function canEdit(Model $record): bool
    {
        return Filament::auth()?->user()?->hasRole('admin') ?? false;   // ✅
    }

    public static function canDelete(Model $record): bool
    {
        return Filament::auth()?->user()?->hasRole('admin') ?? false;   // ✅
    }

    // ========= Form =========
    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label('Nama')->required(),

            Forms\Components\TextInput::make('email')
                ->label('Email')->email()->required()
                ->unique(ignoreRecord: true),

            Forms\Components\TextInput::make('password')
                ->label('Password')->password()
                ->dehydrateStateUsing(fn ($s) => $s ? Hash::make($s) : null)  // ✅ tanpa named arg "callback"
                ->dehydrated(fn ($s) => filled($s))
                ->required(fn (string $context) => $context === 'create'),

            Forms\Components\Select::make('roles')
                ->label('Peran')
                ->relationship('roles', 'name')
                ->options(Role::pluck('name', 'name')->toArray())            // ✅ pluck benar
                ->multiple()
                ->preload()
                ->required(),
        ])->columns(2);
    }

    // ========= Tabel =========
    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nama')->searchable(),
                Tables\Columns\TextColumn::make('email')->label('Email'),
                Tables\Columns\TextColumn::make('roles.name')->label('Peran')->badge(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Ubah'),
                Tables\Actions\DeleteAction::make()->label('Hapus'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => UserResource\Pages\ListUsers::route('/'),
            'create' => UserResource\Pages\CreateUser::route('/create'),
            'edit'   => UserResource\Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
