@extends('layouts.app')

@section('title', 'Data Pengembalian')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Data Pengembalian</h2>
    <div class="d-flex gap-2">
        <a href="{{ route('returns.export', request()->query()) }}" class="btn btn-success">
            <i class="fas fa-download me-1"></i>Download CSV
        </a>
        <button type="button" class="btn btn-outline-primary" data-bs-toggle="offcanvas" data-bs-target="#filterDrawer">
            <i class="fas fa-filter me-1"></i>Filter
        </button>
        <a href="{{ route('returns.create') }}" class="btn btn-primary">
            + Tambah Pengembalian
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
        <h5 class="offcanvas-title" id="filterDrawerLabel">Filter Data Pengembalian</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form method="GET" action="{{ route('returns.index') }}" id="filterForm">
            <div class="mb-3">
                <label for="search" class="form-label">Cari</label>
                <input type="text" class="form-control" id="search" name="search" 
                       value="{{ request('search') }}" placeholder="Nama peminjam, barang...">
            </div>
            <div class="mb-3">
                <label for="condition" class="form-label">Kondisi</label>
                <select class="form-select" id="condition" name="condition">
                    <option value="">Semua Kondisi</option>
                    <option value="Baik" {{ request('condition') == 'Baik' ? 'selected' : '' }}>Baik</option>
                    <option value="Rusak" {{ request('condition') == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                    <option value="Hilang" {{ request('condition') == 'Hilang' ? 'selected' : '' }}>Hilang</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="date_from" class="form-label">Dari Tanggal</label>
                <input type="date" class="form-control" id="date_from" name="date_from" 
                       value="{{ request('date_from') }}">
            </div>
            <div class="mb-3">
                <label for="date_to" class="form-label">Sampai Tanggal</label>
                <input type="date" class="form-control" id="date_to" name="date_to" 
                       value="{{ request('date_to') }}">
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                <a href="{{ route('returns.index') }}" class="btn btn-outline-secondary">Reset Filter</a>
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
                        <th>Barang</th>
                        <th>Peminjam</th>
                        <th>Jumlah</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Kembali</th>
                        <th>Catatan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($returns as $return)
                    <tr>
                        <td>{{ $returns->firstItem() + $loop->index }}</td>
                        <td>{{ $return->loan->item->name ?? '-' }}</td>
                        <td>{{ $return->loan->borrower_name ?? '-' }}</td>
                        <td>{{ $return->quantity_returned }}</td>
                        <td>{{ \Carbon\Carbon::parse($return->loan->loan_date)->format('d M Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($return->return_date)->format('d M Y') }}</td>
                        <td>{{ $return->notes ?? '-' }}</td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <form action="{{ route('returns.destroy', $return->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data pengembalian ini?')">
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
                            Belum ada data pengembalian.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted">
                Menampilkan {{ $returns->firstItem() }} sampai {{ $returns->lastItem() }} dari {{ $returns->total() }} data
            </div>
            <div>
                {{ $returns->links() }}
            </div>
        </div>
    </div>
</div>
@endsection