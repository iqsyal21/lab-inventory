@extends('layouts.app')

@section('title', 'Edit Karyawan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Edit Karyawan</h2>
    <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">
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

<form action="{{ route('employees.update', $employee->id) }}" method="POST" class="p-4 border rounded bg-light shadow-sm">
    @csrf
    @method('PUT')

    <div class="row mb-3">
        <div class="col-md-6">
            <label for="employee_code" class="form-label fw-semibold">Kode Karyawan</label>
            <input type="text" id="employee_code" name="employee_code"
                class="form-control" value="{{ old('employee_code', $employee->employee_code) }}" required>
        </div>
        <div class="col-md-6">
            <label for="name" class="form-label fw-semibold">Nama Lengkap</label>
            <input type="text" id="name" name="name"
                class="form-control" value="{{ old('name', $employee->name) }}" required>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <label for="department" class="form-label fw-semibold">Departemen</label>
            <input type="text" id="department" name="department"
                class="form-control" value="{{ old('department', $employee->department) }}">
        </div>
        <div class="col-md-6">
            <label for="position" class="form-label fw-semibold">Jabatan</label>
            <input type="text" id="position" name="position"
                class="form-control" value="{{ old('position', $employee->position) }}">
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <label for="phone" class="form-label fw-semibold">No. Telepon</label>
            <input type="text" id="phone" name="phone"
                class="form-control" value="{{ old('phone', $employee->phone) }}">
        </div>
        <div class="col-md-6">
            <label for="email" class="form-label fw-semibold">Email</label>
            <input type="email" id="email" name="email"
                class="form-control" value="{{ old('email', $employee->email) }}">
        </div>
    </div>

    <div class="text-end">
        <button type="submit" class="btn btn-primary px-4">
            Perbarui
        </button>
    </div>
</form>
@endsection