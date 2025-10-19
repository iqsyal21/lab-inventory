@extends('layouts.app')

@section('title', 'Edit Pengembalian')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Edit Pengembalian Barang</h2>
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

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <form action="{{ route('returns.update', $return->id) }}" method="POST">
            @csrf
            @method('PUT')

            <input type="hidden" name="loan_id" value="{{ $return->loan_id }}">

            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Barang</label>
                <input type="text" class="form-control" value="{{ $return->loan->item->name }}" readonly>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tanggal Peminjaman</label>
                    <input type="text" class="form-control" value="{{ $return->loan->loan_date }}" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Jumlah Dipinjam</label>
                    <input type="number" class="form-control" value="{{ $return->loan->quantity }}" readonly>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="return_date" class="form-label fw-semibold">Tanggal Pengembalian</label>
                    <input type="date" id="return_date" name="return_date"
                        class="form-control"
                        value="{{ old('return_date', \Carbon\Carbon::parse($return->return_date)->format('Y-m-d')) }}" required>
                </div>

                <div class="col-md-6">
                    <label for="condition" class="form-label fw-semibold">Kondisi Setelah Dikembalikan</label>
                    <select id="condition" name="condition" class="form-select" required>
                        <option value="">Pilih Kondisi</option>
                        <option value="Baik" {{ old('condition', $return->condition) == 'Baik' ? 'selected' : '' }}>Baik</option>
                        <option value="Rusak" {{ old('condition', $return->condition) == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                        <option value="Hilang" {{ old('condition', $return->condition) == 'Hilang' ? 'selected' : '' }}>Hilang</option>
                    </select>
                </div>
            </div>

            <div class="mb-4">
                <label for="notes" class="form-label fw-semibold">Catatan (Opsional)</label>
                <textarea id="notes" name="notes" class="form-control" rows="3" placeholder="Tambahkan keterangan tambahan...">{{ old('notes', $return->notes) }}</textarea>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-primary px-4">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection