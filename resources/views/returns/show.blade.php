@extends('layouts.app')

@section('title', 'Detail Pengembalian')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Detail Pengembalian</h2>
    <div class="d-flex gap-2">
        <a href="{{ route('returns.edit', $return->id) }}" class="btn btn-primary">
            Edit Pengembalian
        </a>
        <a href="{{ route('returns.index') }}" class="btn btn-outline-secondary">
            ← Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Informasi Pengembalian</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Barang</label>
                            <p class="form-control-plaintext">{{ $return->loan->item->name ?? '-' }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Peminjam</label>
                            <p class="form-control-plaintext">{{ $return->loan->borrower_name ?? '-' }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Jumlah Dikembalikan</label>
                            <p class="form-control-plaintext">{{ $return->quantity_returned }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tanggal Pinjam</label>
                            <p class="form-control-plaintext">{{ \Carbon\Carbon::parse($return->loan->loan_date)->format('d M Y') }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tanggal Kembali</label>
                            <p class="form-control-plaintext">{{ \Carbon\Carbon::parse($return->return_date)->format('d M Y') }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Kondisi</label>
                            <p class="form-control-plaintext">{{ $return->condition ?? '-' }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Catatan</label>
                            <p class="form-control-plaintext">{{ $return->notes ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Informasi Sistem</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Tanggal Dibuat</label>
                    <p class="form-control-plaintext">{{ $return->created_at->format('d M Y H:i') }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Terakhir Diperbarui</label>
                    <p class="form-control-plaintext">{{ $return->updated_at->format('d M Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
