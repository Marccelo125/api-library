<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return response()->json(['success' => true, 'msg' => 'Listando categorias', 'data' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validade([
                'name' => 'required'
            ], [
                'required'=> 'O campo :attribute é obrigatório!'
            ]);

            $category = Category::create($request->only(['name']));
            return response()->json(['success'=> true,'msg'=> 'Categoria criada com sucesso', 'data' => $category], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => 'Não foi possível criar a categoria', 'data' => $e->getMessage()], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $category = Category::findOrfail($id);
            return response()->json(['success' => true, 'msg' => 'Livro encontrado com sucesso!', 'data' => $category], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'msg' => 'Não foi possível encontrar a categoria', 'data' => $th->getMessage()], 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        try {
            $request->validade([
                'name' => 'required'
            ], [
                'required'=> 'O campo :attribute é obrigatório!'
            ]);

            $category->update($request->only(['name']));
            $category->save();
            
            return response()->json(['success'=> true,'msg'=> 'Categoria atualizada com sucesso', 'data' => $category], 200);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => 'Não foi possível atualizar a categoria', 'data' => $e->getMessage()], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $category = Category::findOrfail($id);
            $category->delete();
            return response()->json(['success' => true, 'msg' => 'Categoria deletada com sucesso!', 'data' => $category], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'msg' => 'Não foi possível deletar a categoria', 'data' => $th->getMessage()], 400);
        }
    }
}
