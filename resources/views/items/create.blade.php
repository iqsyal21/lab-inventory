@extends('layouts.app')

@section('title', 'Tambah Barang')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Tambah Barang</h2>
    <a href="{{ route('items.index') }}" class="btn btn-outline-secondary">
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

<form action="{{ route('items.store') }}" method="POST" class="p-4 border rounded bg-light shadow-sm">
    @csrf
    <div class="row mb-3">
        <div class="col-md-6">
            <label for="code" class="form-label fw-semibold">Kode Barang</label>
            <input type="text" id="code" name="code" class="form-control" value="{{ old('code') }}" placeholder="Contoh: BRG001" required>
        </div>
        <div class="col-md-6">
            <label for="name" class="form-label fw-semibold">Nama Barang</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" placeholder="Contoh: Keyboard Logitech K120" required>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <label for="quantity_total" class="form-label fw-semibold">Jumlah Barang</label>
            <input type="number" id="quantity_total" name="quantity_total" class="form-control" value="{{ old('quantity_total') }}" placeholder="0" min="0" required>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold d-block">Kondisi Barang</label>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="condition" id="condition_baik" value="Baik"
                    {{ old('condition', 'Baik') == 'Baik' ? 'checked' : '' }}>
                <label class="form-check-label" for="condition_baik">Baik</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="condition" id="condition_rusak" value="Rusak"
                    {{ old('condition') == 'Rusak' ? 'checked' : '' }}>
                <label class="form-check-label" for="condition_rusak">Rusak</label>
            </div>
        </div>
    </div>

    <div class="mb-4">
        <label for="location" class="form-label fw-semibold">Lokasi Penyimpanan</label>
        <input type="text" id="location" name="location" class="form-control" value="{{ old('location') }}" placeholder="Contoh: Lemari 1 / Rak Atas">
    </div>

    <div class="text-end">
        <button type="submit" class="btn btn-primary px-4">
            Simpan
        </button>
    </div>
</form>
@endsection