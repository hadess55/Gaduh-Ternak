<?php

namespace App\Filament\Resources;

use App\Models\Dispute;
use Filament\Forms;
use Filament\Forms\Components\{Section, Select, TextInput, DateTimePicker, Textarea, Radio};
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\{Filter, SelectFilter};
use Illuminate\Database\Eloquent\Builder;

class DisputeResource extends Resource
{
    protected static ?string $model = Dispute::class;
    protected static ?string $navigationIcon = 'heroicon-o-scale';
    protected static ?string $navigationGroup = 'Sengketa';
    protected static ?string $modelLabel = 'Sengketa';
    protected static ?string $pluralModelLabel = 'Sengketa';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Section::make('Data Kasus')->schema([
                Select::make('reporter_id')
                    ->label('Pelapor')
                    ->placeholder('Pilih pelapor')
                    ->relationship('reporter', 'name')
                    ->searchable()
                    ->required(),

                Select::make('defendant_id')
                    ->label('Terlapor')
                    ->placeholder('Pilih terlapor')
                    ->relationship('defendant', 'name')
                    ->searchable(),

                TextInput::make('location')
                    ->label('Lokasi Kejadian')
                    ->placeholder('Contoh: Desa Sukamaju, Kec. Sukamakmur')
                    ->required(),

                DateTimePicker::make('occurred_at')
                    ->label('Waktu Kejadian')
                    ->seconds(false)
                    ->displayFormat('d/m/Y H:i')
                    ->required(),

                Textarea::make('description')
                    ->label('Deskripsi Kejadian')
                    ->placeholder('Tuliskan kronologi singkat')
                    ->columnSpanFull(),

                Radio::make('legal_route')
                    ->label('Jalur Hukum')
                    ->options([
                        'civil'   => 'Perdata',
                        'criminal'=> 'Pidana',
                        'hybrid'  => 'Campuran',
                    ])->inline()->required(),

                Select::make('legal_basis_id')
                    ->label('Dasar Hukum')
                    ->placeholder('Pilih rujukan pasal')
                    ->relationship('legalBasis', 'title')
                    ->searchable(),

                TextInput::make('damage_estimate')
                    ->label('Estimasi Kerugian')
                    ->numeric()
                    ->prefix('Rp'),
            ])->columns(2),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('#')->sortable(),
                TextColumn::make('reporter.name')->label('Pelapor')->searchable(),
                TextColumn::make('defendant.name')->label('Terlapor')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('legal_route')
                    ->label('Jalur Hukum')
                    ->badge()
                    ->formatStateUsing(fn ($s) => [
                        'civil' => 'Perdata', 'criminal' => 'Pidana', 'hybrid' => 'Campuran',
                    ][$s] ?? $s),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($s) => [
                        'new'=>'Baru','triaged'=>'Triage','mediation'=>'Mediasi',
                        'settled'=>'Selesai','escalated'=>'Eskalasi','closed'=>'Tutup',
                    ][$s] ?? $s),
                TextColumn::make('occurred_at')->label('Waktu Kejadian')->dateTime('d/m/Y H:i'),
                TextColumn::make('damage_estimate')->label('Kerugian')->money('IDR', locale: 'id_ID')->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('legal_route')->label('Jalur Hukum')->options([
                    'civil'=>'Perdata','criminal'=>'Pidana','hybrid'=>'Campuran',
                ]),
                SelectFilter::make('status')->label('Status')->options([
                    'new'=>'Baru','triaged'=>'Triage','mediation'=>'Mediasi',
                    'settled'=>'Selesai','escalated'=>'Eskalasi','closed'=>'Tutup',
                ]),
                Filter::make('occurred_at')->label('Rentang Tanggal Kejadian')
                    ->form([
                        Forms\Components\DatePicker::make('from')->label('Dari tanggal'),
                        Forms\Components\DatePicker::make('until')->label('Sampai tanggal'),
                    ])->query(function (Builder $query, array $data) {
                        $query
                            ->when($data['from'] ?? null, fn ($q, $v) => $q->whereDate('occurred_at', '>=', $v))
                            ->when($data['until'] ?? null, fn ($q, $v) => $q->whereDate('occurred_at', '<=', $v));
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('triage')->label('Triage')
                    ->requiresConfirmation()
                    ->visible(fn($r)=>$r->status==='new')
                    ->action(fn(Dispute $r)=>$r->update(['status'=>'triaged'])),

                Tables\Actions\Action::make('mediate')->label('Ajukan Mediasi')
                    ->visible(fn($r)=>$r->status==='triaged')
                    ->action(fn(Dispute $r)=>$r->update(['status'=>'mediation'])),

                Tables\Actions\Action::make('settle')->label('Tutup Damai')->color('success')
                    ->visible(fn($r)=>in_array($r->status,['mediation','triaged']))
                    ->action(fn(Dispute $r)=>$r->update(['status'=>'settled'])),

                Tables\Actions\Action::make('escalate')->label('Eskalasi Pidana')->color('danger')
                    ->visible(fn($r)=>$r->status!=='escalated')
                    ->action(fn(Dispute $r)=>$r->update(['status'=>'escalated'])),

                Tables\Actions\Action::make('surat')->label('Cetak Surat (PDF)')
                    ->icon('heroicon-o-document-text')
                    ->url(fn(Dispute $r)=>route('disputes.surat',$r), true),

                Tables\Actions\EditAction::make()->label('Ubah'),
                Tables\Actions\DeleteAction::make()->label('Hapus'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()->label('Hapus Terpilih'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            DisputeResource\RelationManagers\EvidencesRelationManager::class,
            DisputeResource\RelationManagers\ActionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => DisputeResource\Pages\ListDisputes::route('/'),
            'create' => DisputeResource\Pages\CreateDispute::route('/create'),
            'edit'   => DisputeResource\Pages\EditDispute::route('/{record}/edit'),
        ];
    }
}
