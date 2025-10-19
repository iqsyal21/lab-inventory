@extends('layouts.app')

@section('title', 'Data Barang')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Data Barang</h2>
    <div class="d-flex gap-2">
        <a href="{{ route('items.export', request()->query()) }}" class="btn btn-success">
            <i class="fas fa-download me-1"></i>Download CSV
        </a>
        <button type="button" class="btn btn-outline-primary" data-bs-toggle="offcanvas" data-bs-target="#filterDrawer">
            <i class="fas fa-filter me-1"></i>Filter
        </button>
        <a href="{{ route('items.create') }}" class="btn btn-primary">
            + Tambah Barang
        </a>
    </div>
</div>

@if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

@if (session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif

{{-- Filter Drawer --}}
<div class="offcanvas offcanvas-end" tabindex="-1" id="filterDrawer" aria-labelledby="filterDrawerLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="filterDrawerLabel">Filter Data Barang</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form method="GET" action="{{ route('items.index') }}" id="filterForm">
            <div class="mb-3">
                <label for="search" class="form-label">Cari</label>
                <input type="text" class="form-control" id="search" name="search" 
                       value="{{ request('search') }}" placeholder="Nama, kode, atau lokasi...">
            </div>
            <div class="mb-3">
                <label for="condition" class="form-label">Kondisi</label>
                <select class="form-select" id="condition" name="condition">
                    <option value="">Semua Kondisi</option>
                    <option value="Baik" {{ request('condition') == 'Baik' ? 'selected' : '' }}>Baik</option>
                    <option value="Rusak" {{ request('condition') == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="availability" class="form-label">Ketersediaan</label>
                <select class="form-select" id="availability" name="availability">
                    <option value="">Semua</option>
                    <option value="available" {{ request('availability') == 'available' ? 'selected' : '' }}>Tersedia</option>
                    <option value="unavailable" {{ request('availability') == 'unavailable' ? 'selected' : '' }}>Tidak Tersedia</option>
                </select>
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                <a href="{{ route('items.index') }}" class="btn btn-outline-secondary">Reset Filter</a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Kode</th>
                        <th>Nama Barang</th>
                        <th>Jumlah Total</th>
                        <th>Jumlah Tersedia</th>
                        <th>Kondisi</th>
                        <th>Lokasi</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                    <tr>
                        <td>{{ $items->firstItem() + $loop->index }}</td>
                        <td>{{ $item->code }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->quantity_total }}</td>
                        <td>
                            @if ($item->quantity_available > 0)
                            <span class="badge bg-success">{{ $item->quantity_available }}</span>
                            @else
                            <span class="badge bg-danger">0</span>
                            @endif
                        </td>
                        <td>
                            @if ($item->condition === 'Baik')
                            <span class="badge bg-success">Baik</span>
                            @else
                            <span class="badge bg-warning text-dark">Rusak</span>
                            @endif
                        </td>
                        <td>{{ $item->location ?? '-' }}</td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('items.edit', $item->id) }}" class="btn btn-sm btn-outline-primary">
                                    Edit
                                </a>
                                <form action="{{ route('items.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus barang ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            Belum ada data barang.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted">
                Menampilkan {{ $items->firstItem() }} sampai {{ $items->lastItem() }} dari {{ $items->total() }} data
            </div>
            <div>
                {{ $items->links() }}
            </div>
        </div>
    </div>
</div>
@endsection