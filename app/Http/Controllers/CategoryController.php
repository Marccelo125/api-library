<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Responses\ApiResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return ApiResponse::success('Listando categorias!', [$categories]);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required'
            ], [
                'required'=> 'O campo :attribute é obrigatório!'
            ]);

            $category = Category::create($request->only(['name']));
            return ApiResponse::success('Categoria criada com sucesso', [$category]);
        } catch (\Exception $e) {
            return ApiResponse::fail('Não foi possível criar a categoria', [$e->getMessage()]);
        }
    }

    public function show(string $id)
    {
        try {
            $category = Category::findOrfail($id);
            return ApiResponse::success('Livro encontrado com sucesso!', [$category]);
        } catch (\Throwable $th) {
            return ApiResponse::fail('Não foi possível encontrar a categoria', [$th->getMessage()]);
        }
    }

    public function update(Request $request, int $id)
    {
        try {
            $request->validade([
                'name' => 'required'
            ], [
                'required'=> 'O campo :attribute é obrigatório!'
            ]);

            $category = Category::findOrFail($id)->update($request->only(['name']));
            $category->save();
            
            return ApiResponse::success('Categoria atualizada com sucesso', [$category]);
        } catch (\Exception $e) {
            return ApiResponse::fail('Não foi possível atualizar a categoria', [$e->getMessage()]);
        }
    }

    public function destroy(string $id)
    {
        try {
            $category = Category::findOrfail($id);
            $category->delete();

            return ApiResponse::success('Categoria deletada com sucesso!', [$category]);
        } catch (\Throwable $th) {
            return ApiResponse::fail('Não foi possível deletar a categoria', [$th->getMessage()]);
        }
    }
}
