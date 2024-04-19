<?php

namespace App\Imports;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $generateNumber = 'CPD' . date('YmdHis');
        $user = auth()->user()->id;
        $supplier = Supplier::where('name', $row['supplier'])->first();
        $brand = Brand::where('name', $row['brand'])->first();
        $category = Category::where('name', $row['category'])->first();
        $store = Store::where('name', $row['store'])->first();
        return new Product([
            'number' => $generateNumber,
            'name' => $row['name'],
            'brand_id' => $brand->id ?? null,
            'category_id' => $category->id ?? null,
            'supplier_id' => $supplier->id ?? null,
            'size' => $row['size'],
            'seri' => $row['seri'],
            'satuan' => $row['satuan'],
            'stock' => $row['stock'],
            'stock_minimum' => $row['stock_minimum'],
            'purchase_price' => $row['purchase_price'],
            'selling_price' => $row['selling_price'],
            'store_id' => $store->id ?? null,
            'user_id' => $user
        ]);
    }
}
