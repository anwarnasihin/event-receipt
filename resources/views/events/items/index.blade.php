@extends('layouts.app')
@section('title', 'Master Item Souvenir')

<!-- Tambahkan CSS DataTables jika template Anda belum memilikinya -->
@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
@endpush

@section('content')
<div class="card">
    <!-- Header Dirapihkan -->
    <div class="card-header d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <a href="{{ route('events.index') }}" class="btn btn-secondary btn-sm mr-3">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <div>
                <h3 class="card-title mb-0 font-weight-bold">
                    <i class="fas fa-gift mr-2"></i> Master Item Souvenir
                </h3>
                <small class="text-muted">
                    Event: <strong>{{ $event->name }}</strong>
                </small>
            </div>
        </div>

        <a href="{{ route('events.items.create', $event) }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Item
        </a>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            </div>
        @endif

        <div class="table-responsive">
            <table id="itemTable" class="table table-bordered table-striped table-hover">
                <thead class="thead-light">
                    <tr>
                        <th width="120">Kode</th>
                        <th>Nama Item</th>
                        <th width="100">Qty</th>
                        <th width="120">Status</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($items as $item)
                    <tr>
                        <td>{{ $item->code }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->qty }}</td>
                        <td>
                            @if($item->active)
                                <span class="badge badge-success px-2 py-1">Aktif</span>
                            @else
                                <span class="badge badge-danger px-2 py-1">Non Aktif</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('events.items.edit', [$event, $item]) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('events.items.destroy', [$event, $item]) }}" method="POST" class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <!-- Baris ini akan ditimpa oleh tulisan "No data available in table" dari DataTables secara otomatis -->
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Tambahkan JS DataTables -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<script>
$(document).ready(function () {
    // Inisialisasi DataTables untuk Pencarian dan Sorting
    $('#itemTable').DataTable({
        "pageLength": 10, // Menampilkan 10 data secara default
        "language": {
            "search": "Cari:",
            "lengthMenu": "Tampilkan _MENU_ data",
            "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
            "infoEmpty": "Tidak ada data yang tersedia",
            "zeroRecords": "Data tidak ditemukan",
            "paginate": {
                "first": "Awal",
                "last": "Akhir",
                "next": "Selanjutnya",
                "previous": "Sebelumnya"
            }
        }
    });

    // SweetAlert untuk konfirmasi hapus
    $('.delete-form').submit(function(e){
        e.preventDefault();
        let form = this;
        Swal.fire({
            title: 'Hapus Item?',
            text: 'Data yang dihapus tidak dapat dikembalikan.',
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
