<?php

namespace App\Http\Controllers;

use App\Models\Pond;

class PublicPondController extends Controller
{
    public function show(string $token)
    {
        $pond = Pond::with([
            'user:id,name',
            'farmerProfile',
            'latestHealthScore',
            'cycles' => fn ($q) => $q->with(['fishSpecies', 'harvestEstimate'])
                ->whereIn('status', ['active', 'near_harvest'])
                ->latest(),
        ])->where('public_token', $token)->firstOrFail();

        return view('public.pond', compact('pond'));
    }
}
