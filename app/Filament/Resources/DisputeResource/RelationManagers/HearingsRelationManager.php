<?php

namespace App\Filament\Resources\DisputeResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;

class HearingsRelationManager extends RelationManager
{
    protected static string $relationship = 'hearings';
    protected static ?string $title = 'Mediasi / Hearing';
    protected static ?string $recordTitleAttribute = 'scheduled_at';

    public function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\DateTimePicker::make('scheduled_at')->label('Jadwal')->seconds(false)->required(),
            Forms\Components\TextInput::make('place')->label('Tempat'),
            Forms\Components\Select::make('mediator_id')->label('Mediator')
                ->relationship('mediator','name')->searchable(),
            Forms\Components\Select::make('result')->label('Hasil')
                ->options(['scheduled'=>'Dijadwalkan','success'=>'Berhasil','failed'=>'Gagal','rescheduled'=>'Dijadwal Ulang'])
                ->default('scheduled'),
            Forms\Components\Textarea::make('minutes')->label('Notulensi')->columnSpanFull(),
        ])->columns(2);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('scheduled_at')->label('Jadwal')->dateTime('d/m/Y H:i'),
                Tables\Columns\TextColumn::make('place')->label('Tempat')->limit(20),
                Tables\Columns\TextColumn::make('mediator.name')->label('Mediator'),
                Tables\Columns\TextColumn::make('result')->label('Hasil')->badge()
                    ->formatStateUsing(fn (string $state) => [
                        'scheduled'=>'Dijadwalkan','success'=>'Berhasil','failed'=>'Gagal','rescheduled'=>'Dijadwal Ulang',
                    ][$state] ?? $state),
            ])
            ->headerActions([Tables\Actions\CreateAction::make()->label('Tambah Jadwal')])
            ->actions([Tables\Actions\EditAction::make()->label('Ubah'), Tables\Actions\DeleteAction::make()->label('Hapus')]);
    }
}
