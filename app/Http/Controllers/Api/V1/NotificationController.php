<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Models\DeviceToken;
use App\Models\NotificationPreference;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function storeDeviceToken(Request $request)
    {
        $data = $request->validate([
            'token' => ['required', 'string'],
            'platform' => ['nullable', 'string', 'max:50'],
        ]);

        DeviceToken::updateOrCreate(
            ['user_id' => $request->user()->id, 'token' => $data['token']],
            ['platform' => $data['platform'] ?? null]
        );

        return ApiResponse::success(null, 'Device token tersimpan', 201);
    }

    public function destroyDeviceToken(Request $request)
    {
        $data = $request->validate(['token' => ['required', 'string']]);
        DeviceToken::where('user_id', $request->user()->id)->where('token', $data['token'])->delete();

        return ApiResponse::success(null, 'Device token dihapus');
    }

    public function index(Request $request)
    {
        $notifications = $request->user()->notifications()->paginate(20);

        return ApiResponse::success($notifications, 'Daftar notifikasi');
    }

    public function updatePreferences(Request $request)
    {
        $data = $request->validate([
            'water_critical' => ['sometimes', 'boolean'],
            'feeding_reminder' => ['sometimes', 'boolean'],
            'measurement_reminder' => ['sometimes', 'boolean'],
            'harvest_near' => ['sometimes', 'boolean'],
            'new_products' => ['sometimes', 'boolean'],
            'announcements' => ['sometimes', 'boolean'],
        ]);

        $prefs = NotificationPreference::firstOrCreate(['user_id' => $request->user()->id]);
        $prefs->update($data);

        return ApiResponse::success($prefs->fresh(), 'Preferensi notifikasi diperbarui');
    }
}
