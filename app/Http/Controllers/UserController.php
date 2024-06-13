<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index()
    {
        try {
            $users = User::all();
            return response()->json(['success' => true, 'msg' => 'Listando usuários', 'data' => $users], 200);
        } catch (\Throwable $th) {
            Log::error('Erro ao tentar listar usuários', ['error' => $th->getMessage()]);
            return response()->json(['success' => false, 'msg' => 'Não foi possível listar os usuários'], 400);
        };
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6',
                'number' => 'nullble|string|max:255',
            ], [
                'required' => 'O campo :attribute é obrigatório!',
                'max' => 'O campo :attribute deve ter no maximo :max caracteres!',
                'email' => 'Você precisa fornecer um email válido!',
                'unique' => 'Este email ja foi cadastrado!',
                'password.min' => 'Sua senha precisa ter no minimo 6 caractéres!',
            ]);

            $newUser = User::create($request->only(['name', 'email', 'password', 'number']));
            return response()->json(['success' => true, 'msg' => 'Usuário criando com sucesso!', 'data' => $newUser], 200);
        } catch (\Throwable $th) {
            Log::error('Erro ao tentar cadastrar usuário', ['error' => $th->getMessage()]);
            return response()->json(['success' => false, 'msg' => 'Não foi possivel criar o usuário'], 400);
        }
    }

    public function show(string $id)
    {
        try {
            $user = User::FindOrFail($id);
            return response()->json(['success' => true, 'msg' => 'Usuário encontrado!', 'data' => $user], 200);
        } catch (\Throwable $th) {
            Log::error('Erro ao tentar encontrar usuário', ['error' => $th->getMessage()]);
            return response()->json(['success' => false, 'msg' => 'Não foi possivel encontrar o usuário'], 400);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json(['success' => false, 'msg' => 'Usuário não encontrado', 'data' => null]);
            }

            if (!$request->has('name') && !$request->has('email') && !$request->has('password')) {
                return response()->json(['success' => false, 'msg' => 'Nenhum dado foi informado para atualizar', 'data' => null]);
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

            return response()->json(['success' => true, 'msg' => 'Usuário atualizado!', 'data' => $user], 200);
        } catch (\Throwable $th) {
            Log::error('Erro ao tentar atualizar o usuário', ['error' => $th->getMessage()]);
            return response()->json(['success' => false, 'msg' => 'Não foi possivel atualizar o usuário'], 400);
        }
    }

    public function destroy(string $id)
    {
        try {
            $user = User::FindOrFail($id);
            $user->delete();
            return response()->json(['success' => true, 'msg' => 'Usuário deletado com sucesso', 'data' => $user], 200);
        } catch (\Throwable $th) {
            Log::error('Erro ao tentar deletar o usuário', ['error' => $th->getMessage()]);
            return response()->json(['success' => false, 'msg' => 'Não foi possivel deletar o usuário'], 400);
        }
    }
}
