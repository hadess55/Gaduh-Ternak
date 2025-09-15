<?php

namespace App\Filament\Resources\DisputeResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Illuminate\Support\Facades\Auth;

class SusResponsesRelationManager extends RelationManager
{
    protected static string $relationship = 'susResponses';
    protected static ?string $title = 'Kuesioner SUS';
    protected static ?string $recordTitleAttribute = 'id';

    public function form(Forms\Form $form): Forms\Form
    {
        $likert = [1=>'1 - Sangat tidak setuju',2=>'2',3=>'3',4=>'4',5=>'5 - Sangat setuju'];

        return $form->schema([
            Forms\Components\Repeater::make('answers')->label('Jawaban (10 butir)')
                ->schema([
                    Forms\Components\Select::make('value')->label('Skor')->options($likert)->required(),
                ])
                ->minItems(10)->maxItems(10)
                ->default(fn () => array_map(fn () => ['value'=>3], range(1,10)))
                ->columns(1),
        ]);
    }

    public function mutateFormDataBeforeCreate(array $data): array
    {
        $data['answers'] = collect($data['answers'])->pluck('value')->values()->all();
        $data['user_id'] = Auth::id();
        return $data;
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('score')->label('Skor SUS')->badge(),
                Tables\Columns\TextColumn::make('submitted_at')->label('Diisi')->since(),
                Tables\Columns\TextColumn::make('user.name')->label('Pengisi')->toggleable(isToggledHiddenByDefault: true),
            ])
            ->headerActions([Tables\Actions\CreateAction::make()->label('Isi Kuesioner')])
            ->actions([Tables\Actions\DeleteAction::make()->label('Hapus')]);
    }
}
