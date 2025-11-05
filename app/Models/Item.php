<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'condition',
        'location',
        'description',
    ];

    /**
     * Relasi ke Loan
     * Satu barang bisa memiliki banyak riwayat peminjaman
     */
    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    /**
     * Cek apakah barang sedang dipinjam
     */
    public function isBorrowed()
    {
        return $this->loans()
            ->where('status', 'Dipinjam')
            ->exists();
    }
}
