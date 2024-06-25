<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Responses\ApiResponse;
use App\Services\RatingService;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    private RatingService $ratingService;
    public function __construct(RatingService $ratingService)
    {
        $this->ratingService = $ratingService;
    }
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
            $this->ratingService->validateRequest($request);

            $alreadyRated = $this->ratingService->alreadyRated($request->book_id, $request->user_id);
            if ($alreadyRated) {
                return ApiResponse::fail('Avaliação ja foi atribuida.', [null]);
            }

            $ratingValue = $this->ratingService->dotToComma($request->input('rating'));

            if ($ratingValue > 5 || $ratingValue < 0) {
                return ApiResponse::fail('A avaliação deve ter valor inteiro entre 0 e 5.', [null]);
            }

            $rating = Rating::create([
                'rating' => $ratingValue,
                'comment' => $request->input('comment'),
                'user_id' => $request->input('user_id'),
                'book_id' => $request->input('book_id'),
            ]);
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
        // Verificar se existe uma avaliação com esse id de livro e user mas que seja diferente do id da rota
        try {
            $this->ratingService->validateRequest($request);

            $rating = Rating::findOrFail($id);

            $ratingValue = $this->ratingService->dotToComma($request->input('rating'));
            $request->merge(['rating' => $ratingValue]);

            $alreadyRated = $this->ratingService->alreadyRated($request->book_id, $request->user_id);

            if ($alreadyRated && $id !== $rating->id) {
                return ApiResponse::fail('Avaliação ja foi atribuida.', [null]);
            };

            if ($ratingValue > 5 || $ratingValue < 0) {
                return ApiResponse::fail('A avaliação deve ter valor inteiro entre 0 e 5.', [null]);
            };

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
