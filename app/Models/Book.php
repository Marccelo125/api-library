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
        'total_pages',
        'language',
        'publish_date',
        'publisher'
    ];
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

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }
}
