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
            table-layout:fixed;
        }

        th{
            background:#d9d9d9;
            border:1px solid #000;
            padding:5px;
            text-align:center;
            vertical-align:middle;
        }

        td{
            border:1px solid #000;
            padding:4px;
            vertical-align:middle;
            word-wrap:break-word;
        }

        .text-center{
            text-align:center;
        }

        table{
            width:100%;
            border-collapse:collapse;
            table-layout:fixed;
        }

        .text-left{
            text-align:left;
        }

        .text-right{
            text-align:right;
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
    @php

if($showEventColumn){

    $wNo = 28;
    $wEvent = 82;
    $wParticipant = 78;
    $wNama = 72;
    $wPhone = 68;
    $wCampus = 72;
    $wSouvenir = 120;
    $wTanggal = 82;
    $wPetugas = 68;

}else{

    $wNo = 30;
    $wParticipant = 90;
    $wNama = 88;
    $wPhone = 82;
    $wCampus = 88;
    $wSouvenir = 145;
    $wTanggal = 95;
    $wPetugas = 85;

}

@endphp

<h2>BINUS UNIVERSITY</h2>

<h4>LAPORAN PENYERAHAN SOUVENIR</h4>

<div class="info">

    <table style="border:none;">

        <tr>

            <td style="border:none;width:120px;">Event</td>

            <td style="border:none;">

                :

                {{ $event?->name ?? 'Semua Event' }}

            </td>

        </tr>

        <tr>

            <td style="border:none;">Total Data</td>

            <td style="border:none;">

                :

                {{ $receipts->count() }}

            </td>

        </tr>

        <tr>

            <td style="border:none;">Dicetak</td>

            <td style="border:none;">

                :

                {{ now()->format('d M Y H:i') }}

            </td>

        </tr>

    </table>

</div>

<table>

    <thead>

        <tr>
            <th width="{{ $wNo }}">No</th>
            @if($showEventColumn)
                <th width="{{ $wEvent }}">Event</th>
            @endif
            <th width="{{ $wParticipant }}">Participant ID</th>
            <th width="{{ $wNama }}">Nama</th>
            <th width="{{ $wPhone }}">No HP</th>
            <th width="{{ $wCampus }}">Base Campus</th>
            <th width="{{ $wSouvenir }}">Souvenir</th>
            <th width="{{ $wTanggal }}">Tanggal</th>
            <th width="{{ $wPetugas }}">Petugas</th>
        </tr>

    </thead>

    <tbody>

    @forelse($receipts as $receipt)
        <tr>
            <td class="text-center">{{ $loop->iteration }}</td>
            @if($showEventColumn)
            <td class="text-left">{{ optional(optional($receipt->participant)->event)->name }}</td>
            @endif
            <td class="text-center">{{ optional($receipt->participant)->participant_code }}</td>
            <td class="text-left">{{ optional($receipt->participant)->name }}</td>
            <td class="text-center">{{ optional($receipt->participant)->phone }}</td>
            <td class="text-left">{{ optional($receipt->participant)->campus }}</td>
            <td class="text-left">{{ $receipt->receiptItems->pluck('item.name')->implode(', ') }}</td>
            <td class="text-center">{{ \Carbon\Carbon::parse($receipt->received_at)->format('d-m-Y H:i') }}</td>
            <td class="text-center">{{ optional($receipt->user)->name }}</td>
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
<br><br>

<div style="width:100%;">

    {{-- SUMMARY --}}
    <div style=" width:48%; display:inline-block; vertical-align:top;">
        <table>
            <thead>
                <tr>
                    <th colspan="2"
                        style="
                        background:#1F4E78;
                        color:#FFFFFF;
                        font-weight:bold;
                        text-align:center;
                        padding:8px;">
                        SUMMARY LAPORAN
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Total Peserta</td>
                    <td class="text-center">
                        {{ $totalParticipants }}
                    </td>
                </tr>
                <tr>
                    <td>Jenis Souvenir</td>
                    <td class="text-center">
                        {{ $totalSouvenirType }}
                    </td>
                </tr>
                <tr>
                    <td>Total Item Dibagikan</td>
                    <td class="text-center">
                        {{ $totalItem }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    {{-- RINGKASAN --}}
    <div style=" width:48%; display:inline-block; vertical-align:top; margin-left:2%;">
        <table>
            <thead>
                <tr>
                    <th colspan="2"
                    style="
                        background:#1F4E78;
                        color:#FFFFFF;
                        font-weight:bold;
                        text-align:center;
                        padding:8px;">
                        RINGKASAN SOUVENIR
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach($souvenirSummary as $souvenir => $qty)
                    <tr>
                        <td>{{ $souvenir }}</td>
                        <td class="text-center">
                            {{ $qty }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="footer">
    Dicetak otomatis oleh Sistem Penyerahan Souvenir BINUS
</div>
</body>
</html>
