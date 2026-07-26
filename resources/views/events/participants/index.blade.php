@extends('layouts.app')

@section('title', 'Master Peserta')

@section('content')

<div class="card">

    <div>

    <a href="{{ route('events.participants.create',$event) }}"
       class="btn btn-primary">

        <i class="fas fa-user-plus"></i>

        Tambah Manual

    </a>

    <a href="{{ route('events.participants.import',$event) }}"
       class="btn btn-success">

        <i class="fas fa-file-excel"></i>

        Import Excel

    </a>

    <a href="{{ route('events.participants.template',$event) }}"
       class="btn btn-info">

        <i class="fas fa-download"></i>

        Download Template

    </a>

</div>

    <div class="card-body">

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table id="participantTable"
               class="table table-bordered table-striped">

            <thead>

                <tr>
                    <th width="120">Kode</th>
                    <th width="150">Participant ID</th>
                    <th>Nama</th>
                    <th>Campus</th>
                    <th width="120">Jenis</th>
                    <th width="120">Sumber</th>
                    <th width="150">Status Souvenir</th>
                    <th width="180">Aksi</th>
                </tr>

            </thead>

            <tbody>

            @forelse($participants as $participant)

                <tr>

                    <td>{{ $participant->code }}</td>

                    <td>{{ $participant->participant_code }}</td>

                    <td>{{ $participant->name }}</td>

                    <td>{{ $participant->campus ?? '-' }}</td>

                    <td>

                        @switch($participant->participant_type)

                            @case('Dosen')
                                <span class="badge badge-primary">Dosen</span>
                                @break

                            @case('Staff')
                                <span class="badge badge-success">Staff</span>
                                @break

                            @case('Mahasiswa')
                                <span class="badge badge-warning">Mahasiswa</span>
                                @break

                            @default
                                <span class="badge badge-secondary">Guest</span>

                        @endswitch

                    </td>

                    <td>

                        @if($participant->is_manual)

                            <span class="badge badge-info">
                                Manual
                            </span>

                        @else

                            <span class="badge badge-secondary">
                                Import
                            </span>

                        @endif

                    </td>

                    <td>

                        @if($participant->souvenir_status)

                            <span class="badge badge-success">
                                <i class="fas fa-check-circle"></i>
                                Sudah Ambil
                            </span>

                        @else

                            <span class="badge badge-warning">
                                <i class="fas fa-clock"></i>
                                Belum Ambil
                            </span>

                        @endif

                    </td>

                    <td>

                        {{-- Detail --}}
                        <a href="{{ route('events.participants.show', [$event, $participant]) }}"
                           class="btn btn-info btn-sm"
                           title="Detail">

                            <i class="fas fa-eye"></i>

                        </a>

                        {{-- Edit --}}
                        <a href="{{ route('events.participants.edit', [$event, $participant]) }}"
                           class="btn btn-warning btn-sm"
                           title="Edit">

                            <i class="fas fa-edit"></i>

                        </a>

                        {{-- Delete --}}
                        <form action="{{ route('events.participants.destroy', [$event, $participant]) }}"
                              method="POST"
                              style="display:inline-block;">

                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin ingin menghapus peserta ini?')">

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

@endsection

@push('scripts')

<script>

$(function () {

    $('#participantTable').DataTable({

        responsive: true,
        autoWidth: false,
        pageLength: 10,
        language: {
            search: "Cari :",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
            zeroRecords: "Data tidak ditemukan",
            paginate: {
                previous: "Sebelumnya",
                next: "Berikutnya"

            }

        }

    });

});

</script>

@endpush
