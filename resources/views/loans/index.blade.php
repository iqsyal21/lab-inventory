@extends('layouts.app')

@section('title', 'Data Peminjaman')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Data Peminjaman</h2>
    <div class="d-flex gap-2">
        <a href="{{ route('loans.export', request()->query()) }}" class="btn btn-success">
            <i class="fas fa-download me-1"></i>Download CSV
        </a>
        <button type="button" class="btn btn-outline-primary" data-bs-toggle="offcanvas" data-bs-target="#filterDrawer">
            <i class="fas fa-filter me-1"></i>Filter
        </button>
        <a href="{{ route('loans.create') }}" class="btn btn-primary">
            + Tambah Peminjaman
        </a>
    </div>
</div>

@if (session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

@if (session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

{{-- Filter Drawer --}}
<div class="offcanvas offcanvas-end" tabindex="-1" id="filterDrawer" aria-labelledby="filterDrawerLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="filterDrawerLabel">Filter Data Peminjaman</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form method="GET" action="{{ route('loans.index') }}">
            <div class="mb-3">
                <label for="search" class="form-label">Cari</label>
                <input type="text" class="form-control" id="search" name="search"
                    value="{{ request('search') }}" placeholder="Nama pegawai, barang...">
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="">Semua Status</option>
                    <option value="Dipinjam" {{ request('status') == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                    <option value="Dikembalikan" {{ request('status') == 'Dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                    <option value="Hilang" {{ request('status') == 'Hilang' ? 'selected' : '' }}>Hilang</option>
                    <option value="Rusak" {{ request('status') == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="date_from" class="form-label">Dari Tanggal</label>
                <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
            </div>
            <div class="mb-3">
                <label for="date_to" class="form-label">Sampai Tanggal</label>
                <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                <a href="{{ route('loans.index') }}" class="btn btn-outline-secondary">Reset Filter</a>
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
                        <th>No</th>
                        <th>Barang</th>
                        <th>Peminjam</th>
                        <th>Departemen / Jabatan</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Kembali</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($loans as $loan)
                    <tr>
                        <td>{{ $loans->firstItem() + $loop->index }}</td>
                        <td>{{ $loan->item->name ?? '-' }}</td>
                        <td>{{ $loan->employee->name ?? '-' }}</td>
                        <td>{{ $loan->employee->department ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($loan->loan_date)->format('d M Y') }}</td>
                        <td>
                            @if ($loan->expected_return_date)
                            {{ \Carbon\Carbon::parse($loan->expected_return_date)->format('d M Y') }}
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @switch($loan->status)
                            @case('Dipinjam')
                            <span class="badge bg-warning text-dark">Dipinjam</span>
                            @break
                            @case('Dikembalikan')
                            <span class="badge bg-success">Dikembalikan</span>
                            @break
                            @case('Hilang')
                            <span class="badge bg-danger">Hilang</span>
                            @break
                            @case('Rusak')
                            <span class="badge bg-secondary">Rusak</span>
                            @break
                            @default
                            <span class="badge bg-light text-dark">Tidak Diketahui</span>
                            @endswitch
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('loans.show', $loan->id) }}" class="btn btn-sm btn-outline-info">Detail</a>
                                <a href="{{ route('loans.edit', $loan->id) }}" class="btn btn-sm btn-outline-primary">
                                    Edit
                                </a>
                                <form action="{{ route('loans.destroy', $loan->id) }}" method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus data peminjaman ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        Hapus
                                    </button>
                                </form>
                                <a href="{{ route('loans.print', $loan->id) }}" class="btn btn-sm btn-outline-secondary" target="_blank">
                                    <i class="bi bi-printer"></i> Print
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            Belum ada data peminjaman.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($loans->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted">
                Menampilkan {{ $loans->firstItem() }} sampai {{ $loans->lastItem() }} dari {{ $loans->total() }} data
            </div>
            <div>
                {{ $loans->links() }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection