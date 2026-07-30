<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Laporan Absensi Peserta</title>
</head>

<body>

    <table>

        <tr>
            <td colspan="8" style="text-align:center; font-weight:bold; font-size:18px;">
                BINUS UNIVERSITY
            </td>
        </tr>
        <tr>
            <td colspan="8" style="text-align:center; font-weight:bold; font-size:16px;">
                LAPORAN ABSENSI PESERTA
            </td>
        </tr>
        <tr>
            <td colspan="8"></td>
        </tr>
        <tr>
            <td><strong>Event</strong></td>
            <td>:</td>
            <td colspan="6">{{ $event->name }}</td>
        </tr>
        <tr>
            <td><strong>Tanggal Export</strong></td>
            <td>:</td>
            <td colspan="6">
                {{ $exportedAt->format('d F Y H:i:s') }}
            </td>
        </tr>
        <tr>
            <td><strong>Total Peserta</strong></td>
            <td>:</td>
            <td>{{ $totalParticipants }}</td>
            <td></td>
            <td><strong>Sudah Hadir</strong></td>
            <td>:</td>
            <td>{{ $totalPresent }}</td>
            <td></td>
        </tr>
        <tr>
            <td><strong>Belum Hadir</strong></td>
            <td>:</td>
            <td>{{ $totalAbsent }}</td>
            <td colspan="5"></td>
        </tr>
        <tr>
            <td colspan="8"></td>
        </tr>
    </table>
    <table border="1">
        <thead>
            <tr style="font-weight:bold; text-align:center;">
                <th>No</th>
                <th>Participant ID</th>
                <th>Nama</th>
                <th>Kampus</th>
                <th>Jenis Peserta</th>
                <th>Status</th>
                <th>Jam Check In</th>
                <th>Petugas</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($participants as $index => $participant)
                <tr>
                    <td>{{ $index + 1 }}</td>

                    <td>{{ $participant->participant_code }}</td>
                    <td>{{ $participant->name }}</td>
                    <td>{{ $participant->campus }}</td>
                    <td>{{ $participant->participant_type }}</td>
                    <td>
                        @if ($participant->checkin)
                            Hadir
                        @else
                            Belum Hadir
                        @endif
                    </td>
                    <td>
                        @if ($participant->checkin)
                            {{ $participant->checkin->checkin_at->format('d-m-Y H:i:s') }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if ($participant->checkin && $participant->checkin->user)
                            {{ $participant->checkin->user->name }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align:center;">
                        Tidak ada data peserta.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
