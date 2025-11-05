@extends('layouts.app')

@section('title', 'Detail Karyawan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Detail Karyawan</h2>
    <div class="d-flex gap-2">
        <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-primary">Edit</a>
        <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">← Kembali</a>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Informasi Karyawan</h5>
    </div>
    <div class="card-body">
        <dl class="row mb-0">
            <dt class="col-sm-4">Kode Karyawan</dt>
            <dd class="col-sm-8">{{ $employee->employee_code }}</dd>

            <dt class="col-sm-4">Nama Lengkap</dt>
            <dd class="col-sm-8">{{ $employee->name }}</dd>

            <dt class="col-sm-4">Departemen</dt>
            <dd class="col-sm-8">{{ $employee->department ?? '-' }}</dd>

            <dt class="col-sm-4">Jabatan</dt>
            <dd class="col-sm-8">{{ $employee->position ?? '-' }}</dd>

            <dt class="col-sm-4">Nomor Telepon</dt>
            <dd class="col-sm-8">{{ $employee->phone ?? '-' }}</dd>

            <dt class="col-sm-4">Email</dt>
            <dd class="col-sm-8">{{ $employee->email ?? '-' }}</dd>

            <dt class="col-sm-4">Dibuat pada</dt>
            <dd class="col-sm-8">{{ $employee->created_at->format('d M Y H:i') }}</dd>

            <dt class="col-sm-4">Diperbarui pada</dt>
            <dd class="col-sm-8">{{ $employee->updated_at->format('d M Y H:i') }}</dd>
        </dl>
    </div>
</div>
@endsection