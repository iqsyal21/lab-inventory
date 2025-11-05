<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();

            // Relasi ke items & employees
            $table->foreignId('item_id')
                ->constrained('items')
                ->onDelete('cascade');

            $table->foreignId('employee_id')
                ->constrained('employees')
                ->onDelete('cascade');

            // Detail peminjaman
            $table->date('loan_date');
            $table->date('expected_return_date')->nullable();
            $table->date('actual_return_date')->nullable();

            $table->enum('status', ['Dipinjam', 'Dikembalikan', 'Hilang'])
                ->default('Dipinjam');

            $table->text('condition_after')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            // pastikan engine InnoDB
            $table->engine = 'InnoDB';
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
