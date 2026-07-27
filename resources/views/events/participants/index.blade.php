@extends('layouts.app')
@section('title', 'Master Peserta')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
@endpush

@section('content')
<div class="card">
    <!-- Header Dirapihkan & Ditambah Tombol Kembali -->
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">

        <!-- Bagian Kiri: Tombol Kembali & Judul -->
        <div class="d-flex align-items-center mb-2 mb-md-0">
            <a href="{{ route('events.index') }}" class="btn btn-secondary btn-sm mr-3">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <div>
                <h3 class="card-title mb-0 font-weight-bold">
                    <i class="fas fa-users mr-2"></i> Master Peserta
                </h3>
                <small class="text-muted">
                    Event: <strong>{{ $event->name ?? 'Detail Event' }}</strong>
                </small>
            </div>
        </div>

        <!-- Bagian Kanan: Kelompok Tombol Aksi -->
        <div>
            <a href="{{ route('events.participants.template', $event) }}" class="btn btn-info btn-sm mr-1" title="Download Template">
                <i class="fas fa-download"></i> <span class="d-none d-md-inline">Template</span>
            </a>
            <a href="{{ route('events.participants.import', $event) }}" class="btn btn-success btn-sm mr-1" title="Import Excel">
                <i class="fas fa-file-excel"></i> <span class="d-none d-md-inline">Import</span>
            </a>
            <a href="{{ route('events.participants.create', $event) }}" class="btn btn-primary btn-sm" title="Tambah Manual">
                <i class="fas fa-user-plus"></i> <span class="d-none d-md-inline">Manual</span>
            </a>
        </div>

    </div>

    <div class="card-body">
        <!-- Alert Sukses Dirapihkan -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            </div>
        @endif

        <!-- Table Responsive Wrapper -->
        <div class="table-responsive">
            <table id="participantTable" class="table table-bordered table-striped table-hover">
                <thead class="thead-light">
                    <tr>
                        <th width="120">Kode</th>
                        <th width="150">Participant ID</th>
                        <th>Nama</th>
                        <th>Campus</th>
                        <th width="100">Jenis</th>
                        <th width="100">Sumber</th>
                        <th width="150">Status Souvenir</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($participants as $participant)
                    <tr>
                        <td class="align-middle">{{ $participant->code }}</td>
                        <td class="align-middle">{{ $participant->participant_code }}</td>
                        <td class="align-middle">{{ $participant->name }}</td>
                        <td class="align-middle">{{ $participant->campus ?? '-' }}</td>

                        <td class="align-middle text-center">
                            @switch($participant->participant_type)
                                @case('Dosen')
                                    <span class="badge badge-primary px-2 py-1">Dosen</span>
                                    @break
                                @case('Staff')
                                    <span class="badge badge-success px-2 py-1">Staff</span>
                                    @break
                                @case('Mahasiswa')
                                    <span class="badge badge-warning px-2 py-1">Mahasiswa</span>
                                    @break
                                @default
                                    <span class="badge badge-secondary px-2 py-1">Guest</span>
                            @endswitch
                        </td>

                        <td class="align-middle text-center">
                            @if($participant->is_manual)
                                <span class="badge badge-info px-2 py-1">Manual</span>
                            @else
                                <span class="badge badge-secondary px-2 py-1">Import</span>
                            @endif
                        </td>

                        <td class="align-middle text-center">
                            @if($participant->souvenir_status)
                                <span class="badge badge-success px-2 py-1">
                                    <i class="fas fa-check-circle"></i> Sudah Ambil
                                </span>
                            @else
                                <span class="badge badge-warning px-2 py-1">
                                    <i class="fas fa-clock"></i> Belum Ambil
                                </span>
                            @endif
                        </td>

                        <td class="align-middle">
                            <!-- Tombol Aksi -->
                            <a href="{{ route('events.participants.show', [$event, $participant]) }}" class="btn btn-info btn-sm" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('events.participants.edit', [$event, $participant]) }}" class="btn btn-warning btn-sm" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>

                            <!-- Form Delete diganti dengan class untuk SweetAlert -->
                            <form action="{{ route('events.participants.destroy', [$event, $participant]) }}" method="POST" class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Panggil JS DataTables -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<script>
$(document).ready(function () {
    // Inisialisasi DataTables
    $('#participantTable').DataTable({
        "responsive": true,
        "autoWidth": false,
        "pageLength": 10,
        "language": {
            "search": "Cari :",
            "lengthMenu": "Tampilkan _MENU_ data",
            "info": "Menampilkan _START_ - _END_ dari _TOTAL_ data",
            "zeroRecords": "Data tidak ditemukan",
            "infoEmpty": "Tidak ada data yang tersedia",
            "paginate": {
                "previous": "Sebelumnya",
                "next": "Berikutnya"
            }
        }
    });

    // Mengganti onclick return confirm menjadi SweetAlert2
    $('.delete-form').submit(function(e) {
        e.preventDefault();
        let form = this;
        Swal.fire({
            title: 'Hapus Peserta?',
            text: 'Yakin ingin menghapus peserta ini? Data tidak dapat dikembalikan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>
@endpush
