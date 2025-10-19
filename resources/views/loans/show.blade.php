@extends('layouts.app')

@section('title', 'Detail Peminjaman')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Detail Peminjaman</h2>
    <div class="d-flex gap-2">
        <a href="{{ route('loans.edit', $loan->id) }}" class="btn btn-primary">
            Edit Peminjaman
        </a>
        <a href="{{ route('loans.index') }}" class="btn btn-outline-secondary">
            ← Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Informasi Peminjaman</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Barang</label>
                            <p class="form-control-plaintext">{{ $loan->item->name ?? '-' }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Peminjam</label>
                            <p class="form-control-plaintext">{{ $loan->borrower_name }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Peran</label>
                            <p class="form-control-plaintext">{{ $loan->borrower_role }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Prodi / Unit</label>
                            <p class="form-control-plaintext">{{ $loan->borrower_department ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Jumlah</label>
                            <p class="form-control-plaintext">{{ $loan->quantity }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tanggal Pinjam</label>
                            <p class="form-control-plaintext">{{ \Carbon\Carbon::parse($loan->loan_date)->format('d M Y') }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tanggal Kembali</label>
                            <p class="form-control-plaintext">{{ $loan->return_date ? \Carbon\Carbon::parse($loan->return_date)->format('d M Y') : '-' }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Status</label>
                            <p class="form-control-plaintext">
                                @if ($loan->status === 'Dipinjam')
                                <span class="badge bg-warning text-dark">Dipinjam</span>
                                @elseif ($loan->status === 'Dikembalikan')
                                <span class="badge bg-success">Dikembalikan</span>
                                @else
                                <span class="badge bg-danger">Hilang</span>
                                @endif
                            </p>
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
                    <p class="form-control-plaintext">{{ $loan->created_at->format('d M Y H:i') }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Terakhir Diperbarui</label>
                    <p class="form-control-plaintext">{{ $loan->updated_at->format('d M Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
