<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Stok Akhir - {{ now()->format('d-m-Y') }}</title>
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th {
            background: #1e2a3a;
            color: #fff;
            padding: 6px 8px;
            text-align: left;
            font-size: 10px;
        }

        td {
            padding: 5px 8px;
            border-bottom: 1px solid #eee;
        }

        tr:nth-child(even) td {
            background: #f9f9f9;
        }

        .text-end {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .badge-kritis {
            background: #dc3545;
            color: #fff;
            padding: 1px 5px;
            border-radius: 3px;
            font-size: 9px;
        }

        .badge-aman {
            background: #198754;
            color: #fff;
            padding: 1px 5px;
            border-radius: 3px;
            font-size: 9px;
        }

        tfoot td {
            background: #f0f2f5;
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

            tfoot {
                display: table-footer-group;
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
        <h2>Laporan Stok Akhir Bahan Baku</h2>
        <p>Toko Roti Andika — Dicetak: {{ now()->format('d M Y, H:i') }} WIB</p>
    </div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Nama Bahan</th>
                <th>Kategori</th>
                <th>Satuan</th>
                <th class="text-end">Stok Min</th>
                <th class="text-end">Stok Saat Ini</th>
                <th class="text-end">Nilai Stok (Rp)</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($bahanBakus as $i => $b)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $b->kode_bahan }}</td>
                    <td>{{ $b->nama_bahan }}</td>
                    <td>{{ $b->kategori }}</td>
                    <td>{{ $b->satuan }}</td>
                    <td class="text-end">{{ number_format($b->stok_minimum, 2, ',', '.') }}</td>
                    <td class="text-end"
                        style="{{ $b->isBawahMinimum() ? 'color:#dc3545;font-weight:bold' : 'color:#198754' }}">
                        {{ number_format($b->stok_saat_ini, 2, ',', '.') }}
                    </td>
                    <td class="text-end">{{ number_format($b->stok_saat_ini * $b->harga_satuan, 0, ',', '.') }}</td>
                    <td class="text-center">
                        @if ($b->isBawahMinimum())
                            <span class="badge-kritis">Kritis</span>
                        @else
                            <span class="badge-aman">Aman</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="7" class="text-end">Total Nilai Stok</td>
                <td class="text-end">Rp
                    {{ number_format($bahanBakus->sum(fn($b) => $b->stok_saat_ini * $b->harga_satuan), 0, ',', '.') }}
                </td>
                <td></td>
            </tr>
        </tfoot>
    </table>
    <div class="footer">Sistem Informasi Manajemen Inventory — Toko Roti Andika {{ now()->year }}</div>
</body>

</html>
