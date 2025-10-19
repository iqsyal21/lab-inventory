<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'borrower_name',
        'borrower_role',
        'borrower_department',
        'quantity',
        'loan_date',
        'return_date',
        'status',
    ];

    protected $casts = [
        'loan_date' => 'date',
        'return_date' => 'date',
    ];

    // Relasi ke item (barang)
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    // Relasi ke record pengembalian (bisa banyak kali)
    public function returnRecords()
    {
        return $this->hasMany(ReturnRecord::class, 'loan_id');
    }

    // Akses total barang yang sudah dikembalikan
    public function getTotalReturnedAttribute()
    {
        return $this->returnRecords()->sum('quantity_returned');
    }

    // Akses sisa pinjaman yang belum dikembalikan
    public function getRemainingQuantityAttribute()
    {
        return max(0, $this->quantity - $this->total_returned);
    }
}
