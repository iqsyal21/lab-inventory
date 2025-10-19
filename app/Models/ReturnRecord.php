<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_id',
        'return_date',
        'quantity_returned',
        'condition',
        'notes',
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
}
