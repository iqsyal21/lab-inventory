<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_code',
        'name',
        'department',
        'position',
        'phone',
        'email',
    ];

    /**
     * Relasi ke Loan
     * Satu karyawan bisa memiliki banyak peminjaman
     */
    public function loans()
    {
        return $this->hasMany(Loan::class);
    }
}
