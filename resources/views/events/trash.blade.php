@extends('layouts.app')

@section('title', 'Recycle Bin Event')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>
            <h3 class="mb-1">
                <i class="fas fa-trash-alt text-danger"></i>
                Recycle Bin Event
            </h3>

            <small class="text-muted">
                Event yang dihapus sementara dan masih dapat dipulihkan.
            </small>
        </div>

        <a href="{{ route('events.index') }}"
           class="btn btn-secondary">

            <i class="fas fa-arrow-left"></i>
            Kembali ke Master Event

        </a>

    </div>


    {{-- ALERT SUCCESS --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="fas fa-check-circle"></i>

            {{ session('success') }}

            <button type="button"
                    class="close"
                    data-dismiss="alert">

                <span>&times;</span>

            </button>

        </div>

    @endif


    {{-- ALERT ERROR --}}
    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="fas fa-exclamation-triangle"></i>

            {{ session('error') }}

            <button type="button"
                    class="close"
                    data-dismiss="alert">

                <span>&times;</span>

            </button>

        </div>

    @endif


    {{-- CARD --}}
    <div class="card card-outline card-danger">

        <div class="card-header">

            <h3 class="card-title">

                <i class="fas fa-trash-alt"></i>
                Event Terhapus

            </h3>

        </div>


        <div class="card-body">

            @if($events->count() > 0)

                <div class="table-responsive">

                    <table class="table table-bordered table-hover">

                        <thead>

                            <tr>

                                <th width="60">
                                    No
                                </th>

                                <th>
                                    Kode Event
                                </th>

                                <th>
                                    Nama Event
                                </th>

                                <th>
                                    Tanggal Event
                                </th>

                                <th>
                                    Lokasi
                                </th>

                                <th class="text-center">
                                    Peserta
                                </th>

                                <th>
                                    Dihapus Pada
                                </th>

                                <th width="220" class="text-center">
                                    Aksi
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($events as $event)

                                <tr>

                                    <td>
                                        {{ $loop->iteration }}
                                    </td>

                                    <td>
                                        <strong>
                                            {{ $event->code }}
                                        </strong>
                                    </td>

                                    <td>
                                        {{ $event->name }}
                                    </td>

                                    <td>

                                        @if($event->event_date)

                                            {{ $event->event_date->format('d-m-Y') }}

                                        @else

                                            -

                                        @endif

                                    </td>

                                    <td>
                                        {{ $event->location ?? '-' }}
                                    </td>

                                    <td class="text-center">

                                        <span class="badge badge-info">

                                            <i class="fas fa-users"></i>

                                            {{ $event->participants_count }}

                                        </span>

                                    </td>

                                    <td>

                                        {{ $event->deleted_at
                                            ? $event->deleted_at->format('d-m-Y H:i:s')
                                            : '-'
                                        }}

                                    </td>

                                    <td class="text-center">

                                        {{-- RESTORE --}}
                                        <form
                                            action="{{ route('events.restore', $event->id) }}"
                                            method="POST"
                                            class="d-inline restore-form">

                                            @csrf

                                            <button
                                                type="submit"
                                                class="btn btn-success btn-sm"
                                                title="Pulihkan Event">

                                                <i class="fas fa-undo"></i>

                                                Pulihkan

                                            </button>

                                        </form>


                                        {{-- HARD DELETE --}}
                                        <form
                                            action="{{ route('events.force-delete', $event->id) }}"
                                            method="POST"
                                            class="d-inline force-delete-form">

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-danger btn-sm"
                                                title="Hapus Permanen">

                                                <i class="fas fa-trash-alt"></i>

                                                Hapus Permanen

                                            </button>

                                        </form>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5">

                    <i class="fas fa-trash-alt fa-4x text-muted mb-3"></i>

                    <h5 class="text-muted">
                        Recycle Bin kosong
                    </h5>

                    <p class="text-muted mb-0">
                        Tidak ada event yang sedang berada di Recycle Bin.
                    </p>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection


@push('scripts')

<script>

$(document).ready(function () {


    /*
    |--------------------------------------------------------------------------
    | KONFIRMASI RESTORE
    |--------------------------------------------------------------------------
    */

    $('.restore-form').on('submit', function (e) {

        e.preventDefault();

        const form = this;

        Swal.fire({

            title: 'Pulihkan Event?',

            text: 'Event akan dikembalikan ke Master Event.',

            icon: 'question',

            showCancelButton: true,

            confirmButtonColor: '#28a745',

            cancelButtonColor: '#6c757d',

            confirmButtonText: 'Ya, Pulihkan',

            cancelButtonText: 'Batal'

        }).then(function (result) {

            if (result.isConfirmed) {

                form.submit();

            }

        });

    });


    /*
    |--------------------------------------------------------------------------
    | KONFIRMASI HARD DELETE
    |--------------------------------------------------------------------------
    */

    $('.force-delete-form').on('submit', function (e) {

        e.preventDefault();

        const form = this;

        Swal.fire({

            title: 'Hapus Permanen?',

            html:
                '<div class="text-danger">' +
                '<i class="fas fa-exclamation-triangle fa-2x mb-2"></i>' +
                '<br><br>' +
                '<strong>PERINGATAN!</strong>' +
                '<br><br>' +
                'Event, peserta, data check-in, tanda terima, ' +
                'detail souvenir, dan foto receipt akan dihapus permanen.' +
                '<br><br>' +
                '<strong>Data tidak dapat dipulihkan kembali.</strong>' +
                '</div>',

            icon: 'warning',

            showCancelButton: true,

            confirmButtonColor: '#dc3545',

            cancelButtonColor: '#6c757d',

            confirmButtonText: 'Ya, Hapus Permanen',

            cancelButtonText: 'Batal',

            focusCancel: true

        }).then(function (result) {

            if (result.isConfirmed) {

                form.submit();

            }

        });

    });

});

</script>

@endpush
