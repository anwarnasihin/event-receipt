@extends('layouts.app')
@section('title', 'Absensi Peserta')
@section('content')

<div class="container-fluid py-4 modern-bg">
    {{-- HEADER (Sudah ditambahkan tombol kembali) --}}
    <div class="d-flex justify-content-between align-items-center mb-4 px-2 flex-wrap">
        <div class="d-flex align-items-center mb-2 mb-md-0">
            <a href="{{ route('events.index') }}" class="btn btn-secondary btn-sm mr-3">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <h3 class="m-0 font-weight-bold text-dark">
                <i class="fas fa-user-check text-success mr-2"></i> Absensi Peserta
            </h3>
        </div>
        <span class="badge badge-primary px-4 py-2 badge-modern">
            {{ $event->name }}
        </span>
    </div>

    {{-- Baris Utama --}}
    <div class="row align-items-stretch">

        {{-- ========================================== --}}
        {{-- KOLOM KIRI (Data Peserta & Souvenir) --}}
        {{-- ========================================== --}}
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

                        {{-- INI TAMBAHAN KOTAK NO HP --}}
                        <div class="info-item">
                            <span class="info-label">No HP</span>
                            <strong class="info-value" id="participant-phone">-</strong>
                        </div>
                    </div>
                    <input type="hidden" id="participant-id" value="">
                    <input type="hidden" id="event-id" value="{{ $event->id }}">
                </div>
            </div>



        </div>

        {{-- ========================================== --}}
        {{-- KOLOM KANAN (Kamera & Eksekusi) --}}
        {{-- ========================================== --}}
        <div class="col-lg-7 col-md-12 mb-4 d-flex flex-column">
            {{-- Card ini dibuat flex-grow-1 agar tingginya presisi dengan kolom kiri --}}
            <div class="card modern-card flex-grow-1 d-flex flex-column">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title font-weight-bold mb-3">
                        <i class="fas fa-user-check text-success mr-2"></i> Proses Absensi
                    </h5>

                    <div class="camera-modern-wrapper d-flex flex-column justify-content-center align-items-center bg-light rounded-lg mb-3"
                        style="min-height:480px;">
                        <i class="fas fa-user-check text-success"
                        style="font-size:90px;"></i>
                        <h3 class="mt-4 font-weight-bold">
                            Status Absensi
                        </h3>
                        <p class="text-muted mb-0">
                            Cari peserta kemudian klik
                            <strong>CHECK IN</strong>
                        </p>
                    </div>

                    {{-- Tombol Submit Besar --}}
                    <button type="button" id="btn-submit" class="btn btn-primary btn-lg btn-block modern-submit-btn shadow-sm mt-auto">
                        <i class="fas fa-check-circle mr-2"></i> CHECK IN
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

<style>
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

/* Container souvenir diubah agar bisa meregang */
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
        url: "{{ route('checkin.search', $event->id) }}",
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
// CHECK IN PESERTA
//=====================================
$('#btn-submit').click(function () {
    if ($('#participant-id').val() == '') {
        Swal.fire('Perhatian', 'Pilih peserta terlebih dahulu.', 'warning');
        return;
    }


    let btnSubmit = $(this);
    btnSubmit.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...');

    $.ajax({
        url: "{{ route('checkin.store') }}",
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            participant_id: $('#participant-id').val(),
        },
        success:function(response){
            btnSubmit.prop('disabled', false)
                .html('<i class="fas fa-check-circle mr-2"></i> CHECK IN');
            Swal.fire({
                icon:'success',
                title:'Berhasil',
                text:response.message,
                timer:1500,
                showConfirmButton:false
            });
            resetForm();
        },
        error: function (xhr) {
            btnSubmit.prop('disabled', false).html('<i class="fas fa-check-circle mr-2"></i> CHECK IN');
            let msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Terjadi kesalahan.';
            Swal.fire({ icon: 'error', title: 'Gagal', text: msg });
        }
    });
});

function resetForm() {
    $('#keyword').val('');
    resetInfoPeserta();
    $('#keyword').focus();
}

$(document).ready(function(){
    $('#keyword').focus();
});
</script>
@endpush
