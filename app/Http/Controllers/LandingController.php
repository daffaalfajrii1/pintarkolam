<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\FarmerProfile;
use App\Models\FishSpecies;
use App\Models\Pond;
use App\Models\Product;
use App\Services\WebsiteSettingService;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function __construct(private WebsiteSettingService $settings) {}

    private function layoutData(): array
    {
        return [
            'header' => $this->settings->get('site_header'),
            'footer' => $this->settings->get('site_footer'),
            'branding' => $this->settings->get('site_branding'),
        ];
    }

    public function index()
    {
        return view('landing.index', [
            ...$this->layoutData(),
            'hero' => $this->settings->get('landing_hero'),
            'carousel' => $this->settings->get('landing_carousel'),
            'banners' => $this->settings->get('landing_banners'),
            'sections' => $this->settings->get('landing_sections'),
            'stats' => [
                'farmers' => FarmerProfile::where('verification_status', 'approved')->count(),
                'ponds' => Pond::where('status', 'active')->count(),
                'products' => Product::where('is_published', true)->where('moderation_status', 'approved')->count(),
            ],
            'products' => Product::with(['fishSpecies', 'photos', 'farmerProfile'])
                ->where('is_published', true)
                ->where('moderation_status', 'approved')
                ->where('availability', 'available')
                ->latest()
                ->limit(6)
                ->get(),
            'articles' => Article::where('is_published', true)->latest('published_at')->limit(3)->get(),
        ]);
    }

    public function about()
    {
        return view('landing.about', $this->layoutData());
    }

    public function contact()
    {
        return view('landing.contact', $this->layoutData());
    }

    public function catalog(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $speciesId = $request->integer('species') ?: null;
        $sort = $request->query('sort', 'newest');

        $products = Product::with(['fishSpecies', 'photos', 'farmerProfile'])
            ->where('is_published', true)
            ->where('moderation_status', 'approved')
            ->where('availability', 'available')
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($inner) use ($q) {
                    $inner->where('title', 'like', "%{$q}%")
                        ->orWhere('description', 'like', "%{$q}%")
                        ->orWhere('location_label', 'like', "%{$q}%")
                        ->orWhereHas('fishSpecies', fn ($s) => $s->where('name', 'like', "%{$q}%"))
                        ->orWhereHas('farmerProfile', function ($f) use ($q) {
                            $f->where('shop_name', 'like', "%{$q}%")
                                ->orWhere('business_name', 'like', "%{$q}%")
                                ->orWhere('district', 'like', "%{$q}%");
                        });
                });
            })
            ->when($speciesId, fn ($query) => $query->where('fish_species_id', $speciesId))
            ->when($sort === 'price_asc', fn ($query) => $query->orderBy('price'))
            ->when($sort === 'price_desc', fn ($query) => $query->orderByDesc('price'))
            ->when($sort === 'oldest', fn ($query) => $query->oldest())
            ->when(! in_array($sort, ['price_asc', 'price_desc', 'oldest'], true), fn ($query) => $query->latest())
            ->paginate(12)
            ->withQueryString();

        $shops = FarmerProfile::query()
            ->where('storefront_status', 'approved')
            ->where('verification_status', 'approved')
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($inner) use ($q) {
                    $inner->where('shop_name', 'like', "%{$q}%")
                        ->orWhere('business_name', 'like', "%{$q}%")
                        ->orWhere('district', 'like', "%{$q}%")
                        ->orWhere('regency', 'like', "%{$q}%");
                });
            })
            ->latest()
            ->limit(8)
            ->get();

        return view('landing.catalog', [
            ...$this->layoutData(),
            'products' => $products,
            'shops' => $shops,
            'speciesList' => FishSpecies::where('is_active', true)->orderBy('name')->get(),
            'filters' => [
                'q' => $q,
                'species' => $speciesId,
                'sort' => $sort,
            ],
        ]);
    }

    public function product(Product $product)
    {
        abort_unless(
            $product->is_published
            && $product->moderation_status === 'approved',
            404
        );

        $product->load(['fishSpecies', 'photos', 'farmerProfile', 'user']);

        $related = Product::with(['photos', 'fishSpecies'])
            ->where('is_published', true)
            ->where('moderation_status', 'approved')
            ->where('availability', 'available')
            ->where('id', '!=', $product->id)
            ->when($product->farmer_profile_id, fn ($q) => $q->where('farmer_profile_id', $product->farmer_profile_id))
            ->latest()
            ->limit(4)
            ->get();

        return view('landing.product', [
            ...$this->layoutData(),
            'product' => $product,
            'related' => $related,
        ]);
    }

    public function shop(FarmerProfile $farmer)
    {
        abort_unless($farmer->storefront_status === 'approved', 404);

        $products = Product::with(['fishSpecies', 'photos'])
            ->where('farmer_profile_id', $farmer->id)
            ->where('is_published', true)
            ->where('moderation_status', 'approved')
            ->latest()
            ->get();

        return view('landing.shop', [
            ...$this->layoutData(),
            'farmer' => $farmer,
            'products' => $products,
        ]);
    }

    public function blog(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $sort = $request->query('sort', 'newest');
        $month = $request->query('month'); // YYYY-MM

        $articles = Article::query()
            ->where('is_published', true)
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($inner) use ($q) {
                    $inner->where('title', 'like', "%{$q}%")
                        ->orWhere('excerpt', 'like', "%{$q}%")
                        ->orWhere('body', 'like', "%{$q}%");
                });
            })
            ->when($month && preg_match('/^\d{4}-\d{2}$/', $month), function ($query) use ($month) {
                [$y, $m] = explode('-', $month);
                $query->whereYear('published_at', (int) $y)
                    ->whereMonth('published_at', (int) $m);
            })
            ->when($sort === 'oldest', fn ($query) => $query->oldest('published_at'))
            ->when($sort !== 'oldest', fn ($query) => $query->latest('published_at'))
            ->paginate(9)
            ->withQueryString();

        $months = Article::query()
            ->where('is_published', true)
            ->whereNotNull('published_at')
            ->selectRaw("DATE_FORMAT(published_at, '%Y-%m') as ym")
            ->selectRaw('COUNT(*) as total')
            ->groupBy('ym')
            ->orderByDesc('ym')
            ->limit(12)
            ->get();

        $recent = Article::where('is_published', true)
            ->latest('published_at')
            ->limit(5)
            ->get(['id', 'title', 'slug', 'published_at', 'cover_path']);

        return view('landing.blog', [
            ...$this->layoutData(),
            'articles' => $articles,
            'months' => $months,
            'recent' => $recent,
            'filters' => [
                'q' => $q,
                'sort' => $sort,
                'month' => $month,
            ],
        ]);
    }

    public function article(Article $article)
    {
        abort_unless($article->is_published, 404);

        $recent = Article::where('is_published', true)
            ->where('id', '!=', $article->id)
            ->latest('published_at')
            ->limit(5)
            ->get(['id', 'title', 'slug', 'published_at', 'cover_path']);

        return view('landing.article', [
            ...$this->layoutData(),
            'article' => $article,
            'recent' => $recent,
        ]);
    }
}
