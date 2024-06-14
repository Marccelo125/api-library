<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Borrowing extends Model
{
    protected $fillable = [
        'user_id',
        'book_id',
        'quantity',
        'borrow_date',
        'return_date',
    ];

    // RELATIONSHIPS METHODS
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->HasMany(Book::class);
    }
}
