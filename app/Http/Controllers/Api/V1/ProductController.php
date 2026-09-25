<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Responses\ApiResponse;
use App\Models\Product;
use App\Models\ProductPhoto;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function store(Request $request)
    {
        $this->authorize('create', Product::class);

        $data = $request->validate([
            'fish_species_id' => ['required', 'exists:fish_species,id'],
            'cultivation_cycle_id' => ['nullable', 'exists:cultivation_cycles,id'],
            'title' => ['required', 'string', 'max:255'],
            'size_label' => ['nullable', 'string', 'max:100'],
            'stock_kg' => ['nullable', 'numeric', 'min:0'],
            'stock_pcs' => ['nullable', 'integer', 'min:0'],
            'price_unit' => ['required', 'in:kg,pcs'],
            'price' => ['required', 'numeric', 'min:0'],
            'min_order' => ['nullable', 'numeric', 'min:0'],
            'location_label' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'whatsapp' => ['nullable', 'string', 'max:30'],
            'availability' => ['sometimes', 'in:available,sold_out'],
            'photo' => ['nullable', 'image', 'max:5120'],
        ]);

        $user = $request->user();
        $product = Product::create([
            ...$data,
            'user_id' => $user->id,
            'farmer_profile_id' => $user->farmerProfile?->id,
            'whatsapp' => $data['whatsapp'] ?? $user->farmerProfile?->whatsapp ?? $user->phone,
            'moderation_status' => 'pending',
            'is_published' => true,
            'availability' => $data['availability'] ?? 'available',
        ]);

        if ($request->hasFile('photo')) {
            ProductPhoto::create([
                'product_id' => $product->id,
                'path' => $request->file('photo')->store('products', 'public'),
                'is_primary' => true,
            ]);
        }

        return ApiResponse::success(
            new ProductResource($product->load(['fishSpecies', 'photos', 'farmerProfile'])),
            'Produk dikirim untuk moderasi',
            201
        );
    }

    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $data = $request->validate([
            'title' => ['sometimes', 'string', 'max:255'],
            'size_label' => ['nullable', 'string', 'max:100'],
            'stock_kg' => ['nullable', 'numeric', 'min:0'],
            'stock_pcs' => ['nullable', 'integer', 'min:0'],
            'price_unit' => ['sometimes', 'in:kg,pcs'],
            'price' => ['sometimes', 'numeric', 'min:0'],
            'min_order' => ['nullable', 'numeric', 'min:0'],
            'location_label' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'whatsapp' => ['nullable', 'string', 'max:30'],
            'availability' => ['sometimes', 'in:available,sold_out'],
            'photo' => ['nullable', 'image', 'max:5120'],
        ]);

        $product->update([
            ...$data,
            'moderation_status' => 'pending',
        ]);

        if ($request->hasFile('photo')) {
            ProductPhoto::create([
                'product_id' => $product->id,
                'path' => $request->file('photo')->store('products', 'public'),
                'is_primary' => true,
            ]);
        }

        return ApiResponse::success(
            new ProductResource($product->fresh()->load(['fishSpecies', 'photos', 'farmerProfile'])),
            'Produk diperbarui'
        );
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);
        $product->delete();

        return ApiResponse::success(null, 'Produk dihapus');
    }
}
