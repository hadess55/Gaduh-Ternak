<?php

namespace App\Filament\Widgets;

use App\Models\Farmer;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class FarmersTrendChart extends ChartWidget
{
    // ✅ untuk ChartWidget wajib static
    protected static ?string $heading = 'Tren Peternak (8 Minggu)';
     protected int|string|array $columnSpan = ['sm' => 1, 'xl' => 2];
    protected static ?int $sort = 30;
    protected static string $color = 'primary';

    protected function getData(): array
    {
        $labels = [];
        $created = [];
        $validated = [];

        for ($i = 7; $i >= 0; $i--) {
            $start = now()->subWeeks($i)->startOfWeek();
            $end   = now()->subWeeks($i)->endOfWeek();

            $labels[]   = $start->format('d/m');
            $created[]  = Farmer::whereBetween('created_at', [$start, $end])->count();
            $validated[] = Farmer::where('status', 'validated')
                ->whereBetween('validated_at', [$start, $end])->count();
        }

        return [
            'datasets' => [
                ['label' => 'Dibuat',      'data' => $created,   'tension' => 0.3],
                ['label' => 'Tervalidasi', 'data' => $validated, 'tension' => 0.3],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
