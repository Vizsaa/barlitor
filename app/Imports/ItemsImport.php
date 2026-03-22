<?php

namespace App\Imports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ItemsImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * Map each row from the spreadsheet to an Item model.
     *
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Item([
            'title' => $row['title'],
            'description' => substr($row['description'] ?? '', 0, 500),
            'cost_price' => $row['cost_price'] ?? 0,
            'sell_price' => $row['sell_price'] ?? 0,
            'category' => $row['category'] ?? 'Other',
            'type' => in_array($row['type'] ?? '', ['product', 'tool']) ? $row['type'] : 'product',
            'stock_quantity' => (int)($row['stock_quantity'] ?? 0),
            'supplier_id' => !empty($row['supplier_id']) ? $row['supplier_id'] : null,
            'image_path' => '',
        ]);
    }

    /**
     * Validation rules for each row.
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'cost_price' => 'nullable|numeric|min:0',
            'sell_price' => 'nullable|numeric|min:0',
            'category' => 'nullable|string',
            'type' => 'nullable|in:product,tool',
            'stock_quantity' => 'nullable|integer|min:0',
            'supplier_id' => 'nullable|integer',
        ];
    }
}
