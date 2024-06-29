<?php

namespace App\Services;

use App\Models\Borrowing;
use App\Models\Rating;

class RatingService
{

    public static function validateRequest($request)
    {
        $data = $request->validate([
            'rating' => 'required|string',
            'comment' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
        ], [
            'required' => 'O campo :attribute é obrigatório',
            'exists' => 'O dado no campo :attribute não existe',
            'string' => 'O campo :attribute deve ser uma string',
        ]);

        return $data;
    }
    public static function alreadyRated($book_id, $user_id)
    {
        return Rating::where('user_id', $user_id)->where('book_id', $book_id)->exists();
    }

    public static function dotToComma($ratingValue) {
        $ratingFixed = str_replace('.', ',', $ratingValue);
        return $ratingFixed;
    }

    public static function hasInBorrows($user_id, $book_id): bool
    {
        $borrow = Borrowing::where('user_id', $user_id)->where('book_id', $book_id)->exists();
        if ($borrow) {
            return true;
        }
        return false;
    }
}
