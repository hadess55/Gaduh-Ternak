<?php

namespace App\Filament\Widgets;

use App\Models\Farmer;
use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    // JANGAN static
    protected ?string $heading = 'Ringkasan';

    protected int|string|array $columnSpan = 'full';
    protected static ?int $sort = 10;

    // ini memang static (sesuai base class)
    protected static bool $isLazy = false;

    protected function getStats(): array
    {
        $total     = Farmer::count();
        $pending   = Farmer::where('status', 'pending')->count();
        $validated = Farmer::where('status', 'validated')->count();
        $users     = User::count();

        $startThisWeek = Carbon::now()->startOfWeek();
        $startLastWeek = Carbon::now()->subWeek()->startOfWeek();
        $endLastWeek   = Carbon::now()->subWeek()->endOfWeek();

        $thisWeek = Farmer::where('created_at', '>=', $startThisWeek)->count();
        $lastWeek = Farmer::whereBetween('created_at', [$startLastWeek, $endLastWeek])->count();
        $delta    = $thisWeek - $lastWeek;
        $deltaStr = ($delta >= 0 ? '+' : '') . $delta . ' vs minggu lalu';

        return [
            Stat::make('Total Peternak', (string) $total)
                ->icon('heroicon-o-user-group')
                ->description($deltaStr)
                ->descriptionIcon($delta >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($delta >= 0 ? 'success' : 'danger'),

            Stat::make('Menunggu Validasi', (string) $pending)
                ->icon('heroicon-o-clock')
                ->color('warning'),

            Stat::make('Tervalidasi', (string) $validated)
                ->icon('heroicon-o-check-badge')
                ->color('success'),

            Stat::make('Pengguna', (string) $users)
                ->icon('heroicon-o-users')
                ->color('info'),
        ];
    }
}
