<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Laporan Penyerahan Souvenir</title>
</head>

<body>

@php

$showEventColumn = $receipts
    ->pluck('participant.event.id')
    ->filter()
    ->unique()
    ->count() > 1;

@endphp

<table>

    {{-- ============================= --}}
    {{-- JUDUL --}}
    {{-- ============================= --}}

    <tr>
        <td colspan="{{ $showEventColumn ? 9 : 8 }}"
            style="text-align:center;font-weight:bold;font-size:18px;">
            BINUS UNIVERSITY
        </td>
    </tr>

    <tr>
        <td colspan="{{ $showEventColumn ? 9 : 8 }}"
            style="text-align:center;font-weight:bold;font-size:16px;">
            LAPORAN PENYERAHAN SOUVENIR
        </td>
    </tr>

    <tr>
        <td colspan="{{ $showEventColumn ? 9 : 8 }}"></td>
    </tr>

    {{-- ============================= --}}
    {{-- INFORMASI --}}
    {{-- ============================= --}}

    <tr>
        <td><strong>Event</strong></td>
        <td>:</td>
        <td>{{ $event?->name ?? 'Semua Event' }}</td>
    </tr>

    <tr>
        <td><strong>Total Data</strong></td>
        <td>:</td>
        <td>{{ $receipts->count() }}</td>
    </tr>

    <tr>
        <td><strong>Tanggal Export</strong></td>
        <td>:</td>
        <td>{{ $exportedAt->format('d F Y H:i:s') }}</td>
    </tr>

    <tr>
        <td colspan="{{ $showEventColumn ? 9 : 8 }}"></td>
    </tr>

    {{-- ============================= --}}
    {{-- DATA PESERTA --}}
    {{-- ============================= --}}

    <tr>
        <th colspan="{{ $showEventColumn ? 9 : 8 }}"
            style="
                background:#1F4E78;
                color:white;
                font-size:14px;
                font-weight:bold;
                text-align:center;
            ">
            DATA PESERTA
        </th>
    </tr>

    <tr>

        <th>No</th>

        @if($showEventColumn)
            <th>Event</th>
        @endif

        <th>Participant ID</th>
        <th>Nama</th>
        <th>No HP</th>
        <th>Base Campus</th>
        <th>Souvenir</th>
        <th>Tanggal</th>
        <th>Petugas</th>

    </tr>

    @forelse($receipts as $index => $receipt)

        <tr>

            <td>{{ $index + 1 }}</td>

            @if($showEventColumn)
                <td>{{ optional(optional($receipt->participant)->event)->name }}</td>
            @endif

            <td>{{ optional($receipt->participant)->participant_code }}</td>

            <td>{{ optional($receipt->participant)->name }}</td>

            <td>{{ optional($receipt->participant)->phone }}</td>

            <td>{{ optional($receipt->participant)->campus }}</td>

            <td>{{ $receipt->receiptItems->pluck('item.name')->implode(', ') }}</td>

            <td>{{ \Carbon\Carbon::parse($receipt->received_at)->format('d-m-Y H:i') }}</td>

            <td>{{ optional($receipt->user)->name }}</td>

        </tr>

    @empty

        <tr>

            <td colspan="{{ $showEventColumn ? 9 : 8 }}"
                style="text-align:center;">
                Tidak ada data
            </td>

        </tr>

    @endforelse

</table>

</body>
</html>
