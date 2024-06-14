<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Responses\ApiResponse;
use Illuminate\Http\Request;
use Number;

class RatingController extends Controller
{
    // TODO - CRUD rating
    // TODO - Converter returns to apiResponse
    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        // TODO - Validar a pontuação do rating, se é apenas . ou ,
        try {

            $request->validate([
                'rating' => 'required|string',
                'comment' => 'required|string',
                'user_id' => 'required|exists:users,id',
                'book_id' => 'required|exists:books,id',
            ], [
                'required' => 'O campo :attribute é obrigatório',
                'exists' => 'O dado no campo :attribute não existe',
                'string' => 'O campo :attribute deve ser uma string',
            ]);
    
            $alreadyRated = Rating::alreadyRated($request);
    
            if ($alreadyRated) {
                return ApiResponse::fail('Avaliação ja foi atribuida.', [null]);
            }
    
            if ($request->rating > 5 || $request->rating < 0) {
                return ApiResponse::fail('A avaliação deve ter valor inteiro entre 0 e 5.', [null]);
            }
    
            if (strpos((string)$request->rating, '.') !== false) {
                $request->rating = str_replace('.', ',', (string)$request->rating);
            }
    
            $rating = Rating::create($request->only(['rating', 'comment', 'user_id', 'book_id']));
            return ApiResponse::success('Avaliação criada com sucesso.', [$rating]);
        } catch (\Throwable $th) {
            return ApiResponse::fail('Não foi possível criar a avaliação.', [$th->getMessage()]);
        }
    }

    public function show(Rating $rating)
    {
        //
    }

    public function update(Request $request, Rating $rating)
    {
        //
    }

    public function destroy(Rating $rating)
    {
        //
    }
}
