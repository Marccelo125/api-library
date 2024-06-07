<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function index()
    {
        $authors = Author::with('books')->get();
        return response()->json(['success' => true, 'msg' => 'Listando autores', 'data' => $authors]);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                    'name' => 'required|string'
                ], [
                    'required' => 'O campo :attribute é obrigatório',
                    'string' => 'O campo :attribute está tem que ser uma string',
                ]
            );

            $newAuthor = Author::create($request->only(['name']));

            return response()->json(['success' => true, 'msg' => 'Autor(a) criado com sucesso', 'data' => $newAuthor], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'msg' => 'Não foi possível criar um autor(a)', 'data' => $th->getMessage()], 400);
        }
    }

    public function show(string $id)
    {
        try {
            $author = Author::with('books')->FindOrFail($id);
            return response()->json(['success' => true, 'msg' => 'Autor(a) Encontrado!', 'data' => $author], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'msg' => 'Não foi possível encontrar o autor(a)!', 'data' => $th->getMessage()], 400);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                    'name' => 'required|string'
                ], [
                    'required' => 'O campo :attribute é obrigatório',
                    'string' => 'O campo :attribute tem que ser do tipo string',
                ]
            );

            $author = Author::findOrFail($id);
            $author->update($request->only(['name']));
            $author->save();

            return response()->json(['success' => true, 'msg' => 'Autor(a) atualizado com sucesso!', 'data' => $author], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'msg' => 'Não foi possível atualizar este autor(a)!', 'data' => $th->getMessage()], 400);
        }
    }

    public function destroy(string $id)
    {
        try {
            $author = Author::findOrFail($id);
            $author->delete();

            return response()->json(['success' => true, 'msg' => 'Autor(a) deletado com sucesso!', 'data' => $author], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'msg' => 'Não foi possível deletar este autor(a)!', 'data' => $th->getMessage()], 400);
        }
    }
}
