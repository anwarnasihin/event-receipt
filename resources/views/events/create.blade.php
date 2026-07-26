@extends('layouts.app')

@section('title','Tambah Event')

@section('content')

<div class="card">

    <div class="card-header">

        <h3 class="card-title">

            Tambah Event

        </h3>

    </div>

    <form action="{{ route('events.store') }}" method="POST">

        @csrf

        <div class="card-body">

            <div class="form-group">

                <label>Nama Event</label>

                <input type="text"
                       name="name"
                       class="form-control"
                       required>

            </div>

            <div class="form-group">

                <label>Tanggal Event</label>

                <input type="date"
                       name="event_date"
                       class="form-control">

            </div>

            <div class="form-group">

                <label>Lokasi</label>

                <input type="text"
                       name="location"
                       class="form-control">

            </div>

            <div class="form-group">

                <label>Deskripsi</label>

                <textarea
                    name="description"
                    class="form-control"
                    rows="4"></textarea>

            </div>

        </div>

        <div class="card-footer">

            <button class="btn btn-primary">

                Simpan

            </button>

            <a href="{{ route('events.index') }}"
               class="btn btn-secondary">

                Kembali

            </a>

        </div>

    </form>

</div>

@endsection
