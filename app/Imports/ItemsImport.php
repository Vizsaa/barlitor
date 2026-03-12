<?php

namespace App\Imports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ItemsImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    public function model(array $row)
    {
        return new Item([
            'title' => $row['title'] ?? '',
            'description' => substr($row['description'] ?? '', 0, 64),
            'cost_price' => is_numeric($row['cost_price'] ?? null) ? $row['cost_price'] : 0,
            'sell_price' => is_numeric($row['sell_price'] ?? null) ? $row['sell_price'] : 0,
            'category' => $row['category'] ?? 'Other',
            'type' => in_array($row['type'] ?? '', ['product', 'tool']) ? $row['type'] : 'product',
            'stock_quantity' => (int) ($row['stock_quantity'] ?? 0),
            'supplier_id' => !empty($row['supplier_id']) ? $row['supplier_id'] : null,
            'image_path' => '',
        ]);
    }
}

