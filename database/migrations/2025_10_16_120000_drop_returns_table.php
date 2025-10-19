<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('returns')) {
            Schema::drop('returns');
        }
    }

    public function down(): void
    {
        // Optional: re-create the table structure if needed
        if (!Schema::hasTable('returns')) {
            Schema::create('returns', function (Blueprint $table) {
                $table->id();
                $table->foreignId('loan_id')->constrained('loans')->onDelete('cascade');
                $table->date('return_date')->nullable();
                $table->string('condition_after')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }
};


