<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AspirasiController extends Controller
{
    // Public: Form aspirasi
    public function index()
    {
        $kategori = DB::table('kategori')->get();
        return view('aspirasi.form', compact('kategori'));
    }

    // Public: Simpan aspirasi
    public function store(Request $request)
    {
        $request->validate([
            'nama_siswa' => 'required|string|max:255',
            'isi_aspirasi' => 'required|string',
            'kategori_id' => 'required|exists:kategori,id',
        ]);

        DB::table('aspirasi')->insert([
            'nama_siswa' => $request->nama_siswa,
            'isi_aspirasi' => $request->isi_aspirasi,
            'kategori_id' => $request->kategori_id,
            'status' => 'pending',
            'progres' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('/tracking?nama_siswa=' . urlencode($request->nama_siswa))->with('success', 'Aspirasi berhasil dikirim!');
    }

    public function tracking(Request $request)
    {
        $namaSiswa = $request->query('nama_siswa');
        $trackingData = [];

        if ($namaSiswa) {
            $trackingData = DB::table('aspirasi')
                ->join('kategori', 'aspirasi.kategori_id', '=', 'kategori.id')
                ->leftJoin('feedback', 'aspirasi.id', '=', 'feedback.aspirasi_id')
                ->select([
                    'aspirasi.id',
                    'aspirasi.nama_siswa',
                    'aspirasi.isi_aspirasi',
                    'aspirasi.status',
                    'aspirasi.progres',
                    'kategori.nama_kategori',
                    DB::raw('GROUP_CONCAT(feedback.isi_feedback SEPARATOR "\n") as feedback_all'),
                ])
                ->where('aspirasi.nama_siswa', 'like', '%' . $namaSiswa . '%')
                ->groupBy(
                    'aspirasi.id',
                    'aspirasi.nama_siswa',
                    'aspirasi.isi_aspirasi',
                    'aspirasi.status',
                    'aspirasi.progres',
                    'kategori.nama_kategori'
                )
                ->get();
        }

        return view('tracking', compact('trackingData', 'namaSiswa'));
    }

    public function cariTracking(Request $request)
    {
        $request->validate([
            'nama_siswa' => 'required|string|max:255',
        ]);

        return redirect('/tracking?nama_siswa=' . urlencode($request->nama_siswa));
    }

    // Admin: List aspirasi dengan filter
    public function list(Request $request)
    {
        $query = DB::table('aspirasi')
            ->join('kategori', 'aspirasi.kategori_id', '=', 'kategori.id')
            ->select('aspirasi.*', 'kategori.nama_kategori');

        if ($request->filled('tanggal')) {
            $query->whereDate('aspirasi.created_at', $request->tanggal);
        }

        if ($request->filled('kategori_id')) {
            $query->where('aspirasi.kategori_id', $request->kategori_id);
        }

        if ($request->filled('status')) {
            $query->where('aspirasi.status', $request->status);
        }

        $aspirasi = $query->get();
        $kategori = DB::table('kategori')->get();

        return view('admin.aspirasi', compact('aspirasi', 'kategori'));
    }

    // Admin: Update status
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,proses,selesai',
        ]);

        DB::table('aspirasi')
            ->where('id', $id)
            ->update([
                'status' => $request->status,
                'updated_at' => now(),
            ]);

        return redirect('/aspirasi')->with('success', 'Status berhasil diupdate!');
    }

    public function updateProgres(Request $request, $id)
    {
        $request->validate([
            'progres' => 'required|string|max:255',
            'status' => 'nullable|in:pending,proses,selesai',
        ]);

        $updateData = [
            'progres' => $request->progres,
            'updated_at' => now(),
        ];

        if ($request->filled('status')) {
            $updateData['status'] = $request->status;
        }

        DB::table('aspirasi')
            ->where('id', $id)
            ->update($updateData);

        return redirect('/aspirasi')->with('success', 'Progres berhasil diperbarui!');
    }

    // Admin: Tambah feedback
    public function addFeedback(Request $request, $id)
    {
        $request->validate([
            'isi_feedback' => 'required|string',
        ]);

        DB::table('feedback')->insert([
            'aspirasi_id' => $id,
            'isi_feedback' => $request->isi_feedback,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('/aspirasi')->with('success', 'Feedback berhasil ditambahkan!');
    }
}
