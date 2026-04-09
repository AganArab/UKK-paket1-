<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pengembalian - Workshop Kit</title>
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
    </style>
</head>
<body>
    <div class="print-buttons no-print">
        <button class="btn btn-print" onclick="window.print()"><i class="fa-solid fa-print"></i> Cetak</button>
        <a href="{{ route('pengembalian.index') }}" class="btn"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
    </div>

    <div class="print-header">
        <h1><i class="fa-solid fa-rotate-right"></i> Laporan Pengembalian Alat</h1>
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

    @if($borrowings->count() > 0)
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Peminjam</th>
                <th>Alat</th>
                <th>Qty</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali (Dijadwalkan)</th>
                <th>Tanggal Dikembalikan</th>
                <th>Kondisi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($borrowings as $borrowing)
            <tr>
                <td>#{{ $borrowing->id }}</td>
                <td>{{ $borrowing->borrower->name }}</td>
                <td>
                    @foreach($borrowing->borrowingDetails as $detail)
                        <div>{{ $detail->equipment->name }}</div>
                    @endforeach
                </td>
                <td>
                    @foreach($borrowing->borrowingDetails as $detail)
                        <div>{{ $detail->quantity }}</div>
                    @endforeach
                </td>
                <td>{{ $borrowing->borrow_date->format('d/m/Y') }}</td>
                <td>{{ $borrowing->expected_return_date->format('d/m/Y') }}</td>
                <td>{{ $borrowing->returnRecord ? $borrowing->returnRecord->return_date->format('d/m/Y') : '-' }}</td>
                <td>{{ $borrowing->returnRecord ? ucfirst($borrowing->returnRecord->condition_returned) : '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="print-footer">
        <p>Total Pengembalian: {{ $borrowings->count() }} | Laporan ini digenerate otomatis oleh sistem</p>
    </div>
    @else
    <div class="no-data">
        <p>Tidak ada data pengembalian untuk ditampilkan</p>
    </div>
    @endif
</body>
</html>
