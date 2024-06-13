<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $with = [
        'author',
        'categories',
    ];

    protected $fillable = [
        'title',
        'description',
        'author_id',
        'quantity',
        'available',
        'categories',
    ];

    /**
     * Check if a book is available for borrowing.
     *
     * @param int $requestedQuantity The requested quantity to borrow.
     * @return bool Returns true if the book is available, false otherwise.
     */
    public function isAvailableForBorrowing(int $requestedQuantity): bool
    {
        if (!($this->quantity >= $requestedQuantity) || !$this->available) {
            return false;
        }
        if (($this->quantity - $requestedQuantity) == 0) {
            $this->available = false;
        }
        return true;
    }

    public function reduceQuantity(int $requestedQuantity)
    {
        $this->decrement('quantity', $requestedQuantity);
        $this->save();
    }

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function borrow()
    {
        return $this->belongsTo(Borrowing::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
