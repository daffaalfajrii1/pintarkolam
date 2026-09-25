<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\FishSpecies;
use App\Models\Product;
use App\Models\ProductPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with(['fishSpecies', 'photos'])
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(15);

        $shop = $request->user()->farmerProfile;

        return view('user.products.index', compact('products', 'shop'));
    }

    public function create(Request $request)
    {
        $shop = $request->user()->farmerProfile;
        if (! $shop || ! $shop->canSell()) {
            return redirect()->route('user.shop.edit')
                ->with('status', 'Lengkapi dan tunggu persetujuan profil toko sebelum menjual di katalog.');
        }

        return view('user.products.create', [
            'species' => FishSpecies::where('is_active', true)->orderBy('name')->get(),
            'shop' => $shop,
        ]);
    }

    public function store(Request $request)
    {
        $shop = $request->user()->farmerProfile;
        if (! $shop || ! $shop->canSell()) {
            return redirect()->route('user.shop.edit')
                ->with('status', 'Profil toko belum disetujui. Belum bisa menjual.');
        }

        $data = $this->validatedProduct($request);

        $product = Product::create([
            ...collect($data)->except('photos')->all(),
            'user_id' => $request->user()->id,
            'farmer_profile_id' => $shop->id,
            'whatsapp' => $data['whatsapp'] ?? $shop->whatsapp ?? $request->user()->phone,
            'availability' => 'available',
            'moderation_status' => 'pending',
            'is_published' => false,
        ]);

        $this->storePhotos($request, $product);

        return redirect()->route('user.products.index')->with('status', 'Produk dikirim. Menunggu persetujuan admin sebelum tayang.');
    }

    public function edit(Request $request, Product $product)
    {
        $this->authorizeProduct($request, $product);

        return view('user.products.edit', [
            'product' => $product->load('photos'),
            'species' => FishSpecies::where('is_active', true)->orderBy('name')->get(),
            'shop' => $request->user()->farmerProfile,
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $this->authorizeProduct($request, $product);

        $data = $this->validatedProduct($request, updating: true);

        $product->update([
            ...collect($data)->except(['photos', 'availability'])->all(),
            'whatsapp' => $data['whatsapp'] ?? $product->whatsapp,
            'availability' => $data['availability'] ?? $product->availability,
        ]);

        $this->storePhotos($request, $product);

        return redirect()->route('user.products.index')->with('status', 'Produk diperbarui.');
    }

    public function destroy(Request $request, Product $product)
    {
        $this->authorizeProduct($request, $product);

        foreach ($product->photos as $photo) {
            Storage::disk('public')->delete($photo->path);
            $photo->delete();
        }

        $product->delete();

        return redirect()->route('user.products.index')->with('status', 'Produk dihapus.');
    }

    public function toggleAvailability(Request $request, Product $product)
    {
        $this->authorizeProduct($request, $product);

        $product->update([
            'availability' => $product->availability === 'sold_out' ? 'available' : 'sold_out',
        ]);

        $label = $product->availability === 'sold_out' ? 'Stok ditandai habis / terjual.' : 'Stok kembali tersedia.';

        return back()->with('status', $label);
    }

    public function destroyPhoto(Request $request, Product $product, ProductPhoto $photo)
    {
        $this->authorizeProduct($request, $product);
        abort_unless($photo->product_id === $product->id, 404);

        Storage::disk('public')->delete($photo->path);
        $photo->delete();

        if (! $product->photos()->where('is_primary', true)->exists()) {
            $first = $product->photos()->first();
            if ($first) {
                $first->update(['is_primary' => true]);
            }
        }

        return back()->with('status', 'Foto dihapus.');
    }

    private function authorizeProduct(Request $request, Product $product): void
    {
        abort_unless($product->user_id === $request->user()->id || $request->user()->hasRole('admin'), 403);
    }

    private function validatedProduct(Request $request, bool $updating = false): array
    {
        return $request->validate([
            'fish_species_id' => ['required', 'exists:fish_species,id'],
            'title' => ['required', 'string', 'max:255'],
            'size_label' => ['nullable', 'string', 'max:100'],
            'stock_kg' => ['nullable', 'numeric', 'min:0'],
            'price_unit' => ['required', 'in:kg,pcs'],
            'price' => ['required', 'numeric', 'min:0'],
            'min_order' => ['nullable', 'numeric', 'min:0'],
            'location_label' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'whatsapp' => ['nullable', 'string', 'max:30'],
            'availability' => [$updating ? 'required' : 'nullable', 'in:available,sold_out'],
            'photos' => ['nullable', 'array', 'max:8'],
            'photos.*' => ['image', 'max:4096'],
        ]);
    }

    private function storePhotos(Request $request, Product $product): void
    {
        if (! $request->hasFile('photos')) {
            return;
        }

        $existing = $product->photos()->count();
        $hasPrimary = $product->photos()->where('is_primary', true)->exists();

        foreach ($request->file('photos') as $i => $photo) {
            if ($existing + $i >= 8) {
                break;
            }

            ProductPhoto::create([
                'product_id' => $product->id,
                'path' => $photo->store('uploads/products', 'public'),
                'is_primary' => ! $hasPrimary && $i === 0,
            ]);
        }
    }
}
