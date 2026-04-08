<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = $request->session()->get('user');

        $booksCount = DB::table('books')->count();
        $activeBorrows = DB::table('transactions')
            ->where('user_id', $user['id'])
            ->where('status', 'dipinjam')
            ->count();

        $transactions = DB::table('transactions')
            ->join('books', 'transactions.book_id', '=', 'books.id')
            ->where('transactions.user_id', $user['id'])
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('transactions.status', $request->input('status'));
            })
            ->select('transactions.*', 'books.title as book_title')
            ->orderBy('transactions.id', 'desc')
            ->get();

        return view('user.dashboard', compact('booksCount', 'activeBorrows', 'transactions'));
    }

    public function books(Request $request)
    {
        $query = DB::table('books');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->input('search') . '%');
        }

        $books = $query->orderBy('title')->get();

        return view('user.books.index', compact('books'));
    }

    public function borrow(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'book_id' => 'required|numeric|exists:books,id',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $user = $request->session()->get('user');
        $book = DB::table('books')->where('id', $request->input('book_id'))->first();

        if (! $book || $book->stock <= 0) {
            return Redirect::back()->with('error', 'Buku tidak tersedia untuk dipinjam.');
        }

        DB::table('transactions')->insert([
            'user_id' => $user['id'],
            'book_id' => $book->id,
            'borrow_date' => now()->toDateString(),
            'return_date' => null,
            'status' => 'dipinjam',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('books')->where('id', $book->id)->decrement('stock');

        return Redirect::back()->with('success', 'Buku berhasil dipinjam.');
    }

    public function returnBook(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'transaction_id' => 'required|numeric|exists:transactions,id',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $user = $request->session()->get('user');
        $transaction = DB::table('transactions')
            ->where('id', $request->input('transaction_id'))
            ->where('user_id', $user['id'])
            ->first();

        if (! $transaction) {
            return Redirect::back()->with('error', 'Transaksi tidak ditemukan.');
        }

        if ($transaction->status === 'dikembalikan') {
            return Redirect::back()->with('error', 'Buku sudah dikembalikan.');
        }

        DB::table('transactions')->where('id', $transaction->id)->update([
            'status' => 'dikembalikan',
            'return_date' => now()->toDateString(),
            'updated_at' => now(),
        ]);

        DB::table('books')->where('id', $transaction->book_id)->increment('stock');

        return Redirect::back()->with('success', 'Buku berhasil dikembalikan.');
    }
}
