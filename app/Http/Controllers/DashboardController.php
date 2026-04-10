<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Equipment;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $data = [
            'total_equipment' => Equipment::sum('stock'),
            'total_categories' => Category::count(),
            'total_users' => User::count(),
            'pending_borrowings' => Borrowing::where('status', 'pending')->count(),
        ];

        if ($user->role === 'admin') {
            $data['recent_borrowings'] = Borrowing::with(['borrower', 'borrowingDetails.equipment'])
                ->latest()
                ->take(5)
                ->get();
            $data['low_stock_equipment'] = Equipment::where('stock', '<=', 5)->get();
        } elseif ($user->role === 'petugas') {
            $data['pending_approvals'] = Borrowing::where('status', 'pending')->with(['borrower'])->get();
            $data['recent_returns'] = Borrowing::where('status', 'returned')
                ->with(['borrower', 'returnRecord'])
                ->latest()
                ->take(5)
                ->get();
        } else { // peminjam
            $data['my_borrowings'] = Borrowing::where('borrower_id', $user->id)
                ->with(['borrowingDetails.equipment'])
                ->latest()
                ->take(5)
                ->get();
            $data['available_equipment'] = Equipment::where('stock', '>', 0)->with('category')->get();
        }

        return view('dashboard', compact('data'));
    }
}
