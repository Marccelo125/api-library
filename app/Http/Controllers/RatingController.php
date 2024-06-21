<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Responses\ApiResponse;
use Illuminate\Http\Request;
use Number;

class RatingController extends Controller
{
    // TODO - adicionar uma verificação se aquele usuario fez uma borrowing neste livro
    // só assim ele vai poder fazer uma avaliação
    public function index()
    {
        try {
            $ratings = Rating::all();
            return ApiResponse::success('Listando avaliações', [$ratings]);
        } catch (\Throwable $th) {
            return ApiResponse::fail('Não foi possível retornar as avaliacoes.', [$th->getMessage()]);
        }
    }

    public function store(Request $request)
    {
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

            $ratingValue = (float) str_replace(',', '.', $request->input('rating'));
            if ($ratingValue > 5 || $ratingValue < 0) {
                return ApiResponse::fail('A avaliação deve ter valor inteiro entre 0 e 5.', [null]);
            }

            $rating = Rating::create($request->only(['rating', 'comment', 'user_id', 'book_id']));
            return ApiResponse::success('Avaliação criada com sucesso.', [$rating]);
        } catch (\Throwable $th) {
            return ApiResponse::fail('Não foi possível criar a avaliação.', [$th->getMessage()]);
        }
    }

    public function show(int $id)
    {
        try {
            $rating = Rating::findOrFail($id);
            return ApiResponse::success('Avaliação encontrada', [$rating]);
        } catch (\Throwable $th) {
            return ApiResponse::fail('Não foi possível retornar as avaliacoes.', [$th->getMessage()]);
        }
    }

    public function update(Request $request, int $id)
    {
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

            if ($alreadyRated) return ApiResponse::fail('Avaliação ja foi atribuida.', [null]);

            if ($request->rating > 5 || $request->rating < 0) return ApiResponse::fail('A avaliação deve ter valor inteiro entre 0 e 5.', [null]);

            $ratingValue = (float) str_replace(',', '.', $request->input('rating'));
            if ($ratingValue > 5 || $ratingValue < 0) return ApiResponse::fail('A avaliação deve ter valor inteiro entre 0 e 5.', [null]);

            $rating = Rating::findOrFail($id);
            $rating->update($request->only(['rating', 'comment', 'user_id', 'book_id']));
            return ApiResponse::success('Avaliação atualizada com sucesso.', [$rating]);
        } catch (\Throwable $th) {
            return ApiResponse::fail('Não foi possível atualizar a avaliação.', [$th->getMessage()]);
        }
    }

    public function destroy(Rating $rating)
    {
        try {
            $rating->delete();
            return ApiResponse::success('Avaliação excluída com sucesso.', [null]);
        } catch (\Throwable $th) {
            return ApiResponse::fail('Não foi possível excluir a avaliação.', [$th->getMessage()]);
        }
    }
}
