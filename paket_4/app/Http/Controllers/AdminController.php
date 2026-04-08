<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function dashboard()
    {
        $booksCount = DB::table('books')->count();
        $usersCount = DB::table('users')->count();
        $transactionsCount = DB::table('transactions')->count();

        return view('admin.dashboard', compact('booksCount', 'usersCount', 'transactionsCount'));
    }

    // Books
    public function booksIndex(Request $request)
    {
        $query = DB::table('books');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->input('search') . '%');
        }

        $books = $query->orderBy('id', 'desc')->get();

        return view('admin.books.index', compact('books'));
    }

    public function booksCreate()
    {
        return view('admin.books.form', ['book' => null]);
    }

    public function booksStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'publisher' => 'required|string|max:255',
            'year' => 'required|numeric',
            'stock' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        DB::table('books')->insert([
            'title' => $request->input('title'),
            'author' => $request->input('author'),
            'publisher' => $request->input('publisher'),
            'year' => $request->input('year'),
            'stock' => $request->input('stock'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return Redirect::to('/admin/books')->with('success', 'Buku berhasil ditambahkan.');
    }

    public function booksEdit($id)
    {
        $book = DB::table('books')->where('id', $id)->first();

        if (! $book) {
            return Redirect::to('/admin/books')->with('error', 'Buku tidak ditemukan.');
        }

        return view('admin.books.form', compact('book'));
    }

    public function booksUpdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'publisher' => 'required|string|max:255',
            'year' => 'required|numeric',
            'stock' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        DB::table('books')->where('id', $id)->update([
            'title' => $request->input('title'),
            'author' => $request->input('author'),
            'publisher' => $request->input('publisher'),
            'year' => $request->input('year'),
            'stock' => $request->input('stock'),
            'updated_at' => now(),
        ]);

        return Redirect::to('/admin/books')->with('success', 'Buku berhasil diperbarui.');
    }

    public function booksDestroy($id)
    {
        DB::table('books')->where('id', $id)->delete();

        return Redirect::to('/admin/books')->with('success', 'Buku berhasil dihapus.');
    }

    // Users
    public function usersIndex(Request $request)
    {
        $query = DB::table('users');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->input('search') . '%');
        }

        $users = $query->orderBy('id', 'desc')->get();

        return view('admin.users.index', compact('users'));
    }

    public function usersCreate()
    {
        return view('admin.users.form', ['user' => null]);
    }

    public function usersStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,siswa',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        DB::table('users')->insert([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role' => $request->input('role'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return Redirect::to('/admin/users')->with('success', 'User berhasil ditambahkan.');
    }

    public function usersEdit($id)
    {
        $user = DB::table('users')->where('id', $id)->first();

        if (! $user) {
            return Redirect::to('/admin/users')->with('error', 'User tidak ditemukan.');
        }

        return view('admin.users.form', compact('user'));
    }

    public function usersUpdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:admin,siswa',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $data = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'role' => $request->input('role'),
            'updated_at' => now(),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->input('password'));
        }

        DB::table('users')->where('id', $id)->update($data);

        return Redirect::to('/admin/users')->with('success', 'User berhasil diperbarui.');
    }

    public function usersDestroy($id)
    {
        DB::table('users')->where('id', $id)->delete();

        return Redirect::to('/admin/users')->with('success', 'User berhasil dihapus.');
    }

    // Transactions
    public function transactionsIndex(Request $request)
    {
        $query = DB::table('transactions')
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->join('books', 'transactions.book_id', '=', 'books.id')
            ->select(
                'transactions.*',
                'users.name as user_name',
                'books.title as book_title'
            );

        if ($request->filled('status')) {
            $query->where('transactions.status', $request->input('status'));
        }

        $transactions = $query->orderBy('transactions.id', 'desc')->get();
        $users = DB::table('users')->orderBy('name')->get();
        $books = DB::table('books')->orderBy('title')->get();

        return view('admin.transactions.index', compact('transactions', 'users', 'books'));
    }

    public function transactionsCreate()
    {
        $users = DB::table('users')->orderBy('name')->get();
        $books = DB::table('books')->orderBy('title')->get();
        $transaction = null;

        return view('admin.transactions.form', compact('users', 'books', 'transaction'));
    }

    public function transactionsStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric|exists:users,id',
            'book_id' => 'required|numeric|exists:books,id',
            'borrow_date' => 'required|date',
            'status' => 'required|in:dipinjam,dikembalikan',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $book = DB::table('books')->where('id', $request->input('book_id'))->first();

        if (! $book) {
            return Redirect::back()->with('error', 'Buku tidak ditemukan.')->withInput();
        }

        if ($request->input('status') === 'dipinjam' && $book->stock <= 0) {
            return Redirect::back()->with('error', 'Stok buku tidak cukup.')->withInput();
        }

        DB::table('transactions')->insert([
            'user_id' => $request->input('user_id'),
            'book_id' => $request->input('book_id'),
            'borrow_date' => $request->input('borrow_date'),
            'return_date' => $request->input('status') === 'dikembalikan' ? now()->toDateString() : null,
            'status' => $request->input('status'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if ($request->input('status') === 'dipinjam') {
            DB::table('books')->where('id', $book->id)->decrement('stock');
        }

        return Redirect::to('/admin/transactions')->with('success', 'Transaksi berhasil disimpan.');
    }

    public function transactionsEdit($id)
    {
        $transaction = DB::table('transactions')->where('id', $id)->first();

        if (! $transaction) {
            return Redirect::to('/admin/transactions')->with('error', 'Transaksi tidak ditemukan.');
        }

        $users = DB::table('users')->orderBy('name')->get();
        $books = DB::table('books')->orderBy('title')->get();

        return view('admin.transactions.form', compact('transaction', 'users', 'books'));
    }

    public function transactionsUpdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric|exists:users,id',
            'book_id' => 'required|numeric|exists:books,id',
            'borrow_date' => 'required|date',
            'status' => 'required|in:dipinjam,dikembalikan',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $transaction = DB::table('transactions')->where('id', $id)->first();

        if (! $transaction) {
            return Redirect::to('/admin/transactions')->with('error', 'Transaksi tidak ditemukan.');
        }

        $book = DB::table('books')->where('id', $request->input('book_id'))->first();

        if (! $book) {
            return Redirect::back()->with('error', 'Buku tidak ditemukan.')->withInput();
        }

        $status = $request->input('status');
        $returnDate = $status === 'dikembalikan' ? now()->toDateString() : null;

        if ($transaction->status !== 'dikembalikan' && $status === 'dikembalikan') {
            DB::table('books')->where('id', $book->id)->increment('stock');
        }

        if ($transaction->status === 'dikembalikan' && $status === 'dipinjam' && $book->stock <= 0) {
            return Redirect::back()->with('error', 'Stok buku tidak cukup untuk perubahan status.')->withInput();
        }

        if ($transaction->status === 'dikembalikan' && $status === 'dipinjam') {
            DB::table('books')->where('id', $book->id)->decrement('stock');
        }

        DB::table('transactions')->where('id', $id)->update([
            'user_id' => $request->input('user_id'),
            'book_id' => $request->input('book_id'),
            'borrow_date' => $request->input('borrow_date'),
            'return_date' => $returnDate,
            'status' => $status,
            'updated_at' => now(),
        ]);

        return Redirect::to('/admin/transactions')->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function transactionsDestroy($id)
    {
        DB::table('transactions')->where('id', $id)->delete();

        return Redirect::to('/admin/transactions')->with('success', 'Transaksi berhasil dihapus.');
    }
}
