<?php

namespace App\Http\Controllers\Anthaleja\MegaWareHouse;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Anthaleja\Marketplace\Product;
use App\Models\Anthaleja\MegaWareHouse\ProductCategory;

class ProductController extends Controller
{
    public function assignProductCategory($productId)
    {
        $product = Product::findOrFail($productId);

        // Trova la categoria più adatta
        $category = ProductCategory::where('macro_category', 'Alimentari')
            ->where('category', 'Freschi')
            ->first();

        $product->category_id = $category->id;
        $product->save();
    }
}
