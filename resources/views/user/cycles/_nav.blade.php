@php
    $modules = [
        ['route' => 'user.cycles.show', 'match' => 'user.cycles.show', 'label' => 'Laporan'],
        ['route' => 'user.cycles.water', 'match' => 'user.cycles.water*', 'label' => 'Kualitas Air'],
        ['route' => 'user.cycles.feeding', 'match' => 'user.cycles.feeding*', 'label' => 'Pakan'],
        ['route' => 'user.cycles.mortality', 'match' => 'user.cycles.mortality*', 'label' => 'Kematian'],
        ['route' => 'user.cycles.growth', 'match' => 'user.cycles.growth*', 'label' => 'Pertumbuhan'],
        ['route' => 'user.cycles.costs', 'match' => 'user.cycles.costs*', 'label' => 'Biaya'],
    ];
@endphp
<div class="pk-page-head d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <div>
        <h4 class="mb-0">{{ $cycle->name }}</h4>
        <div class="small text-muted">
            <a href="{{ route('user.ponds.show', $cycle->pond_id) }}">{{ $cycle->pond?->name ?? 'Kolam' }}</a>
            · {{ $cycle->fishSpecies?->name }} · {{ $cycle->status }}
        </div>
    </div>
    <div class="pk-actions">
        <a href="{{ route('user.cycles.print', $cycle) }}" target="_blank" class="pk-btn pk-btn-primary">Cetak PDF</a>
        <a href="{{ route('user.ponds.index') }}" class="pk-btn pk-btn-outline">Budidaya</a>
        @unless(in_array($cycle->status, ['failed', 'completed'], true))
        <form method="POST" action="{{ route('user.cycles.deactivate', $cycle) }}" class="d-inline"
              onsubmit="return confirm('Nonaktifkan siklus {{ $cycle->name }}?')">
            @csrf
            <button type="submit" class="pk-btn pk-btn-warn">Nonaktifkan</button>
        </form>
        @endunless
        <form method="POST" action="{{ route('user.cycles.destroy', $cycle) }}" class="d-inline"
              onsubmit="return confirm('Hapus siklus {{ $cycle->name }}?\nData terkait siklus ini ikut terhapus dari daftar.')">
            @csrf
            @method('DELETE')
            <button type="submit" class="pk-btn pk-btn-danger">Hapus</button>
        </form>
    </div>
</div>

<div class="pk-module-nav mb-3">
    @foreach($modules as $mod)
        <a href="{{ route($mod['route'], $cycle) }}"
           class="pk-module-link {{ request()->routeIs($mod['match']) ? 'active' : '' }}">
            {{ $mod['label'] }}
        </a>
    @endforeach
</div>
