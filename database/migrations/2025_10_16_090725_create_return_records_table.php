<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration.
     */
    public function up(): void
    {
        Schema::create('return_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_id')->constrained()->onDelete('cascade');
            $table->date('return_date');
            $table->integer('quantity_returned')->default(0);
            $table->string('condition')->nullable(); // misal: Baik, Rusak, Hilang
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }


    /**
     * Balikkan migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_records');
    }
};
