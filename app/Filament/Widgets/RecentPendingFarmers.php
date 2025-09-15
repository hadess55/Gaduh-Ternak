<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\FarmerResource;
use App\Models\Farmer;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;

class RecentPendingFarmers extends BaseWidget
{
    protected static ?string $heading = 'Menunggu Validasi (Terbaru)';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 20;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Farmer::query()
                    ->where('status', 'pending')
                    ->latest()
            )
            ->paginated(false)
            ->columns([
                Tables\Columns\TextColumn::make('nama')->label('Nama')->searchable(),
                Tables\Columns\TextColumn::make('desa')->label('Desa'),
                Tables\Columns\TextColumn::make('kecamatan')->label('Kecamatan')->toggleable(),
                Tables\Columns\TextColumn::make('created_at')->label('Diajukan')->since(),
            ])
            ->actions([
                Tables\Actions\Action::make('buka')
                    ->label('Buka')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn (Farmer $record) =>
                        Auth::user()?->hasRole('admin')
                            ? FarmerResource::getUrl('edit', ['record' => $record])
                            : FarmerResource::getUrl('index'),
                        true
                    )
                    ->color('gray'),
            ]);
    }
}
