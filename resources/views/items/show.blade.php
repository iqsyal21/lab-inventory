@extends('layouts.app')

@section('title', 'Detail Barang')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Detail Barang</h2>
    <div class="d-flex gap-2">
        <a href="{{ route('items.edit', $item->id) }}" class="btn btn-primary">
            Edit Barang
        </a>
        <a href="{{ route('items.index') }}" class="btn btn-outline-secondary">
            ← Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Informasi Barang</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Kode Barang</label>
                            <p class="form-control-plaintext">{{ $item->code }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Barang</label>
                            <p class="form-control-plaintext">{{ $item->name }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Jumlah Total</label>
                            <p class="form-control-plaintext">{{ $item->quantity_total }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Jumlah Tersedia</label>
                            <p class="form-control-plaintext">
                                @if ($item->quantity_available > 0)
                                <span class="badge bg-success">{{ $item->quantity_available }}</span>
                                @else
                                <span class="badge bg-danger">0</span>
                                @endif
                            </p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Kondisi</label>
                            <p class="form-control-plaintext">
                                @if ($item->condition === 'Baik')
                                <span class="badge bg-success">Baik</span>
                                @else
                                <span class="badge bg-warning text-dark">Rusak</span>
                                @endif
                            </p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Lokasi</label>
                            <p class="form-control-plaintext">{{ $item->location ?? '-' }}</p>
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
                    <p class="form-control-plaintext">{{ $item->created_at->format('d M Y H:i') }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Terakhir Diperbarui</label>
                    <p class="form-control-plaintext">{{ $item->updated_at->format('d M Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
