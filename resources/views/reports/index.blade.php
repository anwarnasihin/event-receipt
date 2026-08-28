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
                            <th>Nama Event</th>
                            <th>Nama</th>
                            <th>Campus</th>
                            <th>Souvenir</th>
                            <th width="160">Tanggal</th>
                            <th width="150">Petugas</th>
                            @if(auth()->user()->hasRole('Administrator'))
                            <th width="70">Device</th>
                            @endif
                            <th width="80">Foto</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($receipts as $receipt)
                        <tr>
                            <td class="text-center">{{ $loop->iteration + (($receipts->currentPage()-1) * $receipts->perPage()) }}</td>
                            <td>{{ optional($receipt->participant)->participant_code }}</td>
                            <td>{{ optional(optional($receipt->participant)->event)->name ?? '-' }}</td>
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
                            @if(auth()->user()->hasRole('Administrator'))
                            <td class="text-center">
                                <button
                                    type="button"
                                    class="btn btn-info btn-sm btn-device"
                                    data-user="{{ optional($receipt->user)->name }}"
                                    data-ip="{{ $receipt->ip_address }}"
                                    data-browser="{{ $receipt->browser }}"
                                    data-os="{{ $receipt->operating_system }}"
                                    data-agent="{{ $receipt->user_agent }}"
                                    data-date="{{ \Carbon\Carbon::parse($receipt->received_at)->format('d F Y H:i') }}">
                                    <i class="fas fa-laptop"></i>
                                </button>
                            </td>
                            @endif
                            <td class="text-center">

                                @if($receipt->photo)

                                    {{-- Lihat Foto --}}
                                    <button
                                        type="button"
                                        class="btn btn-info btn-sm btn-photo"
                                        data-photo="{{ asset('storage/'.$receipt->photo) }}">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    {{-- Download Foto --}}
                                    <a
                                        href="{{ asset('storage/'.$receipt->photo) }}"
                                        download
                                        class="btn btn-success btn-sm"
                                        title="Download Foto">
                                        <i class="fas fa-download"></i>
                                    </a>

                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->hasRole('Administrator') ? 10 : 9 }}"class="text-center text-muted py-4">
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
<div class="modal fade" id="deviceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fas fa-laptop"></i>
                    Informasi Perangkat
                </h5>
                <button
                    type="button"
                    class="close text-white"
                    data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered mb-0">
                    <tr>
                        <th width="160">Petugas</th>
                        <td id="device-user"></td>
                    </tr>
                    <tr>
                        <th>IP Address</th>
                        <td id="device-ip"></td>
                    </tr>
                    <tr>
                        <th>Browser</th>
                        <td id="device-browser"></td>
                    </tr>
                    <tr>
                        <th>Operating System</th>
                        <td id="device-os"></td>
                    </tr>
                    <tr>
                        <th>Waktu</th>
                        <td id="device-date"></td>
                    </tr>
                    <tr>
                        <th>User Agent</th>
                        <td id="device-agent" style="word-break:break-all;font-size:12px;"></td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button
                    class="btn btn-secondary"
                    data-dismiss="modal">
                    Tutup
                </button>
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
        $('.btn-device').click(function(){
        $('#device-user').text($(this).data('user'));
        $('#device-ip').text($(this).data('ip'));
        $('#device-browser').text($(this).data('browser'));
        $('#device-os').text($(this).data('os'));
        $('#device-agent').text($(this).data('agent'));
        $('#device-date').text($(this).data('date'));
        $('#deviceModal').modal('show');
    });
});
</script>
@endpush
