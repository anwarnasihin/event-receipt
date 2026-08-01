@extends('layouts.app')
@section('title', 'User Management')
@section('content')

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">
            <i class="fas fa-users-cog text-primary"></i>
            User Management
        </h2>
        <button
            class="btn btn-primary"
            data-toggle="modal"
            data-target="#modalCreateUser">

            <i class="fas fa-user-plus"></i>

            Tambah User

        </button>
    </div>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <div class="card">
        <div class="card-body">
            @include('users._table')
        </div>
    </div>
</div>
@include('users._modal_create')
@include('users._modal_edit')

@push('scripts')
<script>
$(function () {

    $('#users-table').DataTable({

        responsive: true,

        autoWidth: false,

        pageLength: 10,

        language: {
            search: "Cari :",
            lengthMenu: "Tampilkan _MENU_ data",
            zeroRecords: "Data tidak ditemukan",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data",
            paginate: {
                previous: "Sebelumnya",
                next: "Selanjutnya"
            }
        }

    });

});
</script>
@endpush
@endsection
