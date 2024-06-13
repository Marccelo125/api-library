<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    // TODO - Adicionar uma multa de atraso para retorno
    // TODO - Adicionar campo que registra a quantidade de livros que foi emprestado
    public function index()
    {
        $borrows = Borrowing::with('user', 'book')->get();
        return response()->json([
            'success' => true,
            'message' => 'Listando todos os emprestimos',
            'data' => $borrows
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // TO DO - Adicionar campo que registra a quantidade de livros que foi emprestado
        try {
            $request->validate([
                'user_id' => 'required|int',
                'book_id' => 'required|int',
                'quantity' => 'required|int',
                'borrow_date' => 'required|date',
                'return_date' => 'required|date'
            ], [
                'required' => 'O campo :attribute é obrigatório',
                'int' => 'O campo :attribute deve ser um integer',
                'date' => 'O campo :attribute deve ser uma data',
                'exist' => 'O campo :attribute não existe'
            ]);

            $book = $request->input('book_id');
            $book = Book::findOrFail($book);
            $quantity = $request->input('quantity');
            $bookAvailable = $book->isAvailableForBorrowing($quantity);

            if ($book->available = 0) {
                return response()->json(['success' => false, 'msg' => 'Livro indisponível', 'data' => null]);
            }

            if ($bookAvailable == false) {
                return response()->json(['success' => false, 'msg' => 'Quantidade indisponível', 'data' => null]);
            }

            $borrow = Borrowing::create($request->only(['user_id', 'book_id', 'quantity','borrow_date', 'return_date']));
            $book->reduceQuantity($quantity);
            return response()->json(['success' => true, 'msg' => 'Emprestimo realizado', 'data' => $borrow]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'msg' => 'Não foi possível realizar o emprestimo', 'data' => $th->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        try {
            $borrow = Borrowing::find($id);
            return response()->json(['success' => true, 'msg' => 'Emprestimo encontrado', 'data' => $borrow]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'msg' => 'Não foi possível encontrar o emprestimo', 'data' => $th->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        try {
            $request->validate([
                'user_id' => 'required|int',
                'book_id' => 'required|int',
                'quantity' => 'required|int',
                'borrow_date' => 'required|date',
                'return_date' => 'required|date'
            ], [
                'required' => 'O campo :attribute é obrigatório',
                'int' => 'O campo :attribute deve ser um integer',
                'date' => 'O campo :attribute deve ser uma data',
                'exist' => 'O campo :attribute não existe'
            ]);

            $book = $request->input('book_id');
            $book = Book::find($book);
            $quantity = $request->input('quantity');

            if ($book->quantity <= 0 || $book->available = 0) {
                return response()->json(['success' => false, 'msg' => 'Livro indisponível', 'data' => null]);
            }

            if ($book->quantity < $quantity) {
                return response()->json(['success' => false, 'msg' => 'Quantidade indisponível', 'data' => null]);
            }

            $borrowing = Borrowing::find($id)
                ->update($request->only(['user_id', 'book_id', 'borrow_date', 'return_date']));

            return response()->json(['success' => true, 'msg' => 'emprestimo atualizado', 'data' => $borrowing]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'msg' => 'Não foi possível atualizar o emprestimo', 'data' => $th->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try {
            $borrowing = Borrowing::findOrFail($id)->delete();
            return response()->json(['success' => true, 'msg' => 'Emprestimo deletado com sucesso', 'data' => $borrowing]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'msg' => 'Não foi possível deletar o emprestimo', 'data' => $th->getMessage()]);
        }
    }
}
