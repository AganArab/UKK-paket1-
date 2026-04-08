<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $borrowing_id
 * @property \Illuminate\Support\Carbon $return_date
 * @property string $condition_returned
 * @property int $processed_by
 */
class ReturnModel extends Model
{
    protected $table = 'returns';

    protected $fillable = ['borrowing_id', 'return_date', 'condition_returned', 'processed_by'];

    protected $casts = [
        'return_date' => 'date',
    ];

    public function borrowing()
    {
        return $this->belongsTo(Borrowing::class);
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
