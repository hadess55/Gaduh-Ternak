<?php

namespace App\Filament\Resources\DisputeResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Illuminate\Support\Facades\Auth;

class ActionsRelationManager extends RelationManager
{
    protected static string $relationship = 'actions';
    protected static ?string $title = 'Aksi';
    protected static ?string $recordTitleAttribute = 'type';

    public function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\Select::make('type')->label('Jenis Aksi')->options([
                'triage'=>'Triage','mediation'=>'Mediasi','settlement'=>'Kesepakatan',
                'escalation'=>'Eskalasi','note'=>'Catatan',
            ])->required(),
            Forms\Components\Textarea::make('notes')->label('Catatan')->columnSpanFull(),
        ]);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')->label('Jenis')->badge(),
                Tables\Columns\TextColumn::make('notes')->label('Catatan')->limit(40),
                Tables\Columns\TextColumn::make('created_at')->label('Dibuat')->dateTime('d/m/Y H:i'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Tambah Aksi')
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['actor_id'] = Auth::id();
                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make()->label('Hapus'),
            ]);
    }
}
