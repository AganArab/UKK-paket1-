<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\ReturnModel;
use App\Models\Fine;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PengembalianController extends Controller
{
    public function index()
    {
        if (!in_array(Auth::user()->role, ['admin', 'petugas'])) {
            abort(403);
        }

        $borrowings = Borrowing::with(['borrower', 'borrowingDetails.equipment', 'returnRecord'])
            ->where('status', 'returned')
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('pengembalian.index', compact('borrowings'));
    }

    public function create()
    {
        if (!in_array(Auth::user()->role, ['admin', 'petugas'])) {
            abort(403);
        }

        $borrowings = Borrowing::with(['borrower', 'borrowingDetails.equipment'])
            ->where('status', 'approved')
            ->whereDoesntHave('returnRecord')
            ->get();

        return view('pengembalian.create', compact('borrowings'));
    }

    public function store(Request $request)
    {
        if (!in_array(Auth::user()->role, ['admin', 'petugas'])) {
            abort(403);
        }

        $request->validate([
            'borrowing_id' => 'required|exists:borrowings,id',
            'return_date' => 'required|date',
            'condition_returned' => 'required|in:baik,rusak',
        ]);

        $borrowing = Borrowing::find($request->borrowing_id);

        if ($borrowing->status !== 'approved') {
            return redirect()->back()->with('error', 'Only approved borrowings can be returned.');
        }

        DB::transaction(function () use ($request, $borrowing) {
            ReturnModel::create([
                'borrowing_id' => $borrowing->id,
                'return_date' => $request->return_date,
                'condition_returned' => $request->condition_returned,
                'processed_by' => Auth::id(),
            ]);

            $borrowing->update(['status' => 'returned']);

            // Increase stock back
            foreach ($borrowing->borrowingDetails as $detail) {
                $detail->equipment->increment('stock', $detail->quantity);
            }

            // Calculate fine if returned late
            $expectedDate = Carbon::parse($borrowing->expected_return_date);
            $returnDate = Carbon::parse($request->return_date);

            if ($returnDate->gt($expectedDate)) {
                $daysLate = $expectedDate->diffInDays($returnDate);
                $fineAmount = $daysLate * 5000;

                Fine::create([
                    'borrowing_id' => $borrowing->id,
                    'amount' => $fineAmount,
                    'reason' => "Terlambat {$daysLate} hari",
                ]);
            }
        });

        return redirect()->route('pengembalian.index')->with('success', 'Equipment returned successfully.');
    }

    public function show(Borrowing $borrowing)
    {
        if (!in_array(Auth::user()->role, ['admin', 'petugas']) || $borrowing->status !== 'returned') {
            abort(403);
        }

        $borrowing->load(['borrower', 'borrowingDetails.equipment', 'returnRecord.processor', 'fines']);
        return view('pengembalian.show', compact('borrowing'));
    }

    public function edit(Borrowing $borrowing)
    {
        if (!in_array(Auth::user()->role, ['admin', 'petugas']) || $borrowing->status !== 'returned') {
            abort(403);
        }

        return view('pengembalian.edit', compact('borrowing'));
    }

    public function update(Request $request, Borrowing $borrowing)
    {
        if (!in_array(Auth::user()->role, ['admin', 'petugas']) || $borrowing->status !== 'returned') {
            abort(403);
        }

        $request->validate([
            'return_date' => 'required|date',
            'condition_returned' => 'required|in:baik,rusak',
        ]);

        if ($borrowing->returnRecord) {
            $borrowing->returnRecord->update([
                'return_date' => $request->return_date,
                'condition_returned' => $request->condition_returned,
            ]);
        }

        return redirect()->route('pengembalian.index')->with('success', 'Return record updated successfully.');
    }

    public function destroy(Borrowing $borrowing)
    {
        if (!in_array(Auth::user()->role, ['admin', 'petugas']) || $borrowing->status !== 'returned') {
            abort(403);
        }

        DB::transaction(function () use ($borrowing) {
            if ($borrowing->returnRecord) {
                $borrowing->returnRecord->delete();
            }

            $borrowing->update(['status' => 'approved']);

            foreach ($borrowing->borrowingDetails as $detail) {
                $detail->equipment->decrement('stock', $detail->quantity);
            }

            Fine::where('borrowing_id', $borrowing->id)->delete();
        });

        return redirect()->route('pengembalian.index')->with('success', 'Return record deleted successfully.');
    }
}
