<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\BorrowingDetail;
use App\Models\Equipment;
use App\Models\ReturnModel;
use App\Models\Fine;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BorrowingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Borrowing::with(['borrower', 'approver', 'borrowingDetails.equipment']);

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if (Auth::user()->role === 'peminjam') {
            $query->where('borrower_id', Auth::id());
        }

        $borrowings = $query->paginate(10);
        return view('borrowings.index', compact('borrowings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $equipment = Equipment::where('stock', '>', 0)->with('category')->get();
        return view('borrowings.create', compact('equipment'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'equipment' => 'required|array|min:1',
            'equipment.*.id' => 'required|exists:equipment,id',
            'equipment.*.quantity' => 'required|integer|min:1',
            'expected_return_date' => 'required|date|after:today',
        ]);

        DB::transaction(function () use ($request) {
            $borrowing = Borrowing::create([
                'borrower_id' => Auth::id(),
                'borrow_date' => now()->toDateString(),
                'expected_return_date' => $request->expected_return_date,
                'status' => 'pending',
            ]);

            foreach ($request->equipment as $item) {
                $equipment = Equipment::find($item['id']);

                // Check stock availability
                if ($equipment->stock < $item['quantity']) {
                    throw new \Exception("Insufficient stock for {$equipment->name}");
                }

                BorrowingDetail::create([
                    'borrowing_id' => $borrowing->id,
                    'equipment_id' => $item['id'],
                    'quantity' => $item['quantity'],
                ]);
            }
        });

        return redirect()->route('borrowings.index')->with('success', 'Borrowing request submitted successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Borrowing $borrowing)
    {
        $borrowing->load(['borrower', 'approver', 'borrowingDetails.equipment', 'returnRecord', 'fines']);
        return view('borrowings.show', compact('borrowing'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Borrowing $borrowing)
    {
        // Only allow editing pending borrowings by the borrower
        if ($borrowing->status !== 'pending' || $borrowing->borrower_id !== Auth::id()) {
            abort(403);
        }

        $equipment = Equipment::where('stock', '>', 0)->with('category')->get();
        return view('borrowings.edit', compact('borrowing', 'equipment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Borrowing $borrowing)
    {
        if ($borrowing->status !== 'pending' || $borrowing->borrower_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'equipment' => 'required|array|min:1',
            'equipment.*.id' => 'required|exists:equipment,id',
            'equipment.*.quantity' => 'required|integer|min:1',
            'expected_return_date' => 'required|date|after:today',
        ]);

        DB::transaction(function () use ($request, $borrowing) {
            $borrowing->update([
                'expected_return_date' => $request->expected_return_date,
            ]);

            // Delete existing details
            $borrowing->borrowingDetails()->delete();

            // Create new details
            foreach ($request->equipment as $item) {
                $equipment = Equipment::find($item['id']);

                if ($equipment->stock < $item['quantity']) {
                    throw new \Exception("Insufficient stock for {$equipment->name}");
                }

                BorrowingDetail::create([
                    'borrowing_id' => $borrowing->id,
                    'equipment_id' => $item['id'],
                    'quantity' => $item['quantity'],
                ]);
            }
        });

        return redirect()->route('borrowings.index')->with('success', 'Borrowing request updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Borrowing $borrowing)
    {
        if ($borrowing->status !== 'pending' || $borrowing->borrower_id !== Auth::id()) {
            abort(403);
        }

        $borrowing->delete();

        return redirect()->route('borrowings.index')->with('success', 'Borrowing request deleted successfully.');
    }

    public function approve(Borrowing $borrowing)
    {
        if (!in_array(Auth::user()->role, ['admin', 'petugas'])) {
            abort(403);
        }

        if ($borrowing->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending requests can be approved.');
        }

        DB::transaction(function () use ($borrowing) {
            $borrowing->update([
                'status' => 'approved',
                'approved_by' => Auth::id(),
            ]);

            // Decrease stock
            foreach ($borrowing->borrowingDetails as $detail) {
                $equipment = $detail->equipment;
                $equipment->decrement('stock', $detail->quantity);
            }
        });

        return redirect()->back()->with('success', 'Borrowing request approved successfully.');
    }

    public function reject(Borrowing $borrowing)
    {
        if (!in_array(Auth::user()->role, ['admin', 'petugas'])) {
            abort(403);
        }

        if ($borrowing->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending requests can be rejected.');
        }

        $borrowing->update(['status' => 'rejected']);

        return redirect()->back()->with('success', 'Borrowing request rejected.');
    }

    public function returnEquipment(Request $request, Borrowing $borrowing)
    {
        if (!in_array(Auth::user()->role, ['admin', 'petugas'])) {
            abort(403);
        }

        if ($borrowing->status !== 'approved') {
            return redirect()->back()->with('error', 'Only approved borrowings can be returned.');
        }

        $request->validate([
            'return_date' => 'required|date',
            'condition_returned' => 'required|in:baik,rusak',
        ]);

        DB::transaction(function () use ($request, $borrowing) {
            // Create return record
            ReturnModel::create([
                'borrowing_id' => $borrowing->id,
                'return_date' => $request->return_date,
                'condition_returned' => $request->condition_returned,
                'processed_by' => Auth::id(),
            ]);

            // Update borrowing status
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
                $fineAmount = $daysLate * 5000; // 5000 per day

                Fine::create([
                    'borrowing_id' => $borrowing->id,
                    'amount' => $fineAmount,
                    'reason' => "Terlambat {$daysLate} hari",
                ]);
            }
        });

        return redirect()->back()->with('success', 'Equipment returned successfully.');
    }
}
