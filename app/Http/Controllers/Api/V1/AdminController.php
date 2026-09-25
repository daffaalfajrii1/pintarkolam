<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Models\CultivationCycle;
use App\Models\DeviceToken;
use App\Models\FarmerProfile;
use App\Models\NotificationPreference;
use App\Models\Pond;
use App\Models\PondHealthScore;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        return ApiResponse::success([
            'total_users' => User::count(),
            'verified_farmers' => FarmerProfile::where('verification_status', 'approved')->count(),
            'active_ponds' => Pond::where('status', 'active')->count(),
            'active_cycles' => CultivationCycle::whereIn('status', ['active', 'near_harvest'])->count(),
            'critical_ponds' => PondHealthScore::query()
                ->where('category', 'kritis')
                ->where('calculated_at', '>=', now()->subDays(7))
                ->distinct('pond_id')
                ->count('pond_id'),
            'near_harvest_cycles' => CultivationCycle::where('status', 'near_harvest')->count(),
            'available_products' => Product::where('availability', 'available')
                ->where('moderation_status', 'approved')
                ->where('is_published', true)
                ->count(),
            'farmers_by_district' => FarmerProfile::query()
                ->selectRaw('district, count(*) as total')
                ->whereNotNull('district')
                ->groupBy('district')
                ->pluck('total', 'district'),
        ], 'Ringkasan dashboard admin');
    }

    public function users(Request $request)
    {
        $users = User::with('roles', 'farmerProfile')->latest()->paginate(20);

        return ApiResponse::success($users, 'Daftar pengguna');
    }

    public function pendingFarmers()
    {
        $farmers = FarmerProfile::with('user')
            ->where(function ($q) {
                $q->where('verification_status', 'pending')
                    ->orWhere('storefront_status', 'pending');
            })
            ->latest()
            ->paginate(20);

        return ApiResponse::success($farmers, 'Pembudidaya menunggu verifikasi');
    }

    public function verifyFarmer(Request $request, FarmerProfile $farmer)
    {
        $data = $request->validate([
            'verification_status' => ['sometimes', 'in:approved,rejected'],
            'storefront_status' => ['sometimes', 'in:approved,rejected,suspended,inactive'],
            'rejection_reason' => ['nullable', 'string'],
        ]);

        if (isset($data['verification_status']) && $data['verification_status'] === 'approved') {
            $data['verified_at'] = now();
            $data['verified_by'] = $request->user()->id;
        }

        $farmer->update($data);

        return ApiResponse::success($farmer->fresh('user'), 'Status pembudidaya diperbarui');
    }

    public function moderateProduct(Request $request, Product $product)
    {
        $data = $request->validate([
            'moderation_status' => ['required', 'in:approved,rejected'],
        ]);

        $product->update([
            'moderation_status' => $data['moderation_status'],
            'is_published' => $data['moderation_status'] === 'approved',
        ]);

        return ApiResponse::success($product->fresh(), 'Moderasi produk selesai');
    }

    public function reports()
    {
        return ApiResponse::success([
            'cycles_by_status' => CultivationCycle::selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status'),
            'products_by_availability' => Product::selectRaw('availability, count(*) as total')->groupBy('availability')->pluck('total', 'availability'),
        ], 'Laporan ringkas');
    }
}
