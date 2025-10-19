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
        'quantity_total',
        'condition',
        'location',
    ];

    // Relasi ke tabel loans (peminjaman)
    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    // Relasi ke record pengembalian lewat loan
    public function returnRecords()
    {
        return $this->hasManyThrough(ReturnRecord::class, Loan::class, 'item_id', 'loan_id');
    }

    // Total jumlah barang yang sedang dipinjam (status = Dipinjam)
    public function getQuantityBorrowedAttribute()
    {
        return $this->loans()
            ->where('status', 'Dipinjam')
            ->sum('quantity');
    }

    // Total jumlah barang yang sudah dikembalikan
    public function getQuantityReturnedAttribute()
    {
        return $this->returnRecords()->sum('quantity_returned');
    }

    // Jumlah stok yang tersedia untuk dipinjam
    public function getQuantityAvailableAttribute()
    {
        // stok tersedia = total - yang sedang dipinjam
        return max(0, $this->quantity_total - $this->quantity_borrowed);
    }
}
