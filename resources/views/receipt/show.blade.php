@extends('layouts.app')

@section('title', 'Tanda Terima')

@section('content')

<div class="container-fluid py-4 modern-bg">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4 px-2 flex-wrap">

        <div class="d-flex align-items-center mb-2 mb-md-0">

            <a href="{{ route('receipt.index') }}"
               class="btn btn-secondary btn-sm mr-3">

                <i class="fas fa-arrow-left"></i> Kembali

            </a>

            <h3 class="m-0 font-weight-bold text-dark">

                <i class="fas fa-receipt text-primary mr-2"></i>
                Penyerahan Souvenir

            </h3>

        </div>

        <span
            class="px-3 py-2 font-weight-bold text-secondary bg-light border rounded-pill"
            style="font-size: 14px;">

            <i class="fas fa-calendar-alt mr-1 text-primary"></i>
            {{ $event->name }}

        </span>

    </div>


    {{-- BARIS UTAMA --}}
    <div class="row align-items-stretch">


        {{-- =========================================
             KOLOM KIRI
             ========================================= --}}
        <div class="col-lg-5 col-md-12 mb-4 d-flex flex-column">


            {{-- =========================================
                 DATA PESERTA
                 ========================================= --}}
            <div class="card modern-card mb-4">

                <div class="card-body">

                    <h5 class="card-title font-weight-bold mb-3">

                        <i class="fas fa-user-circle text-primary mr-2"></i>
                        Data Peserta

                    </h5>


                    {{-- FORM CARI --}}
                    <div class="input-group modern-input-group mb-3">

                        <input
                            type="text"
                            id="keyword"
                            class="form-control"
                            placeholder="Ketik ID atau Nama...">

                        <div class="input-group-append">

                            <button
                                class="btn btn-primary px-4"
                                id="btn-search"
                                type="button"
                                onclick="searchParticipant()">

                                Cari

                            </button>

                        </div>

                    </div>


                    <hr class="my-3">


                    {{-- INFO PESERTA --}}
                    <div class="info-peserta-grid">


                        {{-- NAMA --}}
                        <div class="info-item">

                            <span class="info-label">
                                Nama Lengkap
                            </span>

                            <strong
                                class="info-value"
                                id="participant-name">

                                -

                            </strong>

                        </div>


                        {{-- PARTICIPANT ID --}}
                        <div class="info-item">

                            <span class="info-label">
                                Participant ID
                            </span>

                            <strong
                                class="info-value text-primary"
                                id="participant-code">

                                -

                            </strong>

                        </div>


                        {{-- KAMPUS --}}
                        <div class="info-item">

                            <span class="info-label">
                                Kampus
                            </span>

                            <strong
                                class="info-value"
                                id="participant-campus">

                                -

                            </strong>

                        </div>


                        {{-- EMAIL --}}
                        <div class="info-item email-info-item">

                            <span class="info-label">
                                Email
                            </span>

                            <strong
                                class="info-value"
                                id="participant-email">

                                -

                            </strong>

                        </div>


                    </div>


                    {{-- ID PESERTA --}}
                    <input
                        type="hidden"
                        id="participant-id"
                        value="">


                    {{-- ID EVENT --}}
                    <input
                        type="hidden"
                        id="event-id"
                        value="{{ $event->id }}">


                </div>

            </div>



            {{-- =========================================
                 PILIH SOUVENIR
                 ========================================= --}}
            <div class="card modern-card flex-grow-1 d-flex flex-column">

                <div class="card-body d-flex flex-column">

                    <h5 class="card-title font-weight-bold mb-3">

                        <i class="fas fa-gift text-warning mr-2"></i>
                        Pilih Souvenir

                    </h5>


                    <div
                        id="souvenir-list"
                        class="souvenir-container flex-grow-1">


                        @forelse($items as $item)

                            <label
                                class="modern-checkbox-item"
                                for="item{{ $item->id }}">

                                <div class="d-flex align-items-center">


                                    @if($items->count() == 1)

                                        <input
                                            type="hidden"
                                            name="items[]"
                                            value="{{ $item->id }}">

                                        <i
                                            class="fas fa-check-circle text-success fa-lg mr-3">
                                        </i>

                                    @else

                                        <input
                                            type="checkbox"
                                            name="items[]"
                                            value="{{ $item->id }}"
                                            id="item{{ $item->id }}"
                                            class="mr-3 custom-control-input-modern">

                                    @endif


                                    <span class="font-weight-bold">

                                        {{ $item->name }}

                                    </span>


                                </div>


                                <span class="badge badge-light text-muted">

                                    Stok: {{ $item->qty ?? '0' }}

                                </span>


                            </label>


                        @empty

                            <div
                                class="text-center py-5 text-muted h-100 d-flex flex-column justify-content-center">

                                <i
                                    class="fas fa-box-open fa-3x mb-3 text-light">
                                </i>

                                <span>
                                    Belum ada souvenir.
                                </span>

                            </div>

                        @endforelse


                    </div>

                </div>

            </div>


        </div>



        {{-- =========================================
             KOLOM KANAN
             ========================================= --}}
        <div class="col-lg-7 col-md-12 mb-4 d-flex flex-column">

            <div class="card modern-card flex-grow-1 d-flex flex-column">

                <div class="card-body d-flex flex-column">


                    <h5 class="card-title font-weight-bold mb-3">

                        <i class="fas fa-camera text-danger mr-2"></i>
                        Bukti Penyerahan

                    </h5>


                    {{-- CAMERA --}}
                    <div
                        class="camera-modern-wrapper flex-grow-1 position-relative bg-dark rounded-lg overflow-hidden mb-3 shadow-sm d-flex justify-content-center align-items-center"
                        style="min-height: 480px;">

                        <video
                            id="camera"
                            autoplay
                            playsinline
                            class="w-100 h-100"
                            style="object-fit: cover; position: absolute;">
                        </video>


                        <canvas
                            id="canvas"
                            class="w-100 h-100"
                            style="display:none; object-fit: cover; position: absolute;">
                        </canvas>


                        <button
                            type="button"
                            class="btn btn-light shadow-lg btn-floating-capture"
                            id="btn-capture">

                            <i class="fas fa-camera text-primary fa-lg"></i>

                        </button>


                    </div>


                    {{-- SUBMIT --}}
                    <button
                        type="button"
                        id="btn-submit"
                        class="btn btn-primary btn-lg btn-block modern-submit-btn shadow-sm mt-auto">

                        <i class="fas fa-check-circle mr-2"></i>
                        SERAHKAN SOUVENIR

                    </button>


                </div>

            </div>

        </div>


    </div>

