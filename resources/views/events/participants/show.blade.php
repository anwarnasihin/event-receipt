@extends('layouts.app')

@section('title', 'Detail Peserta')

@section('content')

<div class="row">

    <div class="col-md-4">

        <div class="card card-primary card-outline">

            <div class="card-body box-profile">

                <div class="text-center">

                    <img class="profile-user-img img-fluid img-circle"
                         src="https://ui-avatars.com/api/?name={{ urlencode($participant->name) }}&background=0D8ABC&color=fff&size=256">

                </div>

                <h3 class="profile-username text-center">

                    {{ $participant->name }}

                </h3>

                <p class="text-muted text-center">

                    {{ $participant->participant_type }}

                </p>

                <ul class="list-group list-group-unbordered">

                    <li class="list-group-item">

                        <b>Kode</b>

                        <span class="float-right">

                            {{ $participant->code }}

                        </span>

                    </li>

                    <li class="list-group-item">

                        <b>Participant ID</b>

                        <span class="float-right">

                            {{ $participant->participant_code ?: '-' }}

                        </span>

                    </li>

                    <li class="list-group-item">

                        <b>Sumber Data</b>

                        <span class="float-right">

                            @if($participant->is_manual)

                                <span class="badge badge-info">

                                    Manual

                                </span>

                            @else

                                <span class="badge badge-secondary">

                                    Import Excel

                                </span>

                            @endif

                        </span>

                    </li>

                    <li class="list-group-item">

                        <b>Status Souvenir</b>

                        <span class="float-right">

                            @if($participant->souvenir_status)

                                <span class="badge badge-success">

                                    Sudah Ambil

                                </span>

                            @else

                                <span class="badge badge-warning">

                                    Belum Ambil

                                </span>

                            @endif

                        </span>

                    </li>

                </ul>

            </div>

        </div>

    </div>

    <div class="col-md-8">

        <div class="card">

            <div class="card-header">

                <h3 class="card-title">

                    <i class="fas fa-id-card"></i>

                    Informasi Peserta

                </h3>

            </div>

            <div class="card-body">

                <table class="table table-bordered">

                    <tr>
                        <th width="220">Nama</th>
                        <td>{{ $participant->name }}</td>
                    </tr>

                    <tr>
                        <th>Email</th>
                        <td>{{ $participant->email ?: '-' }}</td>
                    </tr>

                    <tr>
                        <th>No. HP</th>
                        <td>{{ $participant->phone ?: '-' }}</td>
                    </tr>

                    <tr>
                        <th>Campus</th>
                        <td>{{ $participant->campus ?: '-' }}</td>
                    </tr>

                    <tr>
                        <th>Fakultas</th>
                        <td>{{ $participant->faculty ?: '-' }}</td>
                    </tr>

                    <tr>
                        <th>Department</th>
                        <td>{{ $participant->department ?: '-' }}</td>
                    </tr>

                    <tr>
                        <th>Jabatan</th>
                        <td>{{ $participant->position ?: '-' }}</td>
                    </tr>

                    <tr>
                        <th>Catatan</th>
                        <td>{{ $participant->notes ?: '-' }}</td>
                    </tr>

                </table>

            </div>

        </div>

        <div class="card">

            <div class="card-header">

                <h3 class="card-title">

                    <i class="fas fa-gift"></i>

                    Riwayat Penyerahan Souvenir

                </h3>

            </div>

            <div class="card-body text-center text-muted">

                Belum ada riwayat penyerahan souvenir.

            </div>

        </div>

    </div>

</div>

<div class="mt-3">

    <a href="{{ route('events.participants.index', $event) }}"
       class="btn btn-secondary">

        <i class="fas fa-arrow-left"></i>

        Kembali

    </a>

    <a href="{{ route('events.participants.edit', [$event, $participant]) }}"
       class="btn btn-warning">

        <i class="fas fa-edit"></i>

        Edit

    </a>

</div>

@endsection
