@extends('layouts.app')

@section('title', 'Pengembalian Barang')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Tambah Pengembalian</h2>
    <a href="{{ route('returns.index') }}" class="btn btn-outline-secondary">
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

<form action="{{ route('returns.store') }}" method="POST" class="p-4 border rounded bg-light shadow-sm">
    @csrf

    {{-- Pilih Peminjaman --}}
    <div class="mb-3">
        <label for="loan_id" class="form-label fw-semibold">Pilih Data Peminjaman</label>
        <select name="loan_id" id="loan_id" class="form-select" required onchange="updateLoanInfo()">
            <option value="">-- Pilih Peminjaman --</option>
            @foreach ($loans as $loan)
            <option value="{{ $loan->id }}"
                data-item="{{ $loan->item->name }}"
                data-quantity="{{ $loan->quantity }}"
                data-borrower="{{ $loan->borrower_name }}"
                {{ old('loan_id') == $loan->id ? 'selected' : '' }}>
                {{ $loan->borrower_name }} - {{ $loan->item->name }} ({{ $loan->quantity }} unit)
            </option>
            @endforeach
        </select>
    </div>

    {{-- Info otomatis --}}
    <div id="loan-info" class="alert alert-secondary py-2 px-3 small">
        <strong>Barang:</strong> - <br>
        <strong>Jumlah:</strong> - <br>
        <strong>Peminjam:</strong> -
    </div>

    <div class="row mb-3">
        {{-- Tanggal Pengembalian --}}
        <div class="col-md-6">
            <label for="return_date" class="form-label fw-semibold">Tanggal Pengembalian</label>
            <input type="date" id="return_date" name="return_date" class="form-control"
                value="{{ old('return_date', date('Y-m-d')) }}" required>
        </div>

        {{-- Kondisi / Catatan --}}
        <div class="col-md-6">
            <label for="notes" class="form-label fw-semibold">Catatan Kondisi (Opsional)</label>
            <input type="text" id="notes" name="notes" class="form-control"
                value="{{ old('notes') }}" placeholder="Contoh: Barang lengkap, kabel sedikit rusak">
        </div>
    </div>

    <div class="text-end">
        <button type="submit" class="btn btn-primary px-4">
            Simpan Pengembalian
        </button>
    </div>
</form>

<script>
    function updateLoanInfo() {
        const select = document.getElementById('loan_id');
        const info = document.getElementById('loan-info');
        const selected = select.options[select.selectedIndex];

        if (selected.value) {
            const item = selected.dataset.item;
            const qty = selected.dataset.quantity;
            const borrower = selected.dataset.borrower;
            info.innerHTML = `
                <strong>Barang:</strong> ${item}<br>
                <strong>Jumlah:</strong> ${qty}<br>
                <strong>Peminjam:</strong> ${borrower}
            `;
        } else {
            info.innerHTML = `<strong>Barang:</strong> - <br><strong>Jumlah:</strong> - <br><strong>Peminjam:</strong> -`;
        }
    }

    document.addEventListener('DOMContentLoaded', updateLoanInfo);
</script>
@endsection