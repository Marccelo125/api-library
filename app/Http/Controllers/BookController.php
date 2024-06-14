<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Responses\ApiResponse;
use App\Services\BookService;
use Illuminate\Http\Request;

// TODO - Adicionar uma opção de mandar o id do author pela rota também (store)

class BookController extends Controller
{
    public function index()
    {
        $books = Book::with('author')->with('categories')->get();
        return ApiResponse::success('Listando livros!', $books);
    }

    public function store(Request $request)
    {
        try {
            $validatedRequest = BookService::validateRequest($request);
            $newBook = Book::create($validatedRequest);

            if ($request->categories) {
                $newBook->categories()->attach($request->categories);
            }

            $newBook->save();

            return ApiResponse::success('Livro criado com sucesso!', [$newBook]);
        } catch (\Throwable $th) {
            return ApiResponse::fail('Não foi possível criar o livro!', [$th->getMessage()]);
        }
    }

    public function show(string $id)
    {
        try {
            $book = Book::with('author')->FindOrFail($id);
            return ApiResponse::success('Livro encontrado com sucesso!', [$book]);
        } catch (\Throwable $th) {
            return ApiResponse::fail('Não foi possível encontrar o livro!', [$th->getMessage()]);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $validatedRequest = BookService::validateRequest($request);

            $book = Book::findOrFail($id);
            $book->update($validatedRequest);

            if ($request->categories) {
                $book->categories()->sync($request->categories);
            } else {
                $book->categories()->deatch();
            }

            $book->refresh();

            return ApiResponse::success('Livro atualizado com sucesso!', [$book]);
        } catch (\Throwable $th) {
            return ApiResponse::fail('Não foi possível atualizar este livro!', [$th->getMessage()]);
        }
    }

    public function destroy(string $id)
    {
        try {
            $book = Book::FindOrFail($id);
            $book->delete();

            return apiResponse::success('Livro deletado com sucesso!', [$book]);
        } catch (\Throwable $th) {
            return ApiResponse::fail('Não foi possível deletar o livro!', [$th->getMessage()]);
        }
    }
}