</div>



<style>

/* =========================================
   FIX SIDEBAR
   ========================================= */

body,
html {

    height: 100%;

}


.wrapper {

    position: relative !important;
    min-height: 100vh !important;

}


.main-sidebar,
aside {

    position: absolute !important;

    top: 0 !important;
    bottom: 0 !important;

    height: 100% !important;

    min-height: 100% !important;

}


.content-wrapper {

    min-height: 100vh !important;

}



/* =========================================
   MODERN UI
   ========================================= */

.modern-bg {

    background-color: #f4f6f9;

}


.modern-card {

    border: none;

    border-radius: 16px;

    box-shadow:
        0 4px 20px rgba(0, 0, 0, 0.03);

    transition: transform 0.2s;

}


.badge-modern {

    font-size: 14px;

    border-radius: 8px;

    font-weight: 500;

}


.modern-input-group .form-control {

    border-radius: 8px 0 0 8px;

    border: 1px solid #e0e0e0;

    padding: 12px 15px;

    box-shadow: none;

}


.modern-input-group .form-control:focus {

    border-color: #4e73df;

}


.modern-input-group .btn {

    border-radius: 0 8px 8px 0;

    font-weight: 600;

}


.info-peserta-grid {

    display: grid;

    grid-template-columns: 1fr 1fr;

    gap: 15px;

}


.info-item {

    background: #f8f9fc;

    padding: 10px 15px;

    border-radius: 10px;

    display: flex;

    flex-direction: column;

}


.info-label {

    font-size: 12px;

    color: #858796;

    margin-bottom: 4px;

    text-transform: uppercase;

    letter-spacing: 0.5px;

}


.info-value {

    font-size: 15px;

    color: #3a3b45;

}


.souvenir-container {

    overflow-y: auto;

    padding-right: 5px;

}


.souvenir-container::-webkit-scrollbar {

    width: 6px;

}


.souvenir-container::-webkit-scrollbar-track {

    background: transparent;

}


