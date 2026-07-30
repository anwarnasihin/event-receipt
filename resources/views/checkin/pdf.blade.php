<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Absensi Peserta</title>
    <style>
        @page {
            margin: 20px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #000;
        }

        .title {
            text-align: center;
            font-size: 22px;
            font-weight: bold;
        }

        .subtitle {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }

        .info-table td {
            padding: 4px;
            vertical-align: top;
        }

        .label {
            width: 140px;
            font-weight: bold;
        }

        .separator {
            width: 10px;
        }

        .summary {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .summary td {
            padding: 4px;
        }

        table.report {
            width: 100%;
            border-collapse: collapse;
        }

        table.report th {
            background: #1F4E78;
            color: #ffffff;
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
            font-size: 10px;
        }

        table.report td {
            border: 1px solid #000;
            padding: 5px;
            font-size: 10px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .hadir {
            font-weight: bold;
        }

        .belum {
            color: #666666;
        }

        .footer {
            margin-top: 25px;
            text-align: right;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="title">
        BINUS UNIVERSITY
    </div>
    <div class="subtitle">
        LAPORAN ABSENSI PESERTA
    </div>
    <table class="info-table">
        <tr>
            <td class="label">Event</td>
            <td class="separator">:</td>
            <td>{{ $event->name }}</td>
            <td class="label">Tanggal Export</td>
            <td class="separator">:</td>
            <td>{{ $exportedAt->format('d F Y H:i:s') }}</td>
        </tr>
        <tr>
            <td class="label">Kode Event</td>
            <td class="separator">:</td>
            <td>{{ $event->code }}</td>
            <td class="label">Tanggal Event</td>
            <td class="separator">:</td>
            <td>
                {{ $event->event_date
                    ? \Carbon\Carbon::parse($event->event_date)->format('d F Y')
                    : '-' }}
            </td>
        </tr>
        <tr>
            <td class="label">Lokasi</td>
            <td class="separator">:</td>
            <td>{{ $event->location ?: '-' }}</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </table>
    <table class="summary">
        <tr>
            <td class="label">Total Peserta</td>
            <td class="separator">:</td>
            <td>{{ $totalParticipants }}</td>
            <td class="label">Sudah Hadir</td>
            <td class="separator">:</td>
            <td>{{ $totalPresent }}</td>
            <td class="label">Belum Hadir</td>
            <td class="separator">:</td>
            <td>{{ $totalAbsent }}</td>
        </tr>
    </table>
    <table class="report">
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="12%">Participant ID</th>
                <th width="18%">Nama</th>
                <th width="12%">Kampus</th>
                <th width="12%">Jenis Peserta</th>
                <th width="10%">Status</th>
                <th width="18%">Jam Check In</th>
                <th width="14%">Petugas</th>
            </tr>
        </thead>
        <tbody>
            @forelse($participants as $index => $participant)
                <tr>
                    <td class="text-center">
                        {{ $index + 1 }}
                    </td>
                    <td>
                        {{ $participant->participant_code }}
                    </td>
                    <td>
                        {{ $participant->name }}
                    </td>
                    <td>
                        {{ $participant->campus }}
                    </td>
                    <td>
                        {{ $participant->participant_type }}
                    </td>
                    <td class="text-center">
                        @if($participant->checkin)
                            <span class="hadir">
                                Hadir
                            </span>
                        @else
                            <span class="belum">
                                Belum Hadir
                            </span>
                        @endif
                    </td>
                    <td>
                        @if($participant->checkin)
                            {{ $participant->checkin->checkin_at->format('d-m-Y H:i:s') }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($participant->checkin && $participant->checkin->user)
                            {{ $participant->checkin->user->name }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">
                        Tidak ada data peserta.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="footer">
        Dicetak pada
        {{ $exportedAt->format('d F Y H:i:s') }}

    </div>
</body>
</html>
