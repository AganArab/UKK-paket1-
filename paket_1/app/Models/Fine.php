<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fine extends Model
{
    protected $fillable = ['borrowing_id', 'amount', 'reason', 'paid'];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid' => 'boolean',
    ];

    public function borrowing()
    {
        return $this->belongsTo(Borrowing::class);
    }
}
