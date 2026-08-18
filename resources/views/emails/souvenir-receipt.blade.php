<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Tanda Terima Pengambilan Souvenir</title>
</head>

<body style="font-family: Arial, sans-serif; line-height: 1.6;">

    <h2>Tanda Terima Pengambilan Souvenir</h2>

    <p>
        Yth. {{ $receipt->participant->name }},
    </p>

    <p>
        Terima kasih telah melakukan pengambilan souvenir.
    </p>

    <h3>Detail Peserta</h3>

    <table>
        <tr>
            <td><strong>Participant ID</strong></td>
            <td>: {{ $receipt->participant->participant_code }}</td>
        </tr>

        <tr>
            <td><strong>Nama</strong></td>
            <td>: {{ $receipt->participant->name }}</td>
        </tr>

        <tr>
            <td><strong>Email</strong></td>
            <td>: {{ $receipt->participant->email }}</td>
        </tr>

        <tr>
            <td><strong>Kampus</strong></td>
            <td>: {{ $receipt->participant->campus }}</td>
        </tr>

        <tr>
            <td><strong>Waktu Pengambilan</strong></td>
            <td>: {{ $receipt->received_at }}</td>
        </tr>
    </table>

    <h3>Souvenir yang Diterima</h3>

    <ul>
        @foreach ($receipt->receiptItems as $receiptItem)
            <li>
                {{ $receiptItem->item->name ?? '-' }}
            </li>
        @endforeach
    </ul>

    <p>
        Terima kasih.
    </p>

    <p>
        <strong>LSC Bekasi</strong>
    </p>

</body>
</html>
