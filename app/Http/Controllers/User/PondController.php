<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Pond;
use Illuminate\Http\Request;

class PondController extends Controller
{
    public function index(Request $request)
    {
        $ponds = Pond::with([
            'latestHealthScore',
            'cycles' => fn ($q) => $q->with('fishSpecies')->whereIn('status', ['active', 'near_harvest', 'completed', 'preparation', 'failed'])->latest(),
        ])
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return view('user.ponds.index', compact('ponds'));
    }

    public function show(Request $request, Pond $pond)
    {
        abort_unless($pond->user_id === $request->user()->id || $request->user()->hasRole('admin'), 403);
        $pond->ensurePublicToken();
        $pond->load(['latestHealthScore']);

        $cycles = $pond->cycles()->with(['fishSpecies', 'recommendations' => fn ($q) => $q->latest()->limit(3)])->latest()->get();

        return view('user.ponds.show', [
            'pond' => $pond,
            'cycles' => $cycles,
            'qrUrl' => route('ponds.public', $pond->public_token),
        ]);
    }

    public function create()
    {
        return view('user.ponds.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:beton,terpal,tanah,bioflok,lainnya'],
            'area_m2' => ['nullable', 'numeric', 'min:0'],
            'depth_m' => ['nullable', 'numeric', 'min:0'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'notes' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive,maintenance'],
            'hide_exact_location' => ['sometimes', 'boolean'],
        ]);

        if (! empty($data['area_m2']) && ! empty($data['depth_m'])) {
            $data['volume_m3'] = round($data['area_m2'] * $data['depth_m'], 2);
        }

        $pond = Pond::create([
            ...$data,
            'user_id' => $request->user()->id,
            'farmer_profile_id' => $request->user()->farmerProfile?->id,
            'hide_exact_location' => $request->boolean('hide_exact_location', true),
        ]);

        return redirect()->route('user.ponds.show', $pond)->with('status', 'Kolam berhasil ditambahkan.');
    }

    public function edit(Request $request, Pond $pond)
    {
        abort_unless($pond->user_id === $request->user()->id || $request->user()->hasRole('admin'), 403);

        return view('user.ponds.edit', compact('pond'));
    }

    public function update(Request $request, Pond $pond)
    {
        abort_unless($pond->user_id === $request->user()->id || $request->user()->hasRole('admin'), 403);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:beton,terpal,tanah,bioflok,lainnya'],
            'area_m2' => ['nullable', 'numeric', 'min:0'],
            'depth_m' => ['nullable', 'numeric', 'min:0'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'notes' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive,maintenance'],
            'hide_exact_location' => ['sometimes', 'boolean'],
        ]);

        if (! empty($data['area_m2']) && ! empty($data['depth_m'])) {
            $data['volume_m3'] = round($data['area_m2'] * $data['depth_m'], 2);
        }

        $pond->update([
            ...$data,
            'hide_exact_location' => $request->boolean('hide_exact_location'),
        ]);

        return redirect()->route('user.ponds.show', $pond)->with('status', 'Kolam diperbarui.');
    }

    public function destroy(Request $request, Pond $pond)
    {
        abort_unless($pond->user_id === $request->user()->id || $request->user()->hasRole('admin'), 403);

        $cycleCount = $pond->cycles()->count();
        $pond->cycles()->each(function ($cycle) {
            $cycle->delete();
        });
        $pond->delete();

        $msg = $cycleCount > 0
            ? "Kolam dihapus beserta {$cycleCount} siklus terkait."
            : 'Kolam dihapus.';

        return redirect()->route('user.ponds.index')->with('status', $msg);
    }

    public function deactivate(Request $request, Pond $pond)
    {
        abort_unless($pond->user_id === $request->user()->id || $request->user()->hasRole('admin'), 403);

        $pond->update(['status' => 'inactive']);

        return redirect()->route('user.ponds.index')->with('status', 'Kolam dinonaktifkan / disembunyikan dari daftar aktif.');
    }

    public function activate(Request $request, Pond $pond)
    {
        abort_unless($pond->user_id === $request->user()->id || $request->user()->hasRole('admin'), 403);

        $pond->update(['status' => 'active']);

        return redirect()->back()->with('status', 'Kolam diaktifkan kembali.');
    }
}
