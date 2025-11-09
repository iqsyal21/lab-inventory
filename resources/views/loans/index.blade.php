@extends('layouts.app')

@section('title', 'Data Peminjaman')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Data Peminjaman</h2>
    <div class="d-flex gap-2">
        <a href="{{ route('loans.export', request()->query()) }}" class="btn btn-success">
            <i class="fas fa-download me-1"></i>Download CSV
        </a>

        {{-- Tombol Print --}}
        <button id="print-selected" class="btn btn-outline-secondary">
            Print
        </button>

        {{-- Tombol Hapus --}}
        <button id="delete-selected" class="btn btn-outline-danger">
            Hapus
        </button>

        {{-- Tombol Filter --}}
        <button type="button" class="btn btn-outline-primary" data-bs-toggle="offcanvas" data-bs-target="#filterDrawer">
            Filter
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

{{-- FILTER DRAWER --}}
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
{{-- END FILTER DRAWER --}}

<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th><input type="checkbox" id="select-all"></th>
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
                        <td><input type="checkbox" name="selected_loans[]" value="{{ $loan->id }}" class="loan-checkbox"></td>
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
                            @default
                            <span class="badge bg-light text-dark">Tidak Diketahui</span>
                            @endswitch
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('loans.show', $loan->id) }}" class="btn btn-sm btn-outline-info">Detail</a>
                                <a href="{{ route('loans.edit', $loan->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <form action="{{ route('loans.destroy', $loan->id) }}" method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus data peminjaman ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">Belum ada data peminjaman.</td>
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAll = document.getElementById('select-all');
        const checkboxes = document.querySelectorAll('.loan-checkbox');
        const printBtn = document.getElementById('print-selected');
        const deleteBtn = document.getElementById('delete-selected');

        // Select All Checkbox
        selectAll?.addEventListener('change', function() {
            checkboxes.forEach(cb => cb.checked = selectAll.checked);
        });

        // PRINT
        printBtn?.addEventListener('click', function() {
            const selected = Array.from(checkboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.value);

            if (selected.length === 0) {
                alert('Belum ada data yang dipilih untuk dicetak!');
                return;
            }

            const url = "{{ route('loans.printMultiple') }}" + "?ids=" + selected.join(',');
            window.open(url, '_blank');
        });

        // DELETE
        deleteBtn?.addEventListener('click', function() {
            const selected = Array.from(checkboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.value);

            if (selected.length === 0) {
                alert('Belum ada data yang dipilih untuk dihapus!');
                return;
            }

            if (!confirm('Yakin ingin menghapus data peminjaman terpilih?')) return;

            fetch("{{ route('loans.bulkDelete') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        ids: selected
                    })
                })
                .then(res => res.json())
                .then(data => {
                    alert(data.message || 'Data berhasil dihapus.');
                    location.reload();
                })
                .catch(err => {
                    console.error(err);
                    alert('Terjadi kesalahan saat menghapus data.');
                });
        });
    });
</script>
@endpush