.souvenir-container::-webkit-scrollbar-thumb {

    background: #d1d3e2;

    border-radius: 10px;

}


.modern-checkbox-item {

    display: flex;

    justify-content: space-between;

    align-items: center;

    padding: 12px 15px;

    border: 1px solid #eaecf4;

    border-radius: 10px;

    margin-bottom: 10px;

    cursor: pointer;

    transition: all 0.2s;

}


.modern-checkbox-item:hover {

    background-color: #f8f9fc;

    border-color: #d1d3e2;

}


.custom-control-input-modern {

    transform: scale(1.3);

}


.camera-modern-wrapper {

    border-radius: 16px;

}


.btn-floating-capture {

    position: absolute;

    bottom: 20px;

    width: 60px;

    height: 60px;

    border-radius: 50%;

    display: flex;

    align-items: center;

    justify-content: center;

    z-index: 10;

    transition: all 0.2s ease-in-out;

}


.btn-floating-capture:hover {

    transform: scale(1.1);

    background-color: #fff;

}


.modern-submit-btn {

    border-radius: 12px;

    padding: 14px;

    font-size: 16px;

    font-weight: 700;

    letter-spacing: 0.5px;

}

.email-info-item {
    min-width: 0;
}

#participant-email {
    white-space: normal;
    overflow-wrap: anywhere;
    word-break: break-word;
}

</style>



<script>

// =========================================
// WEBCAM
// =========================================

const video =
    document.getElementById('camera');

const canvas =
    document.getElementById('canvas');

const btnCapture =
    document.getElementById('btn-capture');

let stream = null;

let photoCaptured = false;

let photoData = null;



// =========================================
// START CAMERA
// =========================================

async function startCamera() {

    try {

        stream =
            await navigator.mediaDevices.getUserMedia({

                video: {
                    facingMode: "user"
                },

                audio: false

            });


        video.srcObject = stream;

        video.style.display = "block";

        canvas.style.display = "none";


    } catch (error) {

        console.error(error);

        Swal.fire(

            'Kamera Error',

            'Izin kamera ditolak atau kamera tidak ditemukan.',

            'error'

        );

    }

}



// =========================================
// STOP CAMERA
// =========================================

function stopCamera() {

    if (stream) {

        stream
            .getTracks()
            .forEach(function(track) {

                track.stop();

            });

        stream = null;

    }

}



// =========================================
// CAPTURE PHOTO
// =========================================

function capturePhoto() {

    const context =
        canvas.getContext('2d');


    canvas.width =
        video.videoWidth;

    canvas.height =
        video.videoHeight;


    context.drawImage(

        video,

        0,

        0,

        canvas.width,

        canvas.height

    );


    photoData =
        canvas.toDataURL(
            'image/jpeg',
            0.8
        );


    video.style.display = "none";

    canvas.style.display = "block";


    stopCamera();

    photoCaptured = true;


    btnCapture.innerHTML =
        '<i class="fas fa-undo text-danger fa-lg"></i>';

}



// =========================================
// RETAKE PHOTO
// =========================================

function retakePhoto() {

    photoCaptured = false;

    photoData = null;

    canvas.style.display = "none";

    startCamera();


    btnCapture.innerHTML =
        '<i class="fas fa-camera text-primary fa-lg"></i>';

}



// =========================================
// BUTTON CAPTURE
// =========================================

btnCapture.addEventListener(

    'click',

    function() {

        if (photoCaptured) {

            retakePhoto();

        } else {

            capturePhoto();

        }

    }

);



// =========================================
// SEARCH PESERTA
// =========================================

const btnSearch =
    document.getElementById('btn-search');

const keywordInput =
    document.getElementById('keyword');


btnSearch.addEventListener(

    'click',

    searchParticipant

);



// =========================================
// SEARCH DENGAN ENTER
// =========================================

keywordInput.addEventListener(

    'keydown',

    function(event) {

        if (event.key === 'Enter') {

            event.preventDefault();

            searchParticipant();

        }

    }

);



// =========================================
// FUNGSI SEARCH PESERTA
// =========================================

