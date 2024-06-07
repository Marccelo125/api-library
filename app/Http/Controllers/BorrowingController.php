<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use Illuminate\Http\Request;
use Ramsey\Uuid\Type\Integer;

class BorrowingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $borrows = Borrowing::with('user', 'book')->get();
        return response()->json([
            'success' => true,
            'message' => 'All borrows',
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
                'required' => 'The :attribute field is required.',
                'int' => 'The :attribute must be an integer.',
                'date' => 'The :attribute must be a date.',
            ]);

            $book = $request->input('book_id');
            $book = Book::findOrFail($book);
            $quantity = $request->input('quantity');
            $bookAvailable = $book->isAvailableForBorrowing($quantity);

            // dd($bookAvailable);
            if ($book->available = 0) {
                return response()->json(['success' => false, 'msg' => 'Book not available', 'data' => null]);
            }

            if ($bookAvailable == false) {
                return response()->json(['success' => false, 'msg' => 'Quantity not available', 'data' => null]);
            }

            $borrow = Borrowing::create($request->only(['user_id', 'book_id', 'borrow_date', 'return_date']));
            return response()->json(['success' => true, 'msg' => 'Borrow created successfully', 'data' => $borrow]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'msg' => 'Something went wrong', 'data' => $th->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        try {
            $borrow = Borrowing::find($id);
            return response()->json(['success' => true, 'msg' => 'Borrow retrieved successfully', 'data' => $borrow]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'msg' => 'Something went wrong', 'data' => $th->getMessage()]);
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
                'required' => 'The :attribute field is required.',
                'int' => 'The :attribute must be an integer.',
                'date' => 'The :attribute must be a date.',
            ]);

            $book = $request->input('book_id');
            $book = Book::find($book);
            $quantity = $request->input('quantity');

            if ($book->quantity <= 0 || $book->available = 0) {
                return response()->json(['success' => false, 'msg' => 'Book not available', 'data' => null]);
            }

            if ($book->quantity < $quantity) {
                return response()->json(['success' => false, 'msg' => 'Quantity not available', 'data' => null]);
            }

            $borrowing = Borrowing::find($id)
                ->update($request->only(['user_id', 'book_id', 'borrow_date', 'return_date']));

            return response()->json(['success' => true, 'msg' => 'Borrow updated successfully', 'data' => $borrowing]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'msg' => 'Something went wrong', 'data' => $th->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try {
            $borrowing = Borrowing::findOrFail($id)->delete();
            return response()->json(['success' => true, 'msg' => 'Borrow deleted successfully']);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'msg' => 'Something went wrong', 'data' => $th->getMessage()]);
        }
    }
}
