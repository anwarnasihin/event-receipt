@extends('layouts.app')
@section('title', 'Tanda Terima')

@section('content')
<div class="container-fluid py-4 modern-bg">
    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4 px-2 flex-wrap">
        <div class="d-flex align-items-center mb-2 mb-md-0">
            <a href="{{ route('events.index') }}" class="btn btn-secondary btn-sm mr-3">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <h3 class="m-0 font-weight-bold text-dark">
                <i class="fas fa-receipt text-primary mr-2"></i> Penyerahan Souvenir
            </h3>
        </div>
        <span class="badge badge-primary px-4 py-2 badge-modern">
            {{ $event->name }}
        </span>
    </div>

    {{-- Baris Utama --}}
    <div class="row align-items-stretch">

        {{-- KOLOM KIRI (Data Peserta & Souvenir) --}}
        <div class="col-lg-5 col-md-12 mb-4 d-flex flex-column">

            {{-- 1. Card Data Peserta --}}
            <div class="card modern-card mb-4">
                <div class="card-body">
                    <h5 class="card-title font-weight-bold mb-3">
                        <i class="fas fa-user-circle text-primary mr-2"></i> Data Peserta
                    </h5>

                    {{-- Form Cari --}}
                    <div class="input-group modern-input-group mb-3">
                        <input type="text" id="keyword" class="form-control" placeholder="Ketik ID atau Nama...">
                        <div class="input-group-append">
                            <button class="btn btn-primary px-4" id="btn-search" type="button">Cari</button>
                        </div>
                    </div>

                    <hr class="my-3">

                    {{-- Info Peserta --}}
                    <div class="info-peserta-grid">
                        <div class="info-item">
                            <span class="info-label">Nama Lengkap</span>
                            <strong class="info-value" id="participant-name">-</strong>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Participant ID</span>
                            <strong class="info-value text-primary" id="participant-code">-</strong>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Kampus</span>
                            <strong class="info-value" id="participant-campus">-</strong>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Email</span>
                            <strong class="info-value text-truncate" id="participant-email" style="max-width: 150px;">-</strong>
                        </div>
                        <div class="info-item">
                            <span class="info-label">No HP</span>
                            <strong class="info-value" id="participant-phone">-</strong>
                        </div>
                    </div>
                    <input type="hidden" id="participant-id" value="">
                    <input type="hidden" id="event-id" value="{{ $event->id }}">
                </div>
            </div>

            {{-- 2. Card Souvenir --}}
            <div class="card modern-card flex-grow-1 d-flex flex-column">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title font-weight-bold mb-3">
                        <i class="fas fa-gift text-warning mr-2"></i> Pilih Souvenir
                    </h5>
                    <div id="souvenir-list" class="souvenir-container flex-grow-1">
                        @forelse($items as $item)
                        <label class="modern-checkbox-item" for="item{{ $item->id }}">
                            <div class="d-flex align-items-center">
                                <input type="checkbox" name="items[]" value="{{ $item->id }}" id="item{{ $item->id }}" class="mr-3 custom-control-input-modern">
                                <span class="font-weight-bold">{{ $item->name }}</span>
                            </div>
                            <span class="badge badge-light text-muted">Stok: {{ $item->qty ?? '0' }}</span>
                        </label>
                        @empty
                        <div class="text-center py-5 text-muted h-100 d-flex flex-column justify-content-center">
                            <i class="fas fa-box-open fa-3x mb-3 text-light"></i>
                            <span>Belum ada souvenir.</span>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN (Kamera & Eksekusi) --}}
        <div class="col-lg-7 col-md-12 mb-4 d-flex flex-column">
            <div class="card modern-card flex-grow-1 d-flex flex-column">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title font-weight-bold mb-3">
                        <i class="fas fa-camera text-danger mr-2"></i> Bukti Penyerahan
                    </h5>

                    <div class="camera-modern-wrapper flex-grow-1 position-relative bg-dark rounded-lg overflow-hidden mb-3 shadow-sm d-flex justify-content-center align-items-center" style="min-height: 480px;">
                        <video id="camera" autoplay playsinline class="w-100 h-100" style="object-fit: cover; position: absolute;"></video>
                        <canvas id="canvas" class="w-100 h-100" style="display:none; object-fit: cover; position: absolute;"></canvas>

                        <button type="button" class="btn btn-light shadow-lg btn-floating-capture" id="btn-capture">
                            <i class="fas fa-camera text-primary fa-lg"></i>
                        </button>
                    </div>

                    <button type="button" id="btn-submit" class="btn btn-primary btn-lg btn-block modern-submit-btn shadow-sm mt-auto">
                        <i class="fas fa-check-circle mr-2"></i> SERAHKAN SOUVENIR
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
/* =========================================================
   FIX SIDEBAR TERPOTONG (Versi Paling Kuat)
   ========================================================= */
body, html {
    height: 100%;
}

.wrapper {
    position: relative !important;
    min-height: 100vh !important;
}

.main-sidebar, aside {
    position: absolute !important;
    top: 0 !important;
    bottom: 0 !important;
    height: 100% !important;
    min-height: 100% !important;
}

.content-wrapper {
    min-height: 100vh !important;
}

/* =========================================================
   MODERN UI CSS
   ========================================================= */
.modern-bg {
    background-color: #f4f6f9;
}

