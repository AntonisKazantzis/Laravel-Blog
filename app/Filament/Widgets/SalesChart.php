<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class SalesChart extends ChartWidget
{
    protected static bool $isDiscovered = false;

    protected static ?string $heading = 'Chart';

    protected int|string|array $columnSpan = 'full';

    protected static ?string $maxHeight = '400px';

    public ?string $filter = 'daily';

    protected function getData(): array
    {
        $activeFilter = $this->filter;

        // Load the JSON data for sales
        $salesDataMonthlyPath = storage_path('app/salesData/sales_data_monthly.json');
        $salesDataDailyPath = storage_path('app/salesData/sales_data_daily.json');

        // Decode the JSON data into associative arrays
        $salesDataMonthly = json_decode(file_get_contents($salesDataMonthlyPath), true);
        $salesDataDaily = json_decode(file_get_contents($salesDataDailyPath), true);

        // Initialize datasets and labels
        $datasets = [];
        $labels = [];

        if ($activeFilter === 'monthly') {
            // Check if monthly sales data is available and valid
            if (isset($salesDataMonthly['datasets']) && is_array($salesDataMonthly['datasets'])) {
                $datasets = $salesDataMonthly['datasets'];
                $labels = $salesDataMonthly['labels'] ?? $labels;
            }
        } elseif ($activeFilter === 'daily') {
            // Check if daily sales data is available and valid
            if (isset($salesDataDaily['datasets']) && is_array($salesDataDaily['datasets'])) {
                $datasets = $salesDataDaily['datasets'];
                $labels = $salesDataDaily['labels'] ?? $labels;
            }
        }

        return [
            'datasets' => $datasets,
            'labels' => $labels,
        ];
    }

    protected function getFilters(): ?array
    {
        return [
            'daily' => 'Daily',
            'monthly' => 'Monthly',
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
