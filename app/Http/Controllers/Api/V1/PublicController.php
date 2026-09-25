<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\FarmerProfileResource;
use App\Http\Resources\FishSpeciesResource;
use App\Http\Resources\PondResource;
use App\Http\Resources\ProductResource;
use App\Http\Responses\ApiResponse;
use App\Models\Article;
use App\Models\FarmerProfile;
use App\Models\FishSpecies;
use App\Models\Pond;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function home()
    {
        $settings = Setting::query()->where('group', 'landing')->pluck('value', 'key');

        return ApiResponse::success([
            'hero' => $settings['landing_hero'] ?? [
                'title' => 'PintarKolam',
                'tagline' => 'Pantau Air, Atur Pakan, Panen Lebih Tepat',
                'subtitle' => 'Sistem informasi budidaya ikan air tawar Rejang Lebong',
            ],
            'stats' => [
                'farmers' => FarmerProfile::where('verification_status', 'approved')->count(),
                'ponds' => Pond::where('status', 'active')->count(),
                'products' => Product::where('is_published', true)->where('moderation_status', 'approved')->count(),
                'articles' => Article::where('is_published', true)->count(),
            ],
            'featured_products' => ProductResource::collection(
                Product::with(['fishSpecies', 'photos', 'farmerProfile'])
                    ->where('is_published', true)
                    ->where('moderation_status', 'approved')
                    ->where('availability', 'available')
                    ->latest()
                    ->limit(6)
                    ->get()
            ),
            'articles' => ArticleResource::collection(
                Article::where('is_published', true)->latest('published_at')->limit(3)->get()
            ),
        ], 'Beranda PintarKolam');
    }

    public function species()
    {
        return ApiResponse::success(
            FishSpeciesResource::collection(FishSpecies::where('is_active', true)->orderBy('name')->get()),
            'Daftar jenis ikan'
        );
    }

    public function products(Request $request)
    {
        $query = Product::with(['fishSpecies', 'photos', 'farmerProfile'])
            ->where('is_published', true)
            ->where('moderation_status', 'approved');

        if ($request->filled('species_id')) {
            $query->where('fish_species_id', $request->integer('species_id'));
        }
        if ($request->filled('availability')) {
            $query->where('availability', $request->string('availability'));
        }
        if ($request->filled('q')) {
            $q = $request->string('q');
            $query->where(function ($builder) use ($q) {
                $builder->where('title', 'like', "%{$q}%")
                    ->orWhere('location_label', 'like', "%{$q}%");
            });
        }

        return ApiResponse::success(
            ProductResource::collection($query->latest()->paginate(15)),
            'Katalog ikan'
        );
    }

    public function product(Product $product)
    {
        $this->authorize('view', $product);
        $product->load(['fishSpecies', 'photos', 'farmerProfile.user']);

        return ApiResponse::success(new ProductResource($product), 'Detail produk');
    }

    public function farmers(Request $request)
    {
        $query = FarmerProfile::with('user')
            ->where('verification_status', 'approved')
            ->whereIn('storefront_status', ['approved', 'pending', 'inactive']);

        if ($request->filled('district')) {
            $query->where('district', $request->string('district'));
        }

        return ApiResponse::success(
            FarmerProfileResource::collection($query->paginate(15)),
            'Daftar pembudidaya'
        );
    }

    public function mapPonds(Request $request)
    {
        $query = Pond::with(['user.farmerProfile', 'latestHealthScore', 'cycles.fishSpecies'])
            ->where('status', 'active')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude');

        if ($request->boolean('ready_to_sell')) {
            $query->whereHas('user.products', function ($q) {
                $q->where('is_published', true)
                    ->where('moderation_status', 'approved')
                    ->where('availability', 'available');
            });
        }

        if ($request->filled('species_id')) {
            $query->whereHas('cycles', function ($q) use ($request) {
                $q->where('fish_species_id', $request->integer('species_id'))
                    ->whereIn('status', ['active', 'near_harvest']);
            });
        }

        return ApiResponse::success(
            PondResource::collection($query->limit(200)->get()),
            'Peta lokasi kolam'
        );
    }

    public function blog()
    {
        return ApiResponse::success(
            ArticleResource::collection(
                Article::where('is_published', true)->latest('published_at')->paginate(10)
            ),
            'Artikel edukasi'
        );
    }

    public function blogShow(Article $post)
    {
        abort_unless($post->is_published, 404);

        return ApiResponse::success(new ArticleResource($post), 'Detail artikel');
    }
}
