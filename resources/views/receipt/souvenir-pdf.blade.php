<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">

    <style>
        @page {
            size: A4 portrait;
            margin: 0;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            width: 210mm;
            height: 297mm;
        }

        body {
            font-family: "Times New Roman", Times, serif;
            position: relative;
        }

        .page {
            position: relative;
            width: 210mm;
            height: 297mm;

            background-image: url("{{ public_path('images/BGTanter.png') }}");
            background-size: 210mm 297mm;
            background-position: center;
            background-repeat: no-repeat;
        }

        /*
        |--------------------------------------------------------------------------
        | AREA KONTEN
        |--------------------------------------------------------------------------
        */

        .content {
            position: absolute;
            left: 35mm;
            right: 18mm;
            top: 55mm;
            bottom: 35mm;
            color: #000;

            text-align: left;
        }


        /*
        |--------------------------------------------------------------------------
        | JUDUL
        |--------------------------------------------------------------------------
        */

        .title {
            text-align: center;

            font-family: "Times New Roman", Times, serif;
            font-size: 20px;
            font-weight: bold;

            margin-bottom: 2mm;
        }

        .event-name {
            text-align: center;

            font-family: "Times New Roman", Times, serif;
            font-size: 17px;
            font-weight: bold;

            margin-bottom: 7mm;
        }


        /*
        |--------------------------------------------------------------------------
        | FOTO PESERTA
        |--------------------------------------------------------------------------
        */

        .photo-container {
            text-align: center;

            margin-bottom: 7mm;
        }

        .photo {
            width: 75mm;
            height: 65mm;

            object-fit: cover;

            display: inline-block;
        }


        /*
        |--------------------------------------------------------------------------
        | ISI
        |--------------------------------------------------------------------------
        */

        .text {
            font-size: 15px;
            line-height: 1.7;
            text-align: justify;
        }


        /*
        |--------------------------------------------------------------------------
        | DAFTAR SOUVENIR
        |--------------------------------------------------------------------------
        */

        .souvenir-title {

            margin-top: 5mm;
            margin-bottom: 2mm;

            font-family: "Times New Roman", Times, serif;

            font-size: 12px;

            font-weight: bold;
        }

        .souvenir-list {
            margin-top: 2mm;
            padding-left: 7mm;
            font-size: 15px;
        }

        .souvenir-list li {
            margin-bottom: 2mm;
        }


        /*
        |--------------------------------------------------------------------------
        | PERNYATAAN TANGGUNG JAWAB
        |--------------------------------------------------------------------------
        */

        .statement {

            margin-top: 6mm;
            font-family: "Times New Roman", Times, serif;
            font-size: 15px;
            line-height: 1.7;
            text-align: justify;

        }


        /*
        |--------------------------------------------------------------------------
        | TANGGAL & TANDA TANGAN
        |--------------------------------------------------------------------------
        */

        .signature {
            margin-top: 10mm;
            text-align: right;
            font-size: 15px;
        }

        .signature .name {
            margin-top: 12mm;
            font-weight: bold;
        }

    </style>

</head>

<body>

<div class="page">

    <div class="content">


        {{-- ========================================================= --}}
        {{-- JUDUL --}}
        {{-- ========================================================= --}}

        <div class="title">
            TANDA TERIMA
        </div>


        {{-- ========================================================= --}}
        {{-- NAMA EVENT --}}
        {{-- ========================================================= --}}

        <div class="event-name">

            {{ $receipt->participant->event->name ?? 'NAMA EVENT' }}

        </div>


        {{-- ========================================================= --}}
        {{-- FOTO PESERTA --}}
        {{-- ========================================================= --}}

        @if($receipt->photo)

            <div class="photo-container">

                <img
                    class="photo"
                    src="{{ Storage::disk('public')->path($receipt->photo) }}"
                >

            </div>

        @endif


        {{-- ========================================================= --}}
        {{-- PERNYATAAN PENERIMAAN --}}
        {{-- ========================================================= --}}

        <div class="text">
            Saya
            <strong>{{ $receipt->participant->name }}</strong>,
            kode dosen
            <strong>{{ $receipt->participant->participant_code }}</strong>,
            dengan ini menyatakan bahwa saya telah menerima souvenir berupa:
        </div>
        <ol class="souvenir-list">
            @foreach($receipt->receiptItems as $receiptItem)
                <li>
                    {{ $receiptItem->item->name ?? '-' }}
                </li>
            @endforeach
        </ol>

        <div class="statement">
            Adapun souvenir ini telah saya terima dalam kondisi baik.
        </div>


        {{-- ========================================================= --}}
        {{-- LOKASI PETUGAS + TANGGAL --}}
        {{-- ========================================================= --}}

        <div class="signature">

            {{ $receipt->user->location ?? $receipt->participant->campus ?? 'Bekasi' }},
            {{ \Carbon\Carbon::parse($receipt->received_at)->translatedFormat('d F Y H:i') }}

            <div class="name">

                {{ $receipt->participant->name }}

            </div>

        </div>


    </div>

</div>

</body>
</html>
