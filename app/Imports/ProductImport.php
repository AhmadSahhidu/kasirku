<?php

namespace App\Imports;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use App\Models\SubProduct;
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
        if (!$supplier || $row['supplier'] != '' || $row['supplier'] != null) {
            Supplier::create([
                'name' => $row['supplier'],
                'store_id' => $store->id,
                'phone' => '',
                'sales_name' => '',
                'sales_phone' => '',
                'sales_address' => '',
                'address' => ''
            ]);
        }
        if (!$brand || $row['brand'] != '' || $row['brand'] != null) {
            Brand::create([
                'name' => $row['brand'],
                'store_id' => $store->id
            ]);
        }
        if (!$category || $row['category'] != '' || $row['category'] != null) {
            Brand::create([
                'name' => $row['category'],
                'store_id' => $store->id
            ]);
        }

        $product =  Product::create([
            'id' => generateUuid(),
            'number' => $generateNumber,
            'name' => $row['name'],
            'brand_id' => $brand->id ?? null,
            'category_id' => $category->id ?? null,
            'supplier_id' => $supplier->id ?? null,
            'size' => $row['size'],
            'seri' => $row['seri'],
            'satuan' => $row['satuan'],
            'store_id' => $store->id ?? null,
            'user_id' => $user
        ]);

        return new SubProduct([
            'product_id' => $product->id,
            'stock' => $row['stock'],
            'purchase_price' => $row['purchase_price'],
            'selling_price' => $row['selling_price'],
        ]);
    }
}
