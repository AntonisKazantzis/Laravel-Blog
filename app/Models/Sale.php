<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use \Sushi\Sushi;

    public function getRows()
    {
        $salesDataFilePath = storage_path('app/salesData/sales_data_table.json');
        $statisticsDataFilePath = storage_path('app/salesData/sales_data_statistics.json');

        $salesData = json_decode(file_get_contents($salesDataFilePath), true);
        $statisticsData = json_decode(file_get_contents($statisticsDataFilePath), true);

        if (isset($salesData['salesData']) && is_array($salesData['salesData'])) {
            $rows = $salesData['salesData'];

            foreach ($rows as &$row) {
                $productId = $row['productId'];
                $statistics = collect($statisticsData)->firstWhere('productId', $productId);

                $row['sales'] = $statistics['sales'] ?? 0;
                $row['revenue'] = $statistics['revenue'] ?? 0;
                $row['lastSaleDate'] = $statistics['lastSaleDate'] ?? null;
            }

            return $rows;
        }

        return [];
    }
}
