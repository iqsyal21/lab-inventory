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
                        {{-- Barang --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Barang</label>
                            <p class="form-control-plaintext">{{ $loan->item->name ?? '-' }}</p>
                        </div>

                        {{-- Nama Peminjam --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Peminjam</label>
                            <p class="form-control-plaintext">{{ $loan->employee->name ?? '-' }}</p>
                        </div>

                        {{-- Departemen / Jabatan --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Departemen / Jabatan</label>
                            <p class="form-control-plaintext">
                                {{ $loan->employee->department ?? '-' }}
                                @if (!empty($loan->employee->position))
                                / {{ $loan->employee->position }}
                                @endif
                            </p>
                        </div>

                        {{-- Kondisi Setelah --}}
                        @if ($loan->condition_after)
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Kondisi Setelah Dikembalikan</label>
                            <p class="form-control-plaintext">{{ $loan->condition_after }}</p>
                        </div>
                        @endif
                    </div>

                    <div class="col-md-6">
                        {{-- Tanggal Pinjam --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tanggal Pinjam</label>
                            <p class="form-control-plaintext">
                                {{ \Carbon\Carbon::parse($loan->loan_date)->format('d M Y') }}
                            </p>
                        </div>

                        {{-- Tanggal Perkiraan Kembali --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Perkiraan Tanggal Kembali</label>
                            <p class="form-control-plaintext">
                                {{ $loan->expected_return_date ? \Carbon\Carbon::parse($loan->expected_return_date)->format('d M Y') : '-' }}
                            </p>
                        </div>

                        {{-- Tanggal Kembali Aktual --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tanggal Kembali Aktual</label>
                            <p class="form-control-plaintext">
                                {{ $loan->actual_return_date ? \Carbon\Carbon::parse($loan->actual_return_date)->format('d M Y') : '-' }}
                            </p>
                        </div>

                        {{-- Status --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Status</label>
                            <p class="form-control-plaintext">
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
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Catatan Tambahan --}}
                @if (!empty($loan->notes))
                <div class="mt-4">
                    <label class="form-label fw-semibold">Catatan</label>
                    <p class="form-control-plaintext">{{ $loan->notes }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Informasi Sistem --}}
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