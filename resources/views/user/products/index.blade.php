@extends('layouts.user')
@section('title', 'Katalog Saya')
@section('content')
<div class="pk-page-head d-flex justify-content-between align-items-start mb-3 flex-wrap gap-2">
    <div>
        <h2 class="pk-page-title mb-1">Katalog Saya</h2>
        <div class="small text-muted">Kelola produk: edit, stok habis, dan hapus kapan saja</div>
    </div>
    <div class="pk-actions">
        <a href="{{ route('user.shop.edit') }}" class="pk-btn pk-btn-outline">Profil Toko</a>
        <a href="{{ route('user.products.create') }}" class="pk-btn pk-btn-primary">Tambah Produk</a>
    </div>
</div>

@if(!$shop || !$shop->canSell())
<div class="alert alert-warning">Profil toko belum disetujui. Lengkapi profil toko dan tunggu approve admin sebelum menjual.</div>
@endif

<div class="card">
    <div class="card-body p-0 table-responsive">
        <table class="table pk-table mb-0 align-middle">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Moderasi</th>
                    <th>Catatan</th>
                    <th class="text-end pe-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($products as $product)
                <tr>
                    <td>
                        <div class="d-flex gap-2 align-items-center">
                            @if($product->photos->first())
                                <img src="{{ asset('storage/'.$product->photos->first()->path) }}" alt="" class="rounded" style="width:44px;height:44px;object-fit:cover">
                            @endif
                            <div>
                                <strong>{{ $product->title }}</strong>
                                <div class="small text-muted">{{ $product->fishSpecies?->name }} · {{ $product->photos->count() }} foto</div>
                            </div>
                        </div>
                    </td>
                    <td class="pk-money">Rp {{ number_format($product->price, 0, ',', '.') }}/{{ $product->price_unit }}</td>
                    <td>
                        @if($product->availability === 'sold_out')
                            <span class="pk-badge pk-badge-danger">Stok habis</span>
                        @else
                            <span class="pk-badge pk-badge-ok">Tersedia</span>
                        @endif
                        @if($product->stock_kg !== null)
                            <div class="small text-muted mt-1">{{ rtrim(rtrim(number_format((float)$product->stock_kg, 2, ',', '.'), '0'), ',') }} {{ $product->price_unit }}</div>
                        @endif
                    </td>
                    <td>
                        <span class="pk-badge {{ $product->moderation_status === 'approved' ? 'pk-badge-ok' : ($product->moderation_status === 'rejected' ? 'pk-badge-danger' : 'pk-badge-warn') }}">
                            {{ $product->moderation_status }}
                        </span>
                    </td>
                    <td class="small text-muted">{{ $product->rejection_reason ?: '—' }}</td>
                    <td class="text-end pe-3">
                        <div class="pk-actions justify-content-end">
                            @if($product->is_published && $product->moderation_status === 'approved')
                                <a href="{{ route('landing.product', $product) }}" class="pk-btn pk-btn-outline pk-btn-sm" target="_blank">Lihat</a>
                            @endif
                            <a href="{{ route('user.products.edit', $product) }}" class="pk-btn pk-btn-outline pk-btn-sm">Edit</a>
                            <form method="POST" action="{{ route('user.products.availability', $product) }}">
                                @csrf
                                <button class="pk-btn pk-btn-outline pk-btn-sm" type="submit">
                                    {{ $product->availability === 'sold_out' ? 'Stok Ada' : 'Stok Habis' }}
                                </button>
                            </form>
                            <form method="POST" action="{{ route('user.products.destroy', $product) }}" onsubmit="return confirm('Hapus produk ini?')">
                                @csrf @method('DELETE')
                                <button class="pk-btn pk-btn-outline pk-btn-sm" type="submit">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted py-4">Belum ada produk. Tambah produk pertama Anda.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($products->hasPages())
        <div class="card-footer">{{ $products->links() }}</div>
    @endif
</div>
@endsection
