<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;

class ProductsExport implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;

    public function query()
    {
        return Product::with(['category', 'brand']);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama',
            'SKU',
            'Kategori',
            'Merek',
            'Harga',
            'Stok',
            'Status',
        ];
    }

    public function map($product): array
    {
        return [
            $product->id,
            $product->name,
            $product->sku,
            $product->category->name ?? '-',
            $product->brand->name ?? '-',
            $product->price,
            $product->stock,
            $product->is_active ? 'Aktif' : 'Nonaktif',
        ];
    }
}
