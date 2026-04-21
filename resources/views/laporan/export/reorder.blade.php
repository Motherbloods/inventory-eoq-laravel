<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Reorder Point - {{ now()->format('d-m-Y') }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #222;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 16px;
            border-bottom: 2px solid #e65c1e;
            padding-bottom: 8px;
        }

        .header h2 {
            margin: 0 0 2px;
            font-size: 14px;
        }

        .header p {
            margin: 0;
            color: #666;
            font-size: 10px;
        }

        h4 {
            font-size: 12px;
            margin: 14px 0 4px;
            color: #333;
            border-left: 3px solid #e65c1e;
            padding-left: 6px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
            margin-bottom: 12px;
        }

        th {
            background: #1e2a3a;
            color: #fff;
            padding: 5px 7px;
            font-size: 10px;
        }

        td {
            padding: 5px 7px;
            border-bottom: 1px solid #eee;
        }

        tr:nth-child(even) td {
            background: #f9f9f9;
        }

        .text-end {
            text-align: right;
        }

        .text-danger {
            color: #dc3545;
            font-weight: bold;
        }

        .text-primary {
            color: #0d6efd;
            font-weight: bold;
        }

        .footer {
            margin-top: 16px;
            text-align: right;
            font-size: 9px;
            color: #aaa;
        }

        .print-actions {
            text-align: center;
            margin-bottom: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 5px;
        }

        .btn {
            padding: 10px 20px;
            margin: 0 5px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
        }

        .btn-primary {
            background: #0d6efd;
            color: white;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn:hover {
            opacity: 0.9;
        }

        @media print {
            body {
                padding: 0;
            }

            .print-actions {
                display: none !important;
            }

            table {
                page-break-inside: auto;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            thead {
                display: table-header-group;
            }

            h4 {
                page-break-after: avoid;
            }
        }

        @page {
            size: A4 landscape;
            margin: 1cm;
        }
    </style>
</head>

<body>
    <div class="print-actions">
        <button onclick="window.print()" class="btn btn-primary">🖨️ Print / Save PDF</button>
    </div>

    <div class="header">
        <h2>Laporan Bahan Perlu Dipesan</h2>
        <p>Toko Roti Andika &nbsp;|&nbsp; Dicetak: {{ now()->format('d M Y, H:i') }} WIB</p>
    </div>

    <h4>Bahan di Bawah Stok Minimum ({{ $bahanMinimum->count() }})</h4>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Bahan Baku</th>
                <th>Kategori</th>
                <th class="text-end">Stok Min</th>
                <th class="text-end">Stok Saat Ini</th>
                <th class="text-end">Kekurangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bahanMinimum as $i => $b)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $b->nama_bahan }}</td>
                    <td>{{ $b->kategori }}</td>
                    <td class="text-end">{{ number_format($b->stok_minimum, 2, ',', '.') }} {{ $b->satuan }}</td>
                    <td class="text-end text-danger">{{ number_format($b->stok_saat_ini, 2, ',', '.') }}
                        {{ $b->satuan }}
                    </td>
                    <td class="text-end text-danger">
                        {{ number_format($b->stok_minimum - $b->stok_saat_ini, 2, ',', '.') }}
                        {{ $b->satuan }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center;color:green">Semua bahan masih di atas stok minimum.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h4>Bahan Mencapai Reorder Point EOQ ({{ $bahanReorderEoq->count() }})</h4>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Bahan Baku</th>
                <th class="text-end">Stok Saat Ini</th>
                <th class="text-end">Reorder Point</th>
                <th class="text-end">Q* (EOQ)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bahanReorderEoq as $i => $e)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $e->bahanBaku->nama_bahan }}</td>
                    <td class="text-end text-danger">{{ number_format($e->bahanBaku->stok_saat_ini, 2, ',', '.') }}
                        {{ $e->bahanBaku->satuan }}</td>
                    <td class="text-end">{{ number_format($e->reorder_point, 2, ',', '.') }}
                        {{ $e->bahanBaku->satuan }}
                    </td>
                    <td class="text-end text-primary">{{ number_format($e->eoq_result, 2, ',', '.') }}
                        {{ $e->bahanBaku->satuan }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align:center;color:green">Tidak ada bahan yang mencapai reorder
                        point.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="footer">Sistem Informasi Manajemen Inventory — Toko Roti Andika {{ now()->year }}</div>
</body>

</html>
