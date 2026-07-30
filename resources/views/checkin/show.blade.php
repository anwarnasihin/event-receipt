@extends('layouts.app')
@section('title', 'Absensi Peserta')
@section('content')

{{-- Tambahan CSS untuk DataTables --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">

<div class="container-fluid py-4 modern-bg">
    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4 px-2 flex-wrap">
        <div class="d-flex align-items-center mb-2 mb-md-0">
            <a href="{{ route('checkin.index') }}" class="btn btn-secondary btn-sm mr-3">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <h3 class="m-0 font-weight-bold text-dark">
                <i class="fas fa-user-check text-success mr-2"></i> Absensi Peserta
            </h3>
        </div>
        <div class="d-flex align-items-center flex-wrap">

    <span class="px-3 py-2 mr-2 mb-2 font-weight-bold text-secondary bg-light border rounded-pill">
        <i class="fas fa-calendar-alt mr-1 text-primary"></i>
        {{ $event->name }}
    </span>

    <a href="{{ route('checkin.export.pdf', $event->id) }}"
       class="btn btn-danger mr-2 mb-2">
        <i class="fas fa-file-pdf mr-1"></i>
        Export PDF
    </a>

    <a href="{{ route('checkin.export.excel', $event->id) }}"
       class="btn btn-success mr-2 mb-2">
        <i class="fas fa-file-excel mr-1"></i>
        Export Excel
    </a>

    <button
        class="btn btn-primary mb-2"
        data-toggle="modal"
        data-target="#modalManual">

        <i class="fas fa-user-plus mr-1"></i>
        Tambah Peserta

    </button>

</div>
    </div>

    {{-- KOTAK RINGKASAN (Stats) --}}
    <div class="row mb-4">
        <div class="col-md-4 mb-3 mb-md-0">
            <div class="card modern-card bg-primary text-white h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="font-weight-bold mb-1">{{ $totalParticipants }}</h3>
                        <p class="mb-0">Total Peserta</p>
                    </div>
                    <i class="fas fa-users fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3 mb-md-0">
            <div class="card modern-card bg-success text-white h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="font-weight-bold mb-1">{{ $checkedIn }}</h3>
                        <p class="mb-0">Sudah Hadir</p>
                    </div>
                    <i class="fas fa-user-check fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card modern-card bg-warning text-white h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="font-weight-bold mb-1">{{ $notCheckedIn }}</h3>
                        <p class="mb-0">Belum Hadir</p>
                    </div>
                    <i class="fas fa-user-clock fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- TABEL PESERTA (Full DataTables) --}}
    <div class="card modern-card">
        <div class="card-body">
            <div class="table-responsive mt-2">
                <table class="table table-bordered table-hover" id="participantTable" width="100%">
                    <thead class="thead-light">
                        <tr>
                            <th width="150">Participant ID</th>
                            <th>Nama Lengkap</th>
                            <th width="150">Kampus</th>
                            <th width="120" class="text-center">Status</th>
                            <th width="120" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Diisi oleh AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH PESERTA MANUAL (Dipindah ke luar agar terbaca sempurna) --}}
<div class="modal fade" id="modalManual" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-user-plus mr-2"></i> Tambah Peserta Manual
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Participant ID</label>
                    <input type="text" class="form-control" id="manual_participant_code" placeholder="Contoh: D125364">
                </div>
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" class="form-control" id="manual_name" placeholder="Nama lengkap peserta">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" class="form-control" id="manual_email" placeholder="email@binus.ac.id">
                </div>
                <div class="form-group">
                    <label>No HP</label>
                    <input type="text" class="form-control" id="manual_phone" placeholder="08xxxxxxxxxx">
                </div>
                <div class="form-group">
                    <label>Kampus</label>
                    <input type="text" class="form-control" id="manual_campus" placeholder="Contoh: Bekasi / Alam Sutera">
                </div>
                <div class="form-group">
                    <label>Jenis Peserta</label>
                    <select class="form-control" id="manual_type">
                        <option value="Guest">Guest</option>
                        <option value="Dosen">Dosen</option>
                        <option value="Staff">Staff</option>
                        <option value="Mahasiswa">Mahasiswa</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success" id="btnSaveManual">Simpan</button>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
/* =========================================================
   MODERN UI CSS
   ========================================================= */
