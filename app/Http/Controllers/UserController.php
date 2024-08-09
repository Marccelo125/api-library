<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Responses\ApiResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        try {
            $users = User::all();
            return ApiResponse::success('Usuários listados com sucesso!', [$users]);
        } catch (\Throwable $th) {
            return ApiResponse::fail('Não foi possível listar os usuários', [$th->getMessage()]);
        };
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6',
            ], [
                'required' => 'O campo :attribute é obrigatório!',
                'max' => 'O campo :attribute deve ter no maximo :max caracteres!',
                'email' => 'Você precisa fornecer um email válido!',
                'unique' => 'Este email ja foi cadastrado!',
                'password.min' => 'Sua senha precisa ter no minimo 6 caractéres!',
            ]);

            $newUser = User::create($request->only(['name', 'email', 'password', 'number']));
            return ApiResponse::success('Usuário criado com sucesso!', [$newUser]);
        } catch (\Throwable $th) {
            return ApiResponse::fail('Não foi possível criar o usuário', [$th->getMessage()]);
        }
    }

    public function show(string $id)
    {
        try {
            $user = User::FindOrFail($id);
            return ApiResponse::success('Usuário encontrado com sucesso!', [$user]);
        } catch (\Throwable $th) {
            return ApiResponse::fail('Não foi possível encontrar o usuário', [$th->getMessage()]);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return ApiResponse::fail('Usuário não encontrado', [null]);
            }

            if (!$request->has('name') && !$request->has('email') && !$request->has('password') && !$request->has('number')) {
                return ApiResponse::fail('Nenhum dado foi informado para atualizar', [null]);
            }

            $request->validate([
                'name' => 'nullable|string|max:255',
                'email' => 'nullable|email|unique:users,email,' . $user->id,
                'password' => 'nullable|string|min:6',
                'number' => 'nullable|string|max:255',
            ], [
                'max' => 'O campo :attribute deve ter no maximo :max caracteres!',
                'email' => 'Você precisa fornecer um email válido!',
                'unique' => 'Este email ja foi cadastrado!',
                'password.min' => 'Sua senha precisa ter no minimo 6 caractéres!'
            ]);

            $user->update($request->only(['name', 'email', 'password', 'number']));
            $user->save();

            return ApiResponse::success('Usuário atualizado com sucesso!', [$user]);
        } catch (\Throwable $th) {
            return ApiResponse::fail('Não foi possível atualizar o usuário', [$th->getMessage()]);
        }
    }

    public function destroy(string $id)
    {
        try {
            $user = User::FindOrFail($id);
            $user->delete();
            return ApiResponse::success('Usuário deletado com sucesso!', [$user]);
        } catch (\Throwable $th) {
            return ApiResponse::fail('Não foi possível deletar o usuário', [$th->getMessage()]);
        }
    }
}
