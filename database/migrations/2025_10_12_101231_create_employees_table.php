<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_code')->unique(); // kode unik karyawan
            $table->string('name');                    // nama lengkap
            $table->string('department')->nullable();  // departemen
            $table->string('position')->nullable();    // jabatan
            $table->string('phone')->nullable();       // kontak (opsional)
            $table->string('email')->nullable();       // email (opsional)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
