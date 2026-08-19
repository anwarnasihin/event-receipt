@extends('layouts.app')
@section('title', 'Master Event')

<!-- Tambahkan CSS DataTables jika belum ada di template utama -->
@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
@endpush

@section('content')
<div class="card">
    <!-- Header Dirapihkan -->
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0 font-weight-bold">
            <i class="fas fa-calendar-alt mr-2"></i> Master Event
        </h3>
        <a href="{{ route('events.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Event
        </a>
    </div>

    <div class="card-body">
        <!-- Alert Session -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            </div>
        @endif

        <!-- Table Responsive Wrapper -->
        <div class="table-responsive">
            <table id="eventTable" class="table table-bordered table-striped table-hover">
                <thead class="thead-light">
                    <tr>
                        <th width="120">Kode</th>
                        <th>Nama Event</th>
                        <th width="150">Tanggal</th>
                        <th>Lokasi</th>
                        <th width="180">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($events as $event)
                    <tr>
                        <td class="align-middle">{{ $event->code }}</td>
                        <td class="align-middle">{{ $event->name }}</td>
                        <td class="align-middle">{{ $event->event_date }}</td>
                        <td class="align-middle">{{ $event->location }}</td>
                        <td class="align-middle">
                            <!-- Tombol Aksi Dirapatkan agar tidak memakan banyak tempat -->
                            <a href="{{ route('events.items.index', $event) }}" class="btn btn-info btn-sm" title="Master Item">
                                <i class="fas fa-gift"></i>
                            </a>
                            <a href="{{ route('events.participants.index', $event) }}" class="btn btn-success btn-sm" title="Peserta">
                                <i class="fas fa-users"></i>
                            </a>
                            <a href="{{ route('events.edit', $event) }}" class="btn btn-warning btn-sm" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>

                            {{-- Aktif / Nonaktif Event --}}
                            <form action="{{ route('events.toggle-status', $event) }}"
                                method="POST"
                                class="d-inline toggle-status-form">

                                @csrf

                                <button type="button"
                                        class="btn btn-sm {{ $event->status ? 'btn-success' : 'btn-secondary' }} btn-toggle-status"
                                        title="{{ $event->status ? 'Nonaktifkan Event' : 'Aktifkan Event' }}"
                                        data-event-name="{{ $event->name }}"
                                        data-status="{{ $event->status ? 'active' : 'inactive' }}">

                                    <i class="fas {{ $event->status ? 'fa-eye' : 'fa-eye-slash' }}"></i>

                                </button>

                            </form>

                            <form action="{{ route('events.destroy', $event) }}" method="POST" class="d-inline delete-form">
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
<!-- Tambahkan JS DataTables jika belum ada di template utama -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<script>
$(document).ready(function() {
    // Inisialisasi DataTables
    $('#eventTable').DataTable({
        "responsive": true,
        "autoWidth": false,
        "pageLength": 10,
        "language": {
            "search": "Cari :",
            "lengthMenu": "Tampilkan _MENU_ data",
            "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            "zeroRecords": "Data tidak ditemukan",
            "infoEmpty": "Tidak ada data yang tersedia",
            "paginate": {
                "previous": "Sebelumnya",
                "next": "Selanjutnya"
            }
        }
    });

    // SweetAlert untuk konfirmasi hapus
    $('.delete-form').submit(function(e) {
        e.preventDefault();
        let form = this;
        Swal.fire({
            title: 'Hapus Event?',
            text: 'Data Event beserta Item di dalamnya akan ikut terhapus.',
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

@push('scripts')

<script>
$(document).on('click', '.btn-toggle-status', function () {

    const button = $(this);
    const form = button.closest('form');
    const eventName = button.data('event-name');
    const status = button.data('status');

    const isActive = status === 'active';

    Swal.fire({
        icon: isActive ? 'warning' : 'question',

        title: isActive
            ? 'Nonaktifkan Event?'
            : 'Aktifkan Event?',

        html: isActive
            ? `
                Event <strong>${eventName}</strong> akan
                <strong>dinonaktifkan</strong>.<br><br>
                Event ini tidak akan muncul pada menu
                <strong>Tanda Terima</strong>.
              `
            : `
                Event <strong>${eventName}</strong> akan
                <strong>diaktifkan</strong> kembali dan tersedia
                pada menu <strong>Tanda Terima</strong>.
              `,

        showCancelButton: true,

        confirmButtonText: isActive
            ? '<i class="fas fa-eye-slash"></i> Ya, Nonaktifkan'
            : '<i class="fas fa-eye"></i> Ya, Aktifkan',

        cancelButtonText: 'Batal',

        confirmButtonColor: isActive
            ? '#dc3545'
            : '#28a745',

        cancelButtonColor: '#6c757d',

        reverseButtons: true,

        focusCancel: true

    }).then((result) => {

        if (result.isConfirmed) {

            // Kirim form
            form.submit();

        }

    });

});
</script>

@endpush
