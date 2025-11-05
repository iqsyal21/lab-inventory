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
            <select name="item_id" id="item_id" class="form-select" required>
                <option value="">-- Pilih Barang --</option>
                @foreach ($items as $item)
                <option value="{{ $item->id }}" {{ old('item_id', $loan->item_id) == $item->id ? 'selected' : '' }}>
                    {{ $item->code }} - {{ $item->name }}
                </option>
                @endforeach
            </select>
        </div>

        {{-- Pilih Karyawan --}}
        <div class="col-md-6">
            <label for="employee_id" class="form-label fw-semibold">Peminjam (Karyawan)</label>
            <select name="employee_id" id="employee_id" class="form-select" required>
                <option value="">-- Pilih Karyawan --</option>
                @foreach ($employees as $employee)
                <option value="{{ $employee->id }}" {{ old('employee_id', $loan->employee_id) == $employee->id ? 'selected' : '' }}>
                    {{ $employee->name }} ({{ $employee->department }})
                </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="row mb-3">
        {{-- Tanggal Peminjaman --}}
        <div class="col-md-6">
            <label for="loan_date" class="form-label fw-semibold">Tanggal Peminjaman</label>
            <input
                type="date"
                id="loan_date"
                name="loan_date"
                class="form-control"
                value="{{ old('loan_date', \Carbon\Carbon::parse($loan->loan_date)->format('Y-m-d')) }}"
                required>
        </div>

        {{-- Tanggal Pengembalian (Rencana) --}}
        <div class="col-md-6">
            <label for="expected_return_date" class="form-label fw-semibold">Tanggal Pengembalian (Rencana)</label>
            <input
                type="date"
                id="expected_return_date"
                name="expected_return_date"
                class="form-control"
                value="{{ old('expected_return_date', optional($loan->expected_return_date)->format('Y-m-d')) }}"
                required>
        </div>
    </div>

    <div class="row mb-3">
        {{-- Tanggal Pengembalian Aktual --}}
        <div class="col-md-6">
            <label for="actual_return_date" class="form-label fw-semibold">Tanggal Pengembalian (Aktual)</label>
            <input
                type="date"
                id="actual_return_date"
                name="actual_return_date"
                class="form-control"
                value="{{ old('actual_return_date', optional($loan->actual_return_date)->format('Y-m-d')) }}">
        </div>

        {{-- Status --}}
        <div class="col-md-6">
            <label for="status" class="form-label fw-semibold">Status</label>
            <select name="status" id="status" class="form-select" required>
                @php
                $statuses = ['Dipinjam', 'Dikembalikan', 'Hilang', 'Rusak'];
                @endphp
                @foreach ($statuses as $status)
                <option value="{{ $status }}" {{ old('status', $loan->status) == $status ? 'selected' : '' }}>
                    {{ $status }}
                </option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Kondisi Barang Setelah Pengembalian --}}
    <div class="mb-3">
        <label for="condition_after" class="form-label fw-semibold">Kondisi Barang Setelah Pengembalian</label>
        <input
            type="text"
            id="condition_after"
            name="condition_after"
            class="form-control"
            placeholder="Contoh: Masih baik, layar retak, dll."
            value="{{ old('condition_after', $loan->condition_after) }}">
    </div>

    {{-- Catatan --}}
    <div class="mb-3">
        <label for="notes" class="form-label fw-semibold">Catatan</label>
        <textarea
            id="notes"
            name="notes"
            class="form-control"
            rows="3"
            placeholder="Keterangan tambahan">{{ old('notes', $loan->notes) }}</textarea>
    </div>

    <div class="text-end">
        <button type="submit" class="btn btn-primary px-4">
            Perbarui
        </button>
    </div>
</form>
@endsection