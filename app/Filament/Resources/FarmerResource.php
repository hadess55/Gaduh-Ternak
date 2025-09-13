<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FarmerResource\Pages;
use App\Models\Farmer;
use Filament\Forms;
use Filament\Forms\Components\{TextInput, Textarea, Section, Select};
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;

class FarmerResource extends Resource
{
    protected static ?string $model = Farmer::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Data Peternak';
    protected static ?string $modelLabel = 'Peternak';
    protected static ?string $pluralModelLabel = 'Peternak';

    // Hanya perangkat_desa & admin yang boleh melihat resource ini
    public static function canViewAny(): bool
    {
        return Auth::user()?->hasAnyRole(['perangkat_desa','admin']) ?? false;
    }
    public static function canCreate(): bool { return false; } // input dari form publik
    public static function canEdit($record): bool { return Auth::user()?->hasAnyRole(['perangkat_desa','admin']) ?? false; }
    public static function canDelete($record): bool { return Auth::user()?->hasRole('admin') ?? false; }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Section::make('Data Peternak')->schema([
                TextInput::make('nama')->label('Nama')->required(),
                TextInput::make('nik')->label('NIK')->required()->maxLength(20),
                TextInput::make('telp')->label('No. Telepon'),
                Textarea::make('alamat')->label('Alamat')->columnSpanFull(),
                TextInput::make('desa')->label('Desa')->required(),
                TextInput::make('kecamatan')->label('Kecamatan')->required(),
                TextInput::make('jenis_ternak')->label('Jenis Ternak')->required(),
                TextInput::make('jumlah_ternak')->label('Jumlah Ternak')->numeric()->required(),
                Select::make('status')->label('Status')->options([
                    'pending'=>'Menunggu','validated'=>'Tervalidasi','rejected'=>'Ditolak'
                ])->disabled(), // status diubah lewat aksi
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
                TextColumn::make('status')->label('Status')->badge()->formatStateUsing(fn($s)=>[
                    'pending'=>'Menunggu','validated'=>'Tervalidasi','rejected'=>'Ditolak'
                ][$s] ?? $s),
                TextColumn::make('validated_at')->label('Validasi')->since()->toggleable(isToggledHiddenByDefault:true),
            ])
            ->filters([
                SelectFilter::make('status')->label('Status')->options([
                    'pending'=>'Menunggu','validated'=>'Tervalidasi','rejected'=>'Ditolak'
                ]),
            ])
            ->actions([
                Tables\Actions\Action::make('validate')->label('Validasi')->color('success')
                    ->visible(fn(Farmer $r)=>$r->status==='pending')
                    ->requiresConfirmation()
                    ->action(function(Farmer $r){
                        $r->update([
                            'status'=>'validated',
                            'validated_by'=>Auth::id(),
                            'validated_at'=>now(),
                            'catatan'=>$r->catatan
                        ]);
                    }),
                Tables\Actions\Action::make('reject')->label('Tolak')->color('danger')
                    ->visible(fn(Farmer $r)=>$r->status==='pending')
                    ->form([
                        Forms\Components\Textarea::make('catatan')->label('Alasan Penolakan')->required(),
                    ])
                    ->action(function(Farmer $r, array $data){
                        $r->update([
                            'status'=>'rejected',
                            'validated_by'=>Auth::id(),
                            'validated_at'=>now(),
                            'catatan'=>$data['catatan'] ?? null,
                        ]);
                    }),
                Tables\Actions\ViewAction::make()->label('Lihat'),
                Tables\Actions\EditAction::make()->label('Ubah'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFarmers::route('/'),
            // 'view'  => Pages\ViewFarmer::route('/{record}'),
            'edit'  => Pages\EditFarmer::route('/{record}/edit'),
        ];
    }
}