function searchParticipant() {

    const keyword =
        keywordInput.value.trim();


    if (keyword === '') {

        Swal.fire(

            'Perhatian',

            'Masukkan Participant ID atau Nama peserta.',

            'warning'

        );

        return;

    }


    btnSearch.disabled = true;

    btnSearch.innerHTML =
        '<i class="fas fa-spinner fa-spin"></i>';


    const url =

        "{{ route('receipt.search', $event->id) }}" +

        "?keyword=" +

        encodeURIComponent(keyword);


    fetch(

        url,

        {

            method: 'GET',

            headers: {

                'Accept':
                    'application/json',

                'X-Requested-With':
                    'XMLHttpRequest'

            }

        }

    )

    .then(function(response) {

        if (!response.ok) {

            throw new Error(

                'HTTP Error ' +
                response.status

            );

        }

        return response.json();

    })


    .then(function(response) {

        console.log(
            'Search response:',
            response
        );


        if (!response.success) {

            resetInfoPeserta();


            Swal.fire(

                'Tidak Ditemukan',

                response.message ||
                'Peserta tidak ditemukan.',

                'warning'

            );

            return;

        }


        const participant =
            response.participant;


        // SIMPAN ID PESERTA

        document.getElementById(
            'participant-id'
        ).value =
            participant.id || '';


        // TAMPILKAN NAMA

        document.getElementById(
            'participant-name'
        ).textContent =
            participant.name || '-';


        // TAMPILKAN PARTICIPANT ID

        document.getElementById(
            'participant-code'
        ).textContent =
            participant.participant_code || '-';


        // TAMPILKAN KAMPUS

        document.getElementById(
            'participant-campus'
        ).textContent =
            participant.campus || '-';


        // TAMPILKAN EMAIL

        document.getElementById(
            'participant-email'
        ).textContent =
            participant.email || '-';


        Swal.fire({

            icon: 'success',

            title: 'Peserta ditemukan',

            text: participant.name || '',

            timer: 1000,

            showConfirmButton: false

        });

    })


    .catch(function(error) {

        console.error(

            'Search error:',

            error

        );


        Swal.fire(

            'Gagal',

            'Terjadi kesalahan saat mencari peserta.',

            'error'

        );

    })


    .finally(function() {

        btnSearch.disabled = false;

        btnSearch.innerHTML = 'Cari';

    });

}



// =========================================
// SERAHKAN SOUVENIR
// =========================================

const btnSubmit =
    document.getElementById('btn-submit');


btnSubmit.addEventListener(

    'click',

    submitSouvenir

);



// =========================================
// FUNGSI SERAHKAN SOUVENIR
// =========================================

function submitSouvenir() {

    const participantId =

        document.getElementById(
            'participant-id'
        ).value;


    if (!participantId) {

        Swal.fire(

            'Perhatian',

            'Pilih peserta terlebih dahulu.',

            'warning'

        );

        return;

    }


    // AMBIL SOUVENIR

    const items = [];


    document

        .querySelectorAll(
            'input[name="items[]"]'
        )

        .forEach(function(input) {

            if (input.type === 'hidden') {

                items.push(input.value);

            }

            else if (

                input.type === 'checkbox' &&

                input.checked

            ) {

                items.push(input.value);

            }

        });


    if (items.length === 0) {

        Swal.fire(

            'Perhatian',

            'Pilih minimal satu souvenir.',

            'warning'

        );

        return;

    }


    // CEK FOTO

    if (!photoData) {

        Swal.fire(

            'Perhatian',

            'Ambil foto bukti terlebih dahulu.',

            'warning'

        );

        return;

    }


    btnSubmit.disabled = true;

    btnSubmit.innerHTML =
        '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...';


    // SIAPKAN DATA

    const formData =
        new FormData();


    formData.append(
        '_token',
        "{{ csrf_token() }}"
    );


    formData.append(
        'participant_id',
        participantId
    );


    formData.append(

        'name',

        document.getElementById(
            'participant-name'
        ).textContent

    );


    formData.append(

        'participant_code',

        document.getElementById(
            'participant-code'
        ).textContent

    );


    formData.append(

        'campus',

        document.getElementById(
            'participant-campus'
        ).textContent

    );


    formData.append(

        'email',

        document.getElementById(
            'participant-email'
        ).textContent

    );


    formData.append(

        'event_id',

        document.getElementById(
            'event-id'
        ).value

    );


    // SOUVENIR

    items.forEach(function(item) {

        formData.append(

            'items[]',

            item

        );

    });


    // FOTO

    formData.append(

        'photo',

        photoData

    );


    // KIRIM KE SERVER

    fetch(

        "{{ route('receipt.store') }}",

        {

            method: 'POST',

            body: formData,

            headers: {

                'Accept':
                    'application/json',

                'X-Requested-With':
                    'XMLHttpRequest'

            }

        }

    )

    .then(function(response) {

        return response.json();

    })


    .then(function(response) {

        console.log(

            'Submit response:',

            response

        );


        if (!response.success) {

            throw new Error(

                response.message ||

                'Terjadi kesalahan.'

            );

        }


        Swal.fire({

            icon: 'success',

            title: 'Berhasil',

            text: response.message,

            timer: 1500,

            showConfirmButton: false

        });


        resetForm();


        loadSouvenir();

    })


    .catch(function(error) {

        console.error(

            'Submit error:',

            error

        );


        Swal.fire({

            icon: 'error',

            title: 'Gagal',

            text:
                error.message ||
                'Terjadi kesalahan.'

        });

    })


    .finally(function() {

        btnSubmit.disabled = false;

        btnSubmit.innerHTML =

            '<i class="fas fa-check-circle mr-2"></i> SERAHKAN SOUVENIR';

    });

}



