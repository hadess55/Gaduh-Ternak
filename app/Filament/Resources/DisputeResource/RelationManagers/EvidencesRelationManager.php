<?php

namespace App\Filament\Resources\DisputeResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Illuminate\Support\Facades\Auth; // <-- WAJIB

class EvidencesRelationManager extends RelationManager
{
    protected static string $relationship = 'evidences';
    protected static ?string $title = 'Bukti';

    public function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\Select::make('type')->label('Jenis Bukti')->options([
                'photo'=>'Foto','video'=>'Video','doc'=>'Dokumen',
            ])->required(),
            Forms\Components\FileUpload::make('path')->label('Berkas')
                ->disk('public')->directory('evidences')
                ->downloadable()->previewable()->required(),
        ]);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')->label('Jenis')->badge(),
                Tables\Columns\TextColumn::make('path')->label('Lokasi Berkas')->limit(30),
                Tables\Columns\TextColumn::make('sha256')->label('Hash')->limit(16)->tooltip(fn($r)=>$r->sha256),
                Tables\Columns\TextColumn::make('uploaded_at')->label('Diunggah')->since(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Tambah Bukti')
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['uploaded_by'] = Auth::id(); // ⬅️ ganti auth()->id() -> Auth::id()
                        $data['uploaded_at'] = now();
                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make()->label('Hapus'),
            ]);
    }
}
