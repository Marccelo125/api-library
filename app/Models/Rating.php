<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'rating',
        'comment',
        'user_id',
        'book_id',
    ];

    public static function alreadyRated($request) {
        return self::where('user_id', $request->user_id)->where('book_id', $request->book_id)->first();
    }

    // RELATIONSHIPS METHODS
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function book() {
        return $this->belongsTo(Book::class);
    }
}