.modern-card {
    border: none;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
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
.souvenir-container::-webkit-scrollbar { width: 6px; }
.souvenir-container::-webkit-scrollbar-track { background: transparent; }
.souvenir-container::-webkit-scrollbar-thumb { background: #d1d3e2; border-radius: 10px; }

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
</style>
@endsection

@push('scripts')
<script>
//=====================================
// SEARCH PESERTA
//=====================================
$('#btn-search').click(function(){
    let keyword = $('#keyword').val();
    let btnSearch = $(this);
    btnSearch.html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);

    $.ajax({
        url: "{{ route('receipt.search', $event->id) }}",
        type: "GET",
        data: { keyword: keyword },
        success: function (response) {
            btnSearch.html('Cari').prop('disabled', false);

            if (response.success) {
                $('#participant-id').val(response.participant.id);
                $('#participant-name').text(response.participant.name);
                $('#participant-code').text(response.participant.participant_code);
                $('#participant-campus').text(response.participant.campus);
                $('#participant-email').text(response.participant.email);
                $('#participant-phone').text(response.participant.phone || '-');
            } else {
                resetInfoPeserta();
                Swal.fire({ icon: 'warning', title: 'Tidak Ditemukan', text: response.message });
            }
        },
        error: function() {
            btnSearch.html('Cari').prop('disabled', false);
            resetInfoPeserta();
            Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan pada server' });
        }
    });
});

$('#keyword').keypress(function(e){
    if(e.which == 13) { $('#btn-search').click(); return false; }
});

function resetInfoPeserta() {
    $('#participant-id').val('');
    $('#participant-name').text('-');
    $('#participant-code').text('-');
    $('#participant-campus').text('-');
    $('#participant-email').text('-');
    $('#participant-phone').text('-');
}

//=====================================
// WEBCAM
//=====================================
const video = document.getElementById('camera');
const canvas = document.getElementById('canvas');
let stream = null;
let photoCaptured = false;
let photoData = null;

async function startCamera(){
    try {
        stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: "user" }, audio: false });
        video.srcObject = stream;
        video.style.display = "block";
        canvas.style.display = "none";
    } catch(error) {
        console.error(error);
        Swal.fire('Kamera Error', 'Izin kamera ditolak atau tidak ditemukan.', 'error');
    }
}

function stopCamera(){
    if(stream){
        stream.getTracks().forEach(track => track.stop());
        stream = null;
    }
}

function capturePhoto(){
    const context = canvas.getContext('2d');
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    context.drawImage(video, 0, 0, canvas.width, canvas.height);

    photoData = canvas.toDataURL('image/jpeg', 0.8);
    video.style.display = "none";
    canvas.style.display = "block";
    stopCamera();
    photoCaptured = true;

    $('#btn-capture').html('<i class="fas fa-undo text-danger fa-lg"></i>');
}

function retakePhoto(){
    photoCaptured = false;
    photoData = null;
    startCamera();
    $('#btn-capture').html('<i class="fas fa-camera text-primary fa-lg"></i>');
}

$('#btn-capture').click(function(){
    if(photoCaptured) retakePhoto();
    else capturePhoto();
});

//=====================================
// SERAHKAN SOUVENIR
//=====================================
$('#btn-submit').click(function () {
    if ($('#participant-id').val() == '') {
        Swal.fire('Perhatian', 'Pilih peserta terlebih dahulu.', 'warning');
        return;
    }
    if ($('input[name="items[]"]:checked').length == 0) {
        Swal.fire('Perhatian', 'Pilih minimal satu souvenir.', 'warning');
        return;
    }
    if (photoData == null) {
        Swal.fire('Perhatian', 'Ambil foto bukti terlebih dahulu.', 'warning');
        return;
    }

    let items = [];
    $('input[name="items[]"]:checked').each(function () { items.push($(this).val()); });

    let btnSubmit = $(this);
    btnSubmit.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...');

    $.ajax({
        url: "{{ route('receipt.store') }}",
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            participant_id: $('#participant-id').val(),
            event_id: $('#event-id').val(),
            items: items,
            photo: photoData
        },
        success: function (response) {
            btnSubmit.prop('disabled', false).html('<i class="fas fa-check-circle mr-2"></i> SERAHKAN SOUVENIR');
            Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message, timer: 1500, showConfirmButton: false });
            resetForm();
            loadSouvenir();
        },
        error: function (xhr) {
            btnSubmit.prop('disabled', false).html('<i class="fas fa-check-circle mr-2"></i> SERAHKAN SOUVENIR');
            let msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Terjadi kesalahan.';
            Swal.fire({ icon: 'error', title: 'Gagal', text: msg });
        }
    });
});

function resetForm(){
    $('#keyword').val('');
    resetInfoPeserta();
    $('input[name="items[]"]').prop('checked', false);
    retakePhoto();
    $('#keyword').focus();
}

function loadSouvenir(){
    $.get("{{ route('receipt.items', $event->id) }}", function(items){
        let html = '';
        if(items.length === 0){
            html = `<div class="text-center py-5 text-muted h-100 d-flex flex-column justify-content-center"><i class="fas fa-box-open fa-3x mb-3 text-light"></i><span>Habis.</span></div>`;
        } else {
            items.forEach(function(item){
                let stockVal = item.qty !== undefined ? item.qty : '0';
                html += `
                <label class="modern-checkbox-item" for="item${item.id}">
                    <div class="d-flex align-items-center">
                        <input type="checkbox" name="items[]" value="${item.id}" id="item${item.id}" class="mr-3 custom-control-input-modern">
                        <span class="font-weight-bold">${item.name}</span>
                    </div>
                    <span class="badge badge-light text-muted">Stok: ${stockVal}</span>
                </label>`;
            });
        }
        $('#souvenir-list').html(html);
    });
}

$(document).ready(function() {
    startCamera();

    // Auto-adjust sidebar agar selalu sama panjang dengan konten
    function samakanTinggi() {
        let tinggiHalaman = $(document).height();
        $('.main-sidebar').css('min-height', tinggiHalaman + 'px');
    }
    samakanTinggi();
    setTimeout(samakanTinggi, 1500);
});
</script>
@endpush
