<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penyerahan Souvenir</title>
    <style>
        body{
            font-family: DejaVu Sans, sans-serif;
            font-size:11px;
            color:#222;
        }
        h2{
            margin:0;
            text-align:center;
        }
        h4{
            margin:3px 0 20px;
            text-align:center;
            font-weight:normal;
        }
        table{
            width:100%;
            border-collapse:collapse;
        }
        th{
            background:#d9d9d9;
            border:1px solid #000;
            padding:6px;
            text-align:center;
        }
        td{
            border:1px solid #000;
            padding:5px;
            vertical-align:top;
        }
        .text-center{
            text-align:center;
        }
        .text-right{
            text-align:right;
        }
        .mb{
            margin-bottom:20px;
        }
        .info{
            margin-bottom:15px;
        }
        .footer{
            margin-top:20px;
            font-size:10px;
            text-align:right;
        }
    </style>
</head>
<body>
<h2>
    BINUS UNIVERSITY
</h2>
<h4>
    LAPORAN PENYERAHAN SOUVENIR
</h4>
<div class="info">
    <table style="border:none;">
        <tr>
            <td style="border:none;width:120px;">
                Event
            </td>
            <td style="border:none;">
                :
                {{ $event?->name ?? 'Semua Event' }}
            </td>
        </tr>
        <tr>
            <td style="border:none;">
                Total Data
            </td>
            <td style="border:none;">
                :
                {{ $receipts->count() }}
            </td>
        </tr>
        <tr>
            <td style="border:none;">
                Dicetak
            </td>
            <td style="border:none;">
                :
                {{ now()->format('d M Y H:i') }}
            </td>
        </tr>
    </table>
</div>
<table>
    <thead>
    <tr

        <th width="35">
            No
        </th>
        <th width="90">
            Participant ID
        </th>
        <th>
            Nama
        </th>
        <th width="90">
            No HP
        </th>
        <th width="80">
            Base Campus
        </th>
        <th width="140">
            Souvenir
        </th>
        <th width="110">
            Tanggal
        </th>
        <th width="100">
            Petugas
        </th>
    </tr>
    </thead>
    <tbody>
    @forelse($receipts as $receipt)
        <tr>
            <td class="text-center">
                {{ $loop->iteration }}

            </td>
            <td>
                {{ optional($receipt->participant)->participant_code }}
            </td>
            <td>
                {{ optional($receipt->participant)->name }}
            </td>
            <td>
                {{ optional($receipt->participant)->phone }}
            </td>
            <td>
                {{ optional($receipt->participant)->campus }}

            </td>
            <td>
                {{ $receipt->receiptItems
                    ->pluck('item.name')
                    ->implode(', ') }}
            </td>
            <td>
                {{ \Carbon\Carbon::parse($receipt->received_at)->format('d-m-Y H:i') }}
            </td>
            <td>
                {{ optional($receipt->user)->name }}
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="8" class="text-center">
                Tidak ada data
            </td>
        </tr>
    @endforelse
    </tbody>
</table>
<div class="footer">
    Dicetak otomatis oleh Sistem Penyerahan Souvenir BINUS
</div>
</body>
</html>
