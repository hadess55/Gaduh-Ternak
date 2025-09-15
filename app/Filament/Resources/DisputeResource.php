<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DisputeResource\Pages;
use App\Filament\Resources\DisputeResource\RelationManagers\ActionsRelationManager;
use App\Filament\Resources\DisputeResource\RelationManagers\EvidencesRelationManager;
use App\Filament\Resources\DisputeResource\RelationManagers\HearingsRelationManager;
use App\Filament\Resources\DisputeResource\RelationManagers\SusResponsesRelationManager;
use App\Models\Dispute;
use Filament\Forms;
use Filament\Forms\Components\{Section, Select, TextInput, DateTimePicker, Textarea, Radio};
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\{Filter, SelectFilter};
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class DisputeResource extends Resource
{
    protected static ?string $model = Dispute::class;
    protected static ?string $navigationIcon = 'heroicon-o-scale';
    protected static ?string $navigationGroup = 'Sengketa';
    protected static ?string $modelLabel = 'Sengketa';
    protected static ?string $pluralModelLabel = 'Sengketa';

    protected static bool $shouldRegisterNavigation = false;

// Kunci semua kemampuan → siapa pun akan 404
public static function canViewAny(): bool     { return false; }
public static function canView(Model $r): bool { return false; }
public static function canCreate(): bool       { return false; }
public static function canEdit(Model $r): bool { return false; }
public static function canDelete(Model $r): bool{ return false; }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Section::make('Data Kasus')->schema([
                Select::make('reporter_id')
                    ->label('Pelapor')
                    ->relationship('reporter', 'name')
                    ->placeholder('Pilih pelapor')
                    ->searchable()
                    ->required(),

                Select::make('defendant_id')
                    ->label('Terlapor')
                    ->relationship('defendant', 'name')
                    ->placeholder('Pilih terlapor')
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
                    ->relationship('legalBasis', 'title')
                    ->placeholder('Pilih rujukan pasal')
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
                    ->formatStateUsing(fn (string $state) => [
                        'civil' => 'Perdata',
                        'criminal' => 'Pidana',
                        'hybrid' => 'Campuran',
                    ][$state] ?? $state),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => [
                        'new'=>'Baru','triaged'=>'Triage','mediation'=>'Mediasi',
                        'settled'=>'Selesai','escalated'=>'Eskalasi','closed'=>'Tutup',
                    ][$state] ?? $state)
                    ->color(fn (string $state) => match ($state) {
                        'new'       => 'info',
                        'triaged'   => 'warning',
                        'mediation' => 'warning',
                        'settled'   => 'success',
                        'escalated' => 'danger',
                        'closed'    => 'gray',
                        default     => 'gray',
                    }),


                TextColumn::make('occurred_at')->label('Waktu Kejadian')->dateTime('d/m/Y H:i'),
                TextColumn::make('damage_estimate')->label('Kerugian')->money('IDR', locale: 'id_ID')
                    ->toggleable(isToggledHiddenByDefault: true),
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
                    ])
                    ->query(function (Builder $query, array $data): void {
                        $query
                            ->when($data['from'] ?? null, fn (Builder $q, $v) => $q->whereDate('occurred_at', '>=', $v))
                            ->when($data['until'] ?? null, fn (Builder $q, $v) => $q->whereDate('occurred_at', '<=', $v));
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('triage')->label('Triage')
                    ->visible(fn ($record) => $record->status === 'new')
                    ->requiresConfirmation()
                    ->action(fn (Dispute $record) => $record->update(['status'=>'triaged'])),

                Tables\Actions\Action::make('mediate')->label('Ajukan Mediasi')
                    ->visible(fn ($record) => $record->status === 'triaged')
                    ->action(fn (Dispute $record) => $record->update(['status'=>'mediation'])),

                Tables\Actions\Action::make('settle')->label('Tutup Damai')->color('success')
                    ->visible(fn ($record) => in_array($record->status, ['mediation','triaged'], true))
                    ->action(fn (Dispute $record) => $record->update(['status'=>'settled','closed_at'=>now()])),

                Tables\Actions\Action::make('escalate')->label('Eskalasi Pidana')->color('danger')
                    ->visible(fn ($record) => $record->status !== 'escalated')
                    ->requiresConfirmation()
                    ->action(fn (Dispute $record) => $record->update(['status'=>'escalated'])),

                Tables\Actions\Action::make('surat')->label('Cetak Surat (PDF)')
                    ->icon('heroicon-o-document-text')
                    ->url(fn (Dispute $record) => route('disputes.surat', $record), true),

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
            EvidencesRelationManager::class,
            ActionsRelationManager::class,
            HearingsRelationManager::class,
            SusResponsesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListDisputes::route('/'),
            'create' => Pages\CreateDispute::route('/create'),
            'edit'   => Pages\EditDispute::route('/{record}/edit'),
        ];
    }
}
