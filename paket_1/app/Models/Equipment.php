<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int $category_id
 * @property int $stock
 * @property string $condition
 */
/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int $category_id
 * @property int $stock
 * @property string $condition
 */
class Equipment extends Model
{
    protected $fillable = ['name', 'description', 'category_id', 'stock', 'condition'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function borrowingDetails()
    {
        return $this->hasMany(BorrowingDetail::class);
    }
}
