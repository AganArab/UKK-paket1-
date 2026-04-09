<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorrowingDetail extends Model
{
    protected $fillable = ['borrowing_id', 'equipment_id', 'quantity'];

    public function borrowing()
    {
        return $this->belongsTo(Borrowing::class);
    }

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }
}
