<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Alat - Workshop Kit</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            background: #fff;
            padding: 20px;
        }

        .print-header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #8b7355;
            padding-bottom: 20px;
        }

        .print-header h1 {
            color: #8b7355;
            font-size: 28px;
            margin-bottom: 10px;
        }

        .print-header p {
            color: #666;
            font-size: 14px;
        }

        .print-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .print-info div {
            flex: 1;
        }

        .print-info strong {
            color: #8b7355;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        thead {
            background-color: #8b7355;
            color: white;
        }

        th {
            padding: 12px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #ddd;
        }

        td {
            padding: 10px 12px;
            border: 1px solid #ddd;
            font-size: 14px;
        }

        tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }

        tbody tr:hover {
            background-color: #f0ebe3;
        }

        .condition {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }

        .condition-baik {
            background-color: #d4edda;
            color: #155724;
        }

        .condition-rusak {
            background-color: #f8d7da;
            color: #721c24;
        }

        .condition-perlu_perbaikan {
            background-color: #fff3cd;
            color: #856404;
        }

        .print-footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #999;
        }

        @media print {
            body {
                padding: 0;
                background: white;
            }

            .no-print {
                display: none;
            }
        }

        .print-buttons {
            margin-bottom: 20px;
            text-align: right;
        }

        .btn {
            display: inline-block;
            padding: 8px 16px;
            margin-left: 10px;
            background-color: #8b7355;
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        .btn:hover {
            background-color: #6b5344;
        }

        .btn-print {
            background-color: #0066cc;
        }

        .btn-print:hover {
            background-color: #0052a3;
        }

        .summary {
            margin-top: 30px;
            padding: 15px;
            background-color: #f5f1ea;
            border-radius: 6px;
        }

        .summary-item {
            display: inline-block;
            margin-right: 30px;
            margin-bottom: 10px;
        }

        .summary-item strong {
            color: #8b7355;
        }
    </style>
</head>
<body>
    <div class="print-buttons no-print">
        <button class="btn btn-print" onclick="window.print()"><i class="fa-solid fa-print"></i> Cetak</button>
        <a href="{{ route('equipment.index') }}" class="btn"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
    </div>

    <div class="print-header">
        <h1><i class="fa-solid fa-screwdriver-wrench"></i> Laporan Daftar Alat</h1>
        <p>Workshop Kit - Sistem Manajemen Alat</p>
    </div>

    <div class="print-info">
        <div>
            <strong>Tanggal Cetak:</strong> {{ now()->format('d/m/Y H:i') }}
        </div>
        <div>
            <strong>Dicetak oleh:</strong> {{ Auth::user()->name }}
        </div>
    </div>

    @if($equipment->count() > 0)
    <table>
        <thead>
            <tr>
                <th>Nama Alat</th>
                <th>Kategori</th>
                <th>Deskripsi</th>
                <th>Stok</th>
                <th>Kondisi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($equipment as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td>{{ $item->category->name }}</td>
                <td>{{ $item->description ?? '-' }}</td>
                <td style="text-align: center; font-weight: bold;">{{ $item->stock }}</td>
                <td>
                    <span class="condition condition-{{ $item->condition }}">
                        {{ ucfirst(str_replace('_', ' ', $item->condition)) }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <div class="summary-item">
            <strong>Total Alat:</strong> {{ $equipment->count() }}
        </div>
        <div class="summary-item">
            <strong>Total Stok:</strong> {{ $equipment->sum('stock') }}
        </div>
        <div class="summary-item">
            <strong>Alat Baik:</strong> {{ $equipment->where('condition', 'baik')->count() }}
        </div>
        <div class="summary-item">
            <strong>Alat Rusak:</strong> {{ $equipment->where('condition', 'rusak')->count() }}
        </div>
    </div>

    <div class="print-footer">
        <p>Laporan ini digenerate otomatis oleh sistem pada {{ now()->format('d/m/Y H:i') }}</p>
    </div>
    @else
    <div class="no-data">
        <p>Tidak ada data alat untuk ditampilkan</p>
    </div>
    @endif
</body>
</html>
