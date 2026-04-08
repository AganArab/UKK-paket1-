<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /**
     * Print borrowings report
     */
    public function printBorrowings(Request $request)
    {
        if (!in_array(Auth::user()->role, ['admin', 'petugas'])) {
            abort(403);
        }

        $query = Borrowing::with(['borrower', 'approver', 'borrowingDetails.equipment']);

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('from_date') && $request->from_date) {
            $query->whereDate('borrow_date', '>=', $request->from_date);
        }

        if ($request->has('to_date') && $request->to_date) {
            $query->whereDate('borrow_date', '<=', $request->to_date);
        }

        $borrowings = $query->orderBy('borrow_date', 'desc')->get();

        return view('reports.print-borrowings', compact('borrowings'));
    }

    /**
     * Print returns report
     */
    public function printReturns(Request $request)
    {
        if (!in_array(Auth::user()->role, ['admin', 'petugas'])) {
            abort(403);
        }

        $query = Borrowing::with(['borrower', 'borrowingDetails.equipment', 'returnRecord'])
            ->where('status', 'returned');

        if ($request->has('from_date') && $request->from_date) {
            $query->whereDate('borrow_date', '>=', $request->from_date);
        }

        if ($request->has('to_date') && $request->to_date) {
            $query->whereDate('borrow_date', '<=', $request->to_date);
        }

        $borrowings = $query->orderBy('updated_at', 'desc')->get();

        return view('reports.print-returns', compact('borrowings'));
    }

    /**
     * Print equipment report
     */
    public function printEquipment(Request $request)
    {
        if (!in_array(Auth::user()->role, ['admin', 'petugas'])) {
            abort(403);
        }

        $query = \App\Models\Equipment::with('category');

        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        if ($request->has('condition') && $request->condition) {
            $query->where('condition', $request->condition);
        }

        $equipment = $query->orderBy('name')->get();

        return view('reports.print-equipment', compact('equipment'));
    }
}
