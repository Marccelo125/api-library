<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::with('author')->get();
        return response()->json(['success' => true, 'msg' => 'Listando livros', 'data' => $books]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                    'title' => 'required|string|max:255',
                    'description' => 'required|string|nullable',
                    'author_id' => 'required|int',
                ], [
                    'required' => 'O campo :attribute é obrigatório',
                    'max' => 'O campo :attribute tem que ser 255 caracteres',
                    'string' => 'O campo :attribute tem que ser do tipo string',
                    'int' => 'O campo :attribute tem que ser do tipo integer',
                ]
            );

            $newBook = Book::create($request->only(['title', 'description', 'author_id']));

            return response()->json(['success' => true, 'msg' => 'Livro criado com sucesso!', 'data' => $newBook], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'msg' => 'Não foi possível criar o livro!', 'data' => $th->getMessage()], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $book = Book::with('author')->FindOrFail($id);
            return response()->json(['success' => true, 'msg' => 'Livro encontrado com sucesso!', 'data' => $book], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'msg' => 'Não foi possível encontrar o livro!', 'data' => $th->getMessage()], 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                    'title' => 'required|string|max:255',
                    'description' => 'required|string|nullable',
                    'author_id' => 'required|int',
                    'quantity' => 'required|int',
                    'available' => 'required|int',
                ], [
                    'required' => 'O campo :attribute é obrigatório',
                    'max' => 'O campo :attribute tem que ser 255 caracteres',
                    'string' => 'O campo :attribute tem que ser do tipo string',
                    'int' => 'O campo :attribute tem que ser do tipo integer',
                ]
            );

            $book = Book::findOrFail($id);
            $book->update($request->only(['title', 'description', 'author_id']));
            $book->save();

            return response()->json(['success' => true, 'msg' => 'Livro atualizado com sucesso!', 'data' => $book], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'msg' => 'Não foi possível atualizar este livro!', 'data' => $th->getMessage()], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $book = Book::FindOrFail($id);
            $book->delete();

            return response()->json(['success' => true, 'msg' => 'Livro deletado com sucesso!', 'data' => $book], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'msg' => 'Não foi possível deletar este livro!', 'data' => $th->getMessage()], 400);
        }
    }
}
