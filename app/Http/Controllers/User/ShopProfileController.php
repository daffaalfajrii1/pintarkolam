<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\FarmerProfile;
use App\Notifications\UserAlertNotification;
use Illuminate\Http\Request;

class ShopProfileController extends Controller
{
    public function edit(Request $request)
    {
        $profile = $request->user()->farmerProfile ?? new FarmerProfile([
            'owner_name' => $request->user()->name,
            'whatsapp' => $request->user()->phone,
            'regency' => 'Rejang Lebong',
            'province' => 'Bengkulu',
        ]);

        return view('user.shop.edit', compact('profile'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'business_name' => ['required', 'string', 'max:150'],
            'shop_name' => ['required', 'string', 'max:150'],
            'owner_name' => ['required', 'string', 'max:150'],
            'address' => ['required', 'string'],
            'village' => ['nullable', 'string', 'max:100'],
            'district' => ['required', 'string', 'max:100'],
            'regency' => ['nullable', 'string', 'max:100'],
            'province' => ['nullable', 'string', 'max:100'],
            'whatsapp' => ['required', 'string', 'max:30'],
            'bio' => ['nullable', 'string'],
            'shop_description' => ['required', 'string'],
            'shop_logo' => ['nullable', 'image', 'max:2048'],
            'shop_cover' => ['nullable', 'image', 'max:4096'],
        ]);

        $profile = $request->user()->farmerProfile;
        $wasRejected = $profile && in_array($profile->storefront_status, ['rejected', 'inactive'], true);

        $payload = [
            ...$data,
            'user_id' => $request->user()->id,
            'verification_status' => $profile?->verification_status === 'approved' ? 'approved' : 'pending',
            'storefront_status' => 'pending',
            'storefront_rejection_reason' => null,
        ];

        if ($request->hasFile('shop_logo')) {
            $payload['shop_logo_path'] = $request->file('shop_logo')->store('uploads/shops', 'public');
        }
        if ($request->hasFile('shop_cover')) {
            $payload['shop_cover_path'] = $request->file('shop_cover')->store('uploads/shops', 'public');
        }

        unset($payload['shop_logo'], $payload['shop_cover']);

        if ($profile) {
            $profile->update($payload);
        } else {
            $profile = FarmerProfile::create($payload);
        }

        $request->user()->notify(new UserAlertNotification(
            'Profil toko dikirim',
            'Profil toko Anda menunggu persetujuan admin sebelum dapat menjual di katalog.',
            'shop',
            route('user.shop.edit'),
        ));

        return back()->with('status', $wasRejected
            ? 'Profil toko dikirim ulang untuk ditinjau admin.'
            : 'Profil toko disimpan dan menunggu persetujuan admin.');
    }
}
