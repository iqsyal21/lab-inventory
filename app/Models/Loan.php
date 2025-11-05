<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    // Daftar status yang konsisten
    const STATUS_BORROWED = 'Dipinjam';
    const STATUS_RETURNED = 'Dikembalikan';
    const STATUS_LOST = 'Hilang';
    const STATUS_DAMAGED = 'Rusak';

    protected $fillable = [
        'item_id',
        'employee_id',
        'loan_date',
        'expected_return_date',
        'actual_return_date',
        'status',
        'condition_after',
        'notes',
    ];

    protected $casts = [
        'loan_date' => 'date',
        'expected_return_date' => 'date',
        'actual_return_date' => 'date',
    ];

    /** Relasi ke Item */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    /** Relasi ke Employee */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /** Cek apakah sudah dikembalikan */
    public function isReturned()
    {
        return $this->status === self::STATUS_RETURNED;
    }

    /** Label status otomatis */
    public function getStatusLabelAttribute()
    {
        return ucfirst($this->status);
    }

    /** Scope untuk filter */
    public function scopeFilter($query, array $filters)
    {
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['search'])) {
            $query->whereHas(
                'employee',
                fn($q) =>
                $q->where('name', 'like', "%{$filters['search']}%")
            )->orWhereHas(
                'item',
                fn($q) =>
                $q->where('name', 'like', "%{$filters['search']}%")
            );
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('loan_date', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('loan_date', '<=', $filters['date_to']);
        }
    }
}
