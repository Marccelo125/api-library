<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $fillable = [
        'name'
    ];

    // RELATIONSHIPS METHODS
    public function books() {
        return $this->HasMany(Book::class);
    }

}
