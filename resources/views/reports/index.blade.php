@extends('layouts.app')
@section('title', 'Laporan Penyerahan Souvenir')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h3 class="card-title mb-0">
                <i class="fas fa-chart-bar text-primary"></i>
                Laporan Penyerahan Souvenir
            </h3>
        </div>
        <div class="card-body">
            <!-- Form Filter -->
            <form method="GET" action="{{ route('reports.index') }}">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label font-weight-bold>Event</label>
                            <select name="event_id" class="form-control">
                                <option value="">Semua Event</option>
                                @foreach($events as $event)
                                    <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                                        {{ $event->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label font-weight-bold>Cari Peserta</label>
                            <input type="text" name="keyword" class="form-control" placeholder="Participant ID / Nama" value="{{ request('keyword') }}">
                        </div>
                    </div>
                    <!-- Menggunakan label kosong agar tombol sejajar dengan input -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="d-none d-md-block">&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-primary mr-2">
                                    <i class="fas fa-search"></i> Filter
                                </button>
                                <a href="{{ route('reports.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-sync-alt"></i> Reset
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <hr>

            <!-- Tombol Export -->
            <div class="mb-3">
                <a href="{{ route('reports.export.excel', request()->query()) }}"
                    class="btn btn-success">
                    <i class="fas fa-file-excel"></i>
                    Export Excel
                </a>

                <a href="{{ route('reports.export.pdf', request()->query()) }}"
                    class="btn btn-danger">
                    <i class="fas fa-file-pdf"></i>
                    Export PDF
                </a>
            </div>
            <!-- Tabel Data -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped mb-0">
                    <thead class="thead-light text-center">
                        <tr>
                            <th width="50">No</th>
                            <th>Participant ID</th>
                            <th>Nama</th>
                            <th>Campus</th>
                            <th>Souvenir</th>
                            <th width="160">Tanggal</th>
                            <th width="150">Petugas</th>
                            <th width="80">Foto</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($receipts as $receipt)
                        <tr>
                            <td class="text-center">
                                {{ $loop->iteration + (($receipts->currentPage()-1) * $receipts->perPage()) }}
                            </td>
                            <td>{{ optional($receipt->participant)->participant_code }}</td>
                            <td>{{ optional($receipt->participant)->name }}</td>
                            <td>{{ optional($receipt->participant)->campus }}</td>
                            <td>
                                @foreach($receipt->receiptItems as $item)
                                    <span class="badge badge-success">
                                        {{ $item->item->name }}
                                    </span>
                                @endforeach
                            </td>
                            <td class="text-center">
                                {{ \Carbon\Carbon::parse($receipt->received_at)->format('d M Y H:i') }}
                            </td>
                            <td>{{ optional($receipt->user)->name }}</td>
                            <td class="text-center">
                                @if($receipt->photo)
                                    <button type="button" class="btn btn-info btn-sm btn-photo" data-photo="{{ asset('storage/'.$receipt->photo) }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="fas fa-folder-open fa-2x mb-2"></i><br>
                                Tidak ada data yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination dengan Bootstrap Style -->
            <div class="mt-4 d-flex justify-content-center">
                {{-- Menambahkan view bootstrap dan appends query agar filter tidak hilang saat pindah page --}}
                {{ $receipts->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>

{{-- =========================
     MODAL PREVIEW FOTO
========================= --}}
<div class="modal fade" id="photoModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-image"></i> Bukti Penyerahan Souvenir
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body text-center bg-light">
                <img id="preview-photo" src="" class="img-fluid rounded shadow-sm" style="max-height: 600px; object-fit: contain;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .table td, .table th {
        vertical-align: middle !important;
    }
    .badge {
        font-size: 12px;
        padding: 6px 10px;
        margin: 2px;
        font-weight: 500;
    }
    #preview-photo {
        border-radius: 8px;
    }
    .modal-content {
        border-radius: 10px;
        overflow: hidden;
    }
    /* Memperbaiki jarak pagination */
    .pagination {
        margin-bottom: 0;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function(){
    $('.btn-photo').click(function(){
        let photo = $(this).data('photo');
        $('#preview-photo').attr('src', photo);
        $('#photoModal').modal('show');
    });
});
</script>
@endpush