// =========================================
// RESET DATA PESERTA
// =========================================

function resetInfoPeserta() {

    document.getElementById(
        'participant-id'
    ).value = '';


    document.getElementById(
        'participant-name'
    ).textContent = '-';


    document.getElementById(
        'participant-code'
    ).textContent = '-';


    document.getElementById(
        'participant-campus'
    ).textContent = '-';


    document.getElementById(
        'participant-email'
    ).textContent = '-';

}



// =========================================
// RESET FORM
// =========================================

function resetForm() {

    keywordInput.value = '';

    resetInfoPeserta();


    document

        .querySelectorAll(
            'input[name="items[]"]'
        )

        .forEach(function(input) {

            if (input.type === 'checkbox') {

                input.checked = false;

            }

        });


    retakePhoto();

    keywordInput.focus();

}



// =========================================
// LOAD SOUVENIR
// =========================================

function loadSouvenir() {

    fetch(

        "{{ route('receipt.items', $event->id) }}",

        {

            method: 'GET',

            headers: {

                'Accept':
                    'application/json',

                'X-Requested-With':
                    'XMLHttpRequest'

            }

        }

    )

    .then(function(response) {

        return response.json();

    })


    .then(function(items) {

        const souvenirList =

            document.getElementById(
                'souvenir-list'
            );


        let html = '';


        if (!items || items.length === 0) {

            html = `

                <div
                    class="text-center py-5 text-muted h-100
                    d-flex flex-column justify-content-center">

                    <i
                        class="fas fa-box-open fa-3x
                        mb-3 text-light">
                    </i>

                    <span>
                        Habis.
                    </span>

                </div>

            `;

        }

        else {

            items.forEach(function(item) {

                const stock =

                    item.qty !== undefined

                        ? item.qty

                        : '0';


                html += `

                    <label
                        class="modern-checkbox-item"
                        for="item${item.id}">

                        <div
                            class="d-flex align-items-center">

                            <input
                                type="checkbox"
                                name="items[]"
                                value="${item.id}"
                                id="item${item.id}"
                                class="mr-3 custom-control-input-modern">


                            <span class="font-weight-bold">

                                ${item.name}

                            </span>

                        </div>


                        <span
                            class="badge badge-light text-muted">

                            Stok: ${stock}

                        </span>

                    </label>

                `;

            });

        }


        souvenirList.innerHTML = html;

    })


    .catch(function(error) {

        console.error(

            'Load souvenir error:',

            error

        );

    });

}



// =========================================
// DOCUMENT READY
// =========================================

document.addEventListener(

    'DOMContentLoaded',

    function() {


        // START KAMERA

        startCamera();


        // AUTO ADJUST SIDEBAR

        function samakanTinggi() {

            const tinggiHalaman =
                document.body.scrollHeight;


            const sidebar =
                document.querySelector(
                    '.main-sidebar'
                );


            if (sidebar) {

                sidebar.style.minHeight =
                    tinggiHalaman + 'px';

            }

        }


        samakanTinggi();


        setTimeout(

            samakanTinggi,

            1500

        );

    }

);

</script>

@endsection
