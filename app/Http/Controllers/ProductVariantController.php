<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductVariantRequest;
use App\Http\Requests\UpdateProductVariantRequest;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    public function index(Request $request, Product $product)
    {
        $variants = ProductVariant::where('product_id', $product->id)
            ->latest()
            ->paginate(15);

        return view('product_variants.index', compact('product', 'variants'));
    }

    public function create(Product $product)
    {
        return view('product_variants.create', compact('product'));
    }

    public function store(StoreProductVariantRequest $request, Product $product)
    {
        $variant = ProductVariant::create(array_merge(
            $request->validated(),
            ['product_id' => $product->id]
        ));

        return redirect()
            ->route('products.variants.index', $product)
            ->with('success', 'Product variant created successfully.');
    }

    public function edit(Product $product, ProductVariant $product_variant)
    {
        if ($product_variant->product_id !== $product->id) {
            abort(404);
        }

        return view('product_variants.edit', compact('product', 'product_variant'));
    }

    public function update(UpdateProductVariantRequest $request, Product $product, ProductVariant $product_variant)
    {
        if ($product_variant->product_id !== $product->id) {
            abort(404);
        }

        $product_variant->update($request->validated());

        return redirect()
            ->route('products.variants.index', $product)
            ->with('success', 'Product variant updated successfully.');
    }

    public function destroy(Product $product, ProductVariant $product_variant)
    {
        if ($product_variant->product_id !== $product->id) {
            abort(404);
        }

        $product_variant->delete();

        return back()->with('success', 'Product variant deleted successfully.');
    }
}
