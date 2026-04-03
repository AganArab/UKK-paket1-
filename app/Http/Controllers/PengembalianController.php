<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use Illuminate\Http\Request;

class PengembalianController extends Controller
{
    public function index()
    {
        $borrowings = Borrowing::with(['borrower', 'details.equipment'])
            ->where('status', 'returned')
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('pengembalian.index', compact('borrowings'));
    }
}