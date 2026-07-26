@extends('layouts.app')

@section('title', 'Master Event')

@section('content')

<div class="card">

    <div class="card-header d-flex justify-content-between align-items-center">

        <h3 class="card-title">
            <i class="fas fa-calendar-alt"></i>
            Master Event
        </h3>

        <a href="{{ route('events.create') }}"
           class="btn btn-primary">

            <i class="fas fa-plus"></i>

            Tambah Event

        </a>

    </div>

    <div class="card-body">

        @if(session('success'))

            <div class="alert alert-success alert-dismissible fade show">

                <button
                    type="button"
                    class="close"
                    data-dismiss="alert">

                    &times;

                </button>

                <i class="fas fa-check-circle"></i>

                {{ session('success') }}

            </div>

        @endif

        <table id="eventTable"
               class="table table-bordered table-striped">

            <thead>

                <tr>

                    <th width="120">Kode</th>

                    <th>Nama Event</th>

                    <th width="150">Tanggal</th>

                    <th>Lokasi</th>

                    <th width="220">Aksi</th>

                </tr>

            </thead>

            <tbody>

            @forelse($events as $event)

                <tr>

                    <td>{{ $event->code }}</td>

                    <td>{{ $event->name }}</td>

                    <td>{{ $event->event_date }}</td>

                    <td>{{ $event->location }}</td>

                    <td>

                        <a href="{{ route('events.items.index',$event) }}"
                           class="btn btn-info btn-sm"
                           title="Master Item">
                            <i class="fas fa-gift"></i>
                        </a>

                        <a href="{{ route('events.participants.index', $event) }}"
                        class="btn btn-success btn-sm"
                        title="Peserta">
                            <i class="fas fa-users"></i>

                        </a>

                        <a href="{{ route('events.edit',$event) }}"
                           class="btn btn-warning btn-sm"
                           title="Edit">
                            <i class="fas fa-edit"></i>

                        </a>

                        <form
                            action="{{ route('events.destroy',$event) }}"
                            method="POST"
                            class="d-inline delete-form">

                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="btn btn-danger btn-sm"
                                title="Hapus">

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

$(function(){

    $('#eventTable').DataTable({

        responsive:true,

        autoWidth:false,

        pageLength:10,

        language:{

            search:"Cari :",

            lengthMenu:"Tampilkan _MENU_ data",

            info:"Menampilkan _START_ sampai _END_ dari _TOTAL_ data",

            zeroRecords:"Data tidak ditemukan",

            paginate:{

                previous:"Prev",

                next:"Next"

            }

        }

    });

});


$('.delete-form').submit(function(e){

    e.preventDefault();

    let form = this;

    Swal.fire({

        title:'Hapus Event?',

        text:'Data Event beserta Item akan dihapus.',

        icon:'warning',

        showCancelButton:true,

        confirmButtonColor:'#dc3545',

        cancelButtonColor:'#6c757d',

        confirmButtonText:'Ya, Hapus',

        cancelButtonText:'Batal'

    }).then((result)=>{

        if(result.isConfirmed){

            form.submit();

        }

    });

});

</script>

@endpush
