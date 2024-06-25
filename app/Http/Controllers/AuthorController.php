<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Responses\ApiResponse;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function index()
    {
        $authors = Author::with('books')->get();
        return ApiResponse::success('Listando autores!', [$authors]);
    }

    public function store(Request $request)
    {
        try {
            $request->validate(
                [
                    'name' => 'required|string'
                ],
                [
                    'required' => 'O campo :attribute é obrigatório',
                    'string' => 'O campo :attribute está tem que ser uma string',
                ]
            );

            $newAuthor = Author::create($request->only(['name']));

            return ApiResponse::success('Autor(a) criado com sucesso', [$newAuthor]);
        } catch (\Throwable $th) {
            return ApiResponse::fail('Não foi possível criar um autor(a)', [$th->getMessage()]);
        }
    }

    public function show(string $id)
    {
        try {
            $author = Author::with('books')->FindOrFail($id);
            return ApiResponse::success('Autor(a) Encontrado!', [$author]);
        } catch (\Throwable $th) {
            return apiResponse::fail('Não foi possível encontrar o autor(a)!', [$th->getMessage()]);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $request->validate(
                [
                    'name' => 'required|string'
                ],
                [
                    'required' => 'O campo :attribute é obrigatório',
                    'string' => 'O campo :attribute tem que ser do tipo string',
                ]
            );

            $author = Author::findOrFail($id);
            $author->update($request->only(['name']));
            $author->save();

            return ApiResponse::success('Autor(a) atualizado com sucesso!', [$author]);
        } catch (\Throwable $th) {
            return ApiResponse::fail('Não foi possível atualizar este autor(a)!', [$th->getMessage()]);
        }
    }

    public function destroy(string $id)
    {
        try {
            $author = Author::findOrFail($id);
            $author->delete();

            return ApiResponse::success('Autor(a) deletado com sucesso!', [$author]);
        } catch (\Throwable $th) {
            return ApiResponse::fail('Não foi possível deletar este autor(a)!', [$th->getMessage()]);
        }
    }
}
