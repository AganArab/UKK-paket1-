<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $borrower_id
 * @property \Carbon\Carbon $borrow_date
 * @property \Carbon\Carbon $expected_return_date
 * @property string $status
 * @property int|null $approved_by
 */
/**
 * @property int $id
 * @property int $borrower_id
 * @property \Illuminate\Support\Carbon $borrow_date
 * @property \Illuminate\Support\Carbon $expected_return_date
 * @property string $status
 * @property int|null $approved_by
 */
class Borrowing extends Model
{
    protected $fillable = ['borrower_id', 'borrow_date', 'expected_return_date', 'status', 'approved_by'];

    protected $casts = [
        'borrow_date' => 'date',
        'expected_return_date' => 'date',
    ];

    public function borrower()
    {
        return $this->belongsTo(User::class, 'borrower_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function borrowingDetails()
    {
        return $this->hasMany(BorrowingDetail::class);
    }

    public function returnRecord()
    {
        return $this->hasOne(ReturnModel::class);
    }

    public function fines()
    {
        return $this->hasMany(Fine::class);
    }
}
