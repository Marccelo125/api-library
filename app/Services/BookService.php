<?php

namespace App\Services;

use App\Models\Book;

class BookService
{
    public static function validateRequest($request)
    {
        $data = $request->validate(
            [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'author_id' => 'required|int',
                'quantity' => 'required|int',
                'available' => 'nullable|int',
                'total_pages' => 'required|int',
                'language' => 'required|string',
                'publish_date' => 'nullable|date',
                'publisher' => 'required|string'
            ],
            [
                'required' => 'O campo :attribute é obrigatório',
                'max' => 'O campo :attribute tem que ser 255 caracteres',
                'string' => 'O campo :attribute tem que ser do tipo string',
                'int' => 'O campo :attribute tem que ser do tipo integer',
                'exists' => 'O campo :attribute não existe',
                'date' => 'O campo :attribute precisa ser do tipo Date'
            ]
        );
        return $data;
    }

    public function isAvailableForBorrowing(Book $book, int $requestedQuantity): bool
    {
        if ($book->quantity < $requestedQuantity || !$book->available) {
            return false;
        }

        $newQuantity = $book->quantity - $requestedQuantity;

        if ($newQuantity == 0) {
            $book->available = false;
            $book->save();
        }

        return true;
    }

    public static function isBookAuthorPublished($requestTitle, $requestAuthorId)
    {
        $book = Book::where('author_id', $requestAuthorId)->where('title', $requestTitle)->first();
        return $book ? true : false;
    }

    public function reduceQuantity(Book $book, int $requestedQuantity)
    {
        $book->decrement('quantity', $requestedQuantity);
        $book->save();
    }
}
