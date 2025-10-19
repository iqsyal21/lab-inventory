@extends('layouts.app')

@section('title', 'Tambah Peminjaman')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Tambah Peminjaman</h2>
    <a href="{{ route('loans.index') }}" class="btn btn-outline-secondary">
        ← Kembali
    </a>
</div>

@if ($errors->any())
<div class="alert alert-danger">
    <strong>Terjadi kesalahan!</strong>
    <ul class="mb-0 mt-2">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('loans.store') }}" method="POST" class="p-4 border rounded bg-light shadow-sm">
    @csrf

    <div class="row mb-3">
        {{-- Pilih Barang --}}
        <div class="col-md-6">
            <label for="item_id" class="form-label fw-semibold">Barang</label>
            <select name="item_id" id="item_id" class="form-select" required onchange="updateStock()">
                <option value="">-- Pilih Barang --</option>
                @foreach ($items as $item)
                <option value="{{ $item->id }}"
                    data-total="{{ $item->quantity_total }}"
                    data-available="{{ $item->quantity_available }}"
                    {{ old('item_id') == $item->id ? 'selected' : '' }}>
                    {{ $item->name }}
                </option>
                @endforeach
            </select>
            <small id="stock-info" class="text-muted">
                Jumlah tersedia:
                {{ $items->first()?->quantity_available ?? 0 }}
            </small>
        </div>

        {{-- Jumlah Dipinjam --}}
        <div class="col-md-6">
            <label for="quantity" class="form-label fw-semibold">Jumlah Dipinjam</label>
            <input type="number" id="quantity" name="quantity" class="form-control"
                value="{{ old('quantity') }}" placeholder="Masukkan jumlah barang" min="1" required>
        </div>
    </div>

    <div class="row mb-3">
        {{-- Nama Peminjam --}}
        <div class="col-md-6">
            <label for="borrower_name" class="form-label fw-semibold">Nama Peminjam</label>
            <input type="text" id="borrower_name" name="borrower_name" class="form-control"
                value="{{ old('borrower_name') }}" placeholder="Contoh: Andi Saputra" required>
        </div>

        {{-- Peran Peminjam --}}
        <div class="col-md-6">
            <label class="form-label fw-semibold d-block">Peran Peminjam</label>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="borrower_role" id="role_mahasiswa" value="Mahasiswa"
                    {{ old('borrower_role', 'Mahasiswa') == 'Mahasiswa' ? 'checked' : '' }}>
                <label class="form-check-label" for="role_mahasiswa">Mahasiswa</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="borrower_role" id="role_dosen" value="Dosen"
                    {{ old('borrower_role') == 'Dosen' ? 'checked' : '' }}>
                <label class="form-check-label" for="role_dosen">Dosen</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="borrower_role" id="role_teknisi" value="Teknisi"
                    {{ old('borrower_role') == 'Teknisi' ? 'checked' : '' }}>
                <label class="form-check-label" for="role_teknisi">Teknisi</label>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        {{-- Prodi / Unit --}}
        <div class="col-md-6">
            <label for="borrower_department" class="form-label fw-semibold">Prodi / Unit</label>
            <input type="text" id="borrower_department" name="borrower_department" class="form-control"
                value="{{ old('borrower_department') }}" placeholder="Contoh: Teknik Informatika / Lab Jaringan">
        </div>

        {{-- Tanggal Peminjaman --}}
        <div class="col-md-6">
            <label for="loan_date" class="form-label fw-semibold">Tanggal Peminjaman</label>
            <input
                type="date"
                id="loan_date"
                name="loan_date"
                class="form-control"
                min="{{ date('Y-m-d') }}"
                value="{{ old('loan_date', date('Y-m-d')) }}"
                required>
        </div>
    </div>

    {{-- (Opsional) Keterangan --}}
    {{--
    <div class="mb-3">
        <label for="note" class="form-label fw-semibold">Keterangan</label>
        <textarea id="note" name="note" class="form-control" rows="3" placeholder="Contoh: Untuk praktikum jaringan minggu ini">{{ old('note') }}</textarea>
    </div>
    --}}

    <div class="text-end">
        <button type="submit" class="btn btn-primary px-4">
            Simpan
        </button>
    </div>
</form>

{{-- Script untuk update info stok --}}
<script>
    function updateStock() {
        const select = document.getElementById('item_id');
        const stockInfo = document.getElementById('stock-info');
        const qtyInput = document.getElementById('quantity');
        const selectedOption = select.options[select.selectedIndex];
        const available = selectedOption?.dataset?.available ?? 0;
        stockInfo.textContent = 'Jumlah tersedia: ' + available;
        if (qtyInput) {
            qtyInput.max = available;
        }
    }

    document.addEventListener('DOMContentLoaded', updateStock);
</script>
@endsection