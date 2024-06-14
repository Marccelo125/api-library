<?php

namespace App\Services;

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

     public function isAvailableForBorrowing(int $requestedQuantity)
     {

     }
}