<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tanda Terima Pengambilan Souvenir</title>
</head>

<body style="margin:0; padding:0; background-color:#f4f6f8; font-family:Arial, Helvetica, sans-serif; color:#333333;">

@php
    $location = $receipt->user->location ?? 'BINUS';
    $senderName = 'LSC BINUS ' . $location;
@endphp

<table
    width="100%"
    cellpadding="0"
    cellspacing="0"
    border="0"
    style="width:100%; background-color:#f4f6f8; padding:30px 15px;"
>
    <tr>
        <td align="center" valign="top">

            <!-- CONTAINER -->
            <table
                width="650"
                cellpadding="0"
                cellspacing="0"
                border="0"
                style="
                    width:650px;
                    max-width:650px;
                    background-color:#ffffff;
                    border-radius:8px;
                    overflow:hidden;
                "
            >

                <!-- ========================= -->
                <!-- HEADER -->
                <!-- ========================= -->
                <tr>
                    <td
                        style="
                            padding:15px 25px;
                            border-bottom:1px solid #eeeeee;
                        "
                    >

                        <table
                            width="100%"
                            cellpadding="0"
                            cellspacing="0"
                            border="0"
                            style="width:100%;"
                        >
                            <tr>

                                <!-- LOGO BINUS -->
                                <td
                                    width="150"
                                    align="left"
                                    valign="middle"
                                    style="width:150px;"
                                >

                                    <img
                                        src="{{ $message->embed(public_path('images/BINUSUNIVERSITY.png')) }}"
                                        width="90"
                                        alt="BINUS University"
                                        style="
                                            display:block;
                                            width:90px;
                                            max-width:90px;
                                            height:auto;
                                            border:0;
                                            outline:none;
                                            text-decoration:none;
                                        "
                                    >

                                </td>

                                <!-- NAMA LOKASI -->
                                <td
                                    align="right"
                                    valign="middle"
                                >

                                    <span
                                        style="
                                            font-family:Arial, Helvetica, sans-serif;
                                            font-size:14px;
                                            line-height:20px;
                                            font-weight:bold;
                                            color:#005baa;
                                        "
                                    >
                                        {{ $senderName }}
                                    </span>

                                </td>

                            </tr>
                        </table>

                    </td>
                </tr>


                <!-- ========================= -->
                <!-- TITLE -->
                <!-- ========================= -->
                <tr>
                    <td
                        style="
                            padding:35px 35px 15px 35px;
                        "
                    >

                        <div
                            style="
                                font-size:24px;
                                line-height:30px;
                                font-weight:bold;
                                color:#222222;
                            "
                        >
                            Tanda Terima Pengambilan Souvenir
                        </div>

                        <div
                            style="
                                height:4px;
                                width:55px;
                                background-color:#005baa;
                                margin-top:12px;
                                line-height:4px;
                                font-size:0;
                            "
                        >
                            &nbsp;
                        </div>

                    </td>
                </tr>


                <!-- ========================= -->
                <!-- GREETING -->
                <!-- ========================= -->
                <tr>
                    <td
                        style="
                            padding:10px 35px 20px 35px;
                            font-size:15px;
                            line-height:26px;
                        "
                    >

                        <p style="margin:0 0 15px 0;">
                            Yth.
                            <strong>
                                {{ $receipt->participant->name }}
                            </strong>,
                        </p>

                        <p style="margin:0;">
                            Terima kasih telah hadir dan berpartisipasi dalam kegiatan
                            <strong>
                                {{ $receipt->participant->event->name ?? '-' }}
                            </strong>.
                        </p>

                        <p style="margin:15px 0 0 0;">
                            Melalui email ini kami menyampaikan tanda terima bahwa
                            Bapak/Ibu telah menerima souvenir.
                        </p>

                    </td>
                </tr>


                <!-- ========================= -->
                <!-- DETAIL PESERTA -->
                <!-- ========================= -->
                <tr>
                    <td
                        style="
                            padding:10px 35px 10px 35px;
                        "
                    >

                        <div
                            style="
                                font-size:17px;
                                line-height:22px;
                                font-weight:bold;
                                color:#222222;
                                margin-bottom:12px;
                            "
                        >
                            Detail Peserta
                        </div>

                        <table
                            width="100%"
                            cellpadding="0"
                            cellspacing="0"
                            border="0"
                            style="
                                width:100%;
                                font-size:14px;
                                border-collapse:collapse;
                            "
                        >

                            <tr>
                                <td
                                    width="180"
                                    style="
                                        width:180px;
                                        padding:8px 0;
                                        color:#666666;
                                    "
                                >
                                    Participant ID
                                </td>

                                <td
                                    style="
                                        padding:8px 0;
                                        font-weight:bold;
                                    "
                                >
                                    {{ $receipt->participant->participant_code }}
                                </td>
                            </tr>

                            <tr>
                                <td
                                    style="
                                        padding:8px 0;
                                        color:#666666;
                                    "
                                >
                                    Nama
                                </td>

                                <td
                                    style="
                                        padding:8px 0;
                                        font-weight:bold;
                                    "
                                >
                                    {{ $receipt->participant->name }}
                                </td>
                            </tr>

                            <tr>
                                <td
                                    style="
                                        padding:8px 0;
                                        color:#666666;
                                    "
                                >
                                    Kampus
                                </td>

                                <td style="padding:8px 0;">
                                    {{ $receipt->participant->campus }}
                                </td>
                            </tr>

                            <tr>
                                <td
                                    style="
                                        padding:8px 0;
                                        color:#666666;
                                    "
                                >
                                    Waktu Pengambilan
                                </td>

                                <td style="padding:8px 0;">
                                    {{ \Carbon\Carbon::parse($receipt->received_at)->format('d F Y, H:i:s') }}
                                </td>
                            </tr>

                        </table>

                    </td>
                </tr>


                <!-- ========================= -->
                <!-- SOUVENIR -->
                <!-- ========================= -->
                <tr>
                    <td
                        style="
                            padding:25px 35px;
                        "
                    >

                        <div
                            style="
                                font-size:17px;
                                line-height:22px;
                                font-weight:bold;
                                color:#222222;
                                margin-bottom:12px;
                            "
                        >
                            Souvenir yang Diterima
                        </div>

                        <table
                            width="100%"
                            cellpadding="0"
                            cellspacing="0"
                            border="0"
                            style="
                                width:100%;
                                border-collapse:collapse;
                                background-color:#f7f9fb;
                            "
                        >

                            @foreach ($receipt->receiptItems as $receiptItem)

                                <tr>

                                    <td
                                        style="
                                            padding:12px 15px;
                                            border-bottom:1px solid #e5e5e5;
                                        "
                                    >

                                        <span
                                            style="
                                                color:#20a464;
                                                font-weight:bold;
                                            "
                                        >
                                            ✓
                                        </span>

                                        <span style="margin-left:8px;">
                                            {{ $receiptItem->item->name ?? '-' }}
                                        </span>

                                    </td>

                                </tr>

                            @endforeach

                        </table>

                    </td>
                </tr>


                <!-- ========================= -->
                <!-- INFORMATION -->
                <!-- ========================= -->
                <tr>
                    <td
                        style="
                            padding:0 35px 25px 35px;
                        "
                    >

                        <table
                            width="100%"
                            cellpadding="0"
                            cellspacing="0"
                            border="0"
                            style="
                                width:100%;
                                background-color:#eef6ff;
                                border-left:4px solid #005baa;
                            "
                        >

                            <tr>

                                <td
                                    style="
                                        padding:15px 18px;
                                        font-size:13px;
                                        line-height:21px;
                                        color:#555555;
                                    "
                                >
                                    Bukti foto penyerahan souvenir terlampir pada email ini
                                    sebagai dokumentasi penerimaan souvenir.
                                </td>

                            </tr>

                        </table>

                    </td>
                </tr>


                <!-- ========================= -->
                <!-- CLOSING -->
                <!-- ========================= -->
                <tr>
                    <td
                        style="
                            padding:5px 35px 35px 35px;
                            font-size:14px;
                            line-height:24px;
                        "
                    >

                        <p style="margin:0 0 15px 0;">
                            Terima kasih atas kehadiran dan partisipasi Bapak/Ibu.
                        </p>

                        <p style="margin:0;">
                            Salam,
                        </p>

                        <p
                            style="
                                margin:5px 0 0 0;
                                font-weight:bold;
                                color:#005baa;
                            "
                        >
                            {{ $senderName }}
                        </p>

                        <p
                            style="
                                margin:2px 0 0 0;
                                color:#777777;
                            "
                        >
                            Bina Nusantara University
                        </p>

                    </td>
                </tr>


                <!-- ========================= -->
                <!-- FOOTER -->
                <!-- ========================= -->
                <tr>

                    <td
                        align="center"
                        style="
                            padding:18px 25px;
                            background-color:#f1f3f5;
                            font-size:11px;
                            line-height:17px;
                            color:#888888;
                        "
                    >

                        Email ini dikirim secara otomatis oleh sistem
                        Event Receipt LSC Lokasi.

                        <br>

                        Mohon tidak membalas email ini.

                    </td>

                </tr>

            </table>

        </td>
    </tr>
</table>

</body>
</html>
