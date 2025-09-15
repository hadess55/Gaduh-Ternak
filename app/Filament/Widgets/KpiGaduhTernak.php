<?php

namespace App\Filament\Widgets;

use App\Models\Dispute;
use Carbon\CarbonPeriod;
use Filament\Widgets\ChartWidget;

class KpiGaduhTernak extends ChartWidget
{
    protected static ?string $heading = 'Tren Kasus (8 Minggu)';

    protected function getData(): array
    {
        $start = now()->startOfWeek()->subWeeks(7);
        $end   = now()->endOfWeek();

        $period = CarbonPeriod::create($start, '1 week', $end);

        $labels  = [];
        $total   = [];
        $settled = [];

        foreach ($period as $weekStart) {
            $from = $weekStart->copy();
            $to   = $weekStart->copy()->endOfWeek();

            $labels[]  = $from->format('d/m');
            $total[]   = Dispute::whereBetween('occurred_at', [$from, $to])->count();
            $settled[] = Dispute::where('status', 'settled')
                                ->whereBetween('updated_at', [$from, $to])->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Kasus',
                    'data'  => $total,
                    'tension' => 0.3,
                ],
                [
                    'label' => 'Selesai',
                    'data'  => $settled,
                    'tension' => 0.3,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
