@extends('layouts.admin')
@section('title', 'Katalog')
@section('content')
<h2 class="mb-3">Moderasi Katalog (seperti toko)</h2>
<div class="card"><div class="card-body table-responsive">
<table class="table table-striped align-middle">
<thead><tr><th>Produk</th><th>Toko</th><th>Harga</th><th>Status</th><th></th></tr></thead>
<tbody>
@foreach($products as $product)
<tr>
<td>
    <strong>{{ $product->title }}</strong>
    <div class="small text-muted">{{ $product->fishSpecies?->name }} · {{ $product->photos->count() }} foto</div>
</td>
<td>{{ $product->farmerProfile?->shop_name ?: $product->user?->name }}</td>
<td>Rp {{ number_format($product->price,0,',','.') }}/{{ $product->price_unit }}</td>
<td>
    <span class="badge bg-{{ $product->moderation_status === 'approved' ? 'success' : ($product->moderation_status === 'rejected' ? 'danger' : 'warning') }}">{{ $product->moderation_status }}</span>
</td>
<td class="text-end"><a href="{{ route('admin.products.show', $product) }}" class="btn btn-sm btn-primary">Detail sebelum rilis</a></td>
</tr>
@endforeach
</tbody>
</table>
{{ $products->links() }}
</div></div>
@endsection
