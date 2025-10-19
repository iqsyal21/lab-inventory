@extends('layouts.app')

@section('title', 'Edit Peminjaman')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Edit Peminjaman</h2>
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

<form action="{{ route('loans.update', $loan->id) }}" method="POST" class="p-4 border rounded bg-light shadow-sm">
    @csrf
    @method('PUT')

    <div class="row mb-3">
        {{-- Pilih Barang --}}
        <div class="col-md-6">
            <label for="item_id" class="form-label fw-semibold">Barang</label>
            <select name="item_id" id="item_id" class="form-select" required onchange="updateStock()">
                <option value="">-- Pilih Barang --</option>
                @foreach ($items as $item)
                <option value="{{ $item->id }}"
                    data-available="{{ $item->quantity_available }}"
                    {{ old('item_id', $loan->item_id) == $item->id ? 'selected' : '' }}>
                    {{ $item->name }}
                </option>
                @endforeach
            </select>
            <small id="stock-info" class="text-muted">
                Stok tersedia: {{ $loan->item->quantity_available ?? 0 }}
            </small>
        </div>

        {{-- Jumlah --}}
        <div class="col-md-6">
            <label for="quantity" class="form-label fw-semibold">Jumlah Dipinjam</label>
            <input type="number" id="quantity" name="quantity" class="form-control"
                value="{{ old('quantity', $loan->quantity) }}" min="1" required>
        </div>
    </div>

    <div class="row mb-3">
        {{-- Nama Peminjam --}}
        <div class="col-md-6">
            <label for="borrower_name" class="form-label fw-semibold">Nama Peminjam</label>
            <input type="text" id="borrower_name" name="borrower_name" class="form-control"
                value="{{ old('borrower_name', $loan->borrower_name) }}" placeholder="Contoh: Andi Saputra" required>
        </div>

        {{-- Peran Peminjam --}}
        <div class="col-md-6">
            <label class="form-label fw-semibold d-block">Peran Peminjam</label>
            @php
            $roles = ['Mahasiswa', 'Dosen', 'Teknisi'];
            @endphp
            @foreach ($roles as $role)
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="borrower_role" id="role_{{ strtolower($role) }}"
                    value="{{ $role }}" {{ old('borrower_role', $loan->borrower_role) == $role ? 'checked' : '' }}>
                <label class="form-check-label" for="role_{{ strtolower($role) }}">{{ $role }}</label>
            </div>
            @endforeach
        </div>
    </div>

    <div class="row mb-3">
        {{-- Prodi / Unit --}}
        <div class="col-md-6">
            <label for="borrower_department" class="form-label fw-semibold">Prodi / Unit</label>
            <input type="text" id="borrower_department" name="borrower_department" class="form-control"
                value="{{ old('borrower_department', $loan->borrower_department) }}"
                placeholder="Contoh: Teknik Informatika / Lab Jaringan">
        </div>

        {{-- Tanggal Peminjaman --}}
        <div class="col-md-6">
            <label for="loan_date" class="form-label fw-semibold">Tanggal Peminjaman</label>
            <input type="date" id="loan_date" name="loan_date" class="form-control"
                value="{{ old('loan_date', \Carbon\Carbon::parse($loan->loan_date)->format('Y-m-d')) }}" required>
        </div>
    </div>

    <div class="text-end">
        <button type="submit" class="btn btn-primary px-4">
            Perbarui
        </button>
    </div>
</form>

{{-- Script untuk update stok --}}
<script>
    function updateStock() {
        const select = document.getElementById('item_id');
        const stockInfo = document.getElementById('stock-info');
        const selectedOption = select.options[select.selectedIndex];
        const available = selectedOption.dataset.available ?? 0;
        stockInfo.textContent = 'Stok tersedia: ' + available;
    }

    document.addEventListener('DOMContentLoaded', updateStock);
</script>
@endsection