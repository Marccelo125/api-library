<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use App\Responses\ApiResponse;
use App\Services\BookService;
use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    // TODO - Adicionar uma multa de atraso para retorno
    
    public function index()
    {
        $borrows = Borrowing::with('user', 'book')->get();
        return ApiResponse::success('Listando todos os emprestimós!', [$borrows]);
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
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
            $book = Book::findOrFail($book);
            $quantity = $request->input('quantity');
    
            $bookAvailable = $book->BookService->isAvailableForBorrowing($quantity);
            $bookAvailable = $book->isAvailableForBorrowing($quantity);
            
            if ($book->available = 0) {
                return ApiResponse::fail('Livro indisponivel!', [null]);
            }
            
            if ($bookAvailable == false) {
                return ApiResponse::fail('Quantidade indisponivel!', [null]);
            }

            $borrow = Borrowing::create($request->only(['user_id', 'book_id', 'quantity','borrow_date', 'return_date']));
            $book->reduceQuantity($quantity);

            return ApiResponse::success('Empréstimo realizado com sucesso!', [$borrow]);
        } catch (\Throwable $th) {
            return ApiResponse::fail('Não foi possível realizar o emprestimo!', [$th->getmessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        try {
            $borrow = Borrowing::find($id);
            return ApiResponse::success('Empréstimo encontrado!', [$borrow]);
        } catch (\Throwable $th) {
            return ApiResponse::fail('Não foi possível encontrar o empréstimo', [$th->getMessage()]);
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

            if ($request->available = 0 && $book->available = 0) {
                return ApiResponse::fail('Livro já indisponível', [null]);
            }
            
            if ($request->quantity <= 0 && $book->quantity = 0) {
                return ApiResponse::fail('A quantidade deste livro já é zero!', [null]);
            }

            $borrowing = Borrowing::find($id)
                ->update($request->only(['user_id', 'book_id', 'borrow_date', 'return_date']));

            return ApiResponse::success('Empréstimo atualizado!', [$borrowing]);
        } catch (\Throwable $th) {
            return ApiResponse::fail('Não foi possível atualizar o emprestimo', [$th->getMessage()]);
        }
    }

    public function destroy(int $id)
    {
        try {
            $borrowing = Borrowing::findOrFail($id)->delete();

            return ApiResponse::success('Empréstimo deletado com sucesso!', [$borrowing]);
        } catch (\Throwable $th) {
            return ApiResponse::fail('Não foi possivel deletar o emprestimo!', [$th->getMessage()]);
        }
    }
}
