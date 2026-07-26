@extends('layouts.app')

@section('title', 'Master Item Souvenir')

@section('content')

<div class="card">

    <div class="card-header d-flex justify-content-between align-items-center">

        <div>

            <h3 class="card-title mb-1">
                <i class="fas fa-gift"></i>
                Master Item Souvenir
            </h3>

            <small>
                Event :
                <strong>{{ $event->name }}</strong>
            </small>

        </div>

        <a href="{{ route('events.items.create',$event) }}"
           class="btn btn-primary">

            <i class="fas fa-plus"></i>

            Tambah Item

        </a>

    </div>

    <div class="card-body">

        @if(session('success'))

            <div class="alert alert-success alert-dismissible">

                <button type="button"
                        class="close"
                        data-dismiss="alert">

                    &times;

                </button>

                <i class="fas fa-check-circle"></i>

                {{ session('success') }}

            </div>

        @endif

        <table id="itemTable"
               class="table table-bordered table-striped">

            <thead>

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

                            <span class="badge badge-success">

                                Aktif

                            </span>

                        @else

                            <span class="badge badge-danger">

                                Non Aktif

                            </span>

                        @endif

                    </td>

                    <td>

                        <a href="{{ route('events.items.edit',[$event,$item]) }}"
                           class="btn btn-warning btn-sm">

                            <i class="fas fa-edit"></i>

                        </a>

                        <form
                            action="{{ route('events.items.destroy',[$event,$item]) }}"
                            method="POST"
                            class="d-inline delete-form">

                            @csrf
                            @method('DELETE')

                            <button
                                class="btn btn-danger btn-sm">

                                <i class="fas fa-trash"></i>

                            </button>

                        </form>

                    </td>

                </tr>

            @empty

            <tr>
                <td>-</td>
                <td colspan="4" class="text-center">
                    Belum ada Item Souvenir.
                </td>
            </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection

@push('scripts')

<script>

$(function () {

    console.log($('#itemTable').html());

});


$('.delete-form').submit(function(e){

    e.preventDefault();

    let form=this;

    Swal.fire({

        title:'Hapus Item?',

        text:'Data yang dihapus tidak dapat dikembalikan.',

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