.modern-bg { background-color: #f4f6f9; }
.modern-card {
    border: none;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
    transition: transform 0.2s;
}
.badge-modern { font-size: 14px; border-radius: 8px; font-weight: 500; }
.opacity-50 { opacity: 0.5; }

/* Custom DataTables Styling agar lebih rapi */
.dataTables_wrapper .dataTables_paginate .paginate_button {
    padding: 0 !important;
    margin-left: 2px !important;
    border: none !important;
}
.dataTables_wrapper .dataTables_length select {
    border-radius: 6px;
    border: 1px solid #ced4da;
    padding: 4px 30px 4px 12px !important;
    min-width: 70px;
}
.dataTables_wrapper .dataTables_filter input {
    border-radius: 6px;
    border: 1px solid #ced4da;
    padding: 4px 12px;
    margin-left: 8px;
}
</style>

@push('scripts')
{{-- Library DataTables JS --}}
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>

<script>
//=====================================
// 1. LOAD DATA & INITIALIZE DATATABLES
//=====================================
function loadParticipants(){
    $.get("{{ route('checkin.participants', $event->id) }}", function(data){
        if ($.fn.DataTable.isDataTable('#participantTable')) {
            $('#participantTable').DataTable().destroy();
        }

        let html = '';
        data.forEach(function(participant){
            let status = participant.checkin
                ? '<span class="badge badge-success px-3 py-1">Hadir</span>'
                : '<span class="badge badge-secondary px-3 py-1">Belum</span>';

            let action = participant.checkin
                ? '<button class="btn btn-success btn-sm" disabled><i class="fas fa-check"></i></button>'
                : `<button class="btn btn-primary btn-sm btn-checkin" data-id="${participant.id}">CHECK IN</button>`;

            html += `
                <tr>
                    <td class="align-middle">${participant.participant_code}</td>
                    <td class="align-middle font-weight-bold">${participant.name}</td>
                    <td class="align-middle">${participant.campus ?? '-'}</td>
                    <td class="align-middle text-center">${status}</td>
                    <td class="align-middle text-center">${action}</td>
                </tr>
            `;
        });

        $('#participantTable tbody').html(html);

        $('#participantTable').DataTable({
            "language": {
                "lengthMenu": "Tampilkan _MENU_ data",
                "search": "Cari :",
                "info": "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                "infoEmpty": "Menampilkan 0 - 0 dari 0 data",
                "infoFiltered": "(difilter dari _MAX_ total data)",
                "zeroRecords": "Data tidak ditemukan",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Berikutnya",
                    "previous": "Sebelumnya"
                }
            },
            "ordering": true,
            "pageLength": 10
        });
    });
}

//=====================================
// 2. PROSES CHECK IN VIA TABEL
//=====================================
$(document).on('click', '.btn-checkin', function () {
    let participantId = $(this).data('id');
    let button = $(this);

    button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

    $.ajax({
        url: "{{ route('checkin.store') }}",
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            participant_id: participantId
        },
        success: function (response) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: response.message,
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
                location.reload();
            });
        },
        error: function (xhr) {
            button.prop('disabled', false).html('CHECK IN');
            let msg = xhr.responseJSON?.message ?? 'Terjadi kesalahan.';
            Swal.fire({ icon: 'error', title: 'Gagal', text: msg });
        }
    });
});

//=====================================
// 3. JALANKAN SAAT HALAMAN DIBUKA
//=====================================
$(document).ready(function(){
    loadParticipants();
});

//=====================================
// 4. SIMPAN PESERTA MANUAL
//=====================================
$(document).on('click', '#btnSaveManual', function () {
    let btnSave = $(this);
    btnSave.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...');

    $.ajax({
        url: "{{ route('checkin.manual', ['event' => $event->id]) }}",
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            participant_code: $('#manual_participant_code').val(),
            name: $('#manual_name').val(),
            email: $('#manual_email').val(),
            phone: $('#manual_phone').val(),
            campus: $('#manual_campus').val(),
            participant_type: $('#manual_type').val(),
        },
        success: function (response) {
            btnSave.prop('disabled', false).html('Simpan');
            $('#modalManual').modal('hide');
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: response.message,
                timer: 1500,
                showConfirmButton: false
            }).then(function () {
                location.reload();
            });
        },
        error: function (xhr) {
            btnSave.prop('disabled', false).html('Simpan');
            let msg = 'Terjadi kesalahan.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                msg = xhr.responseJSON.message;
            }
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: msg
            });
        }
    });
});
</script>
@endpush
