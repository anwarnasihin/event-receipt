@extends('layouts.app')

@section('content')

<div class="card">

    <div class="card-header">

        <h3>Edit Event</h3>

    </div>

    <form action="{{ route('events.update',$event) }}"
          method="POST">

        @csrf
        @method('PUT')

        <div class="card-body">

            <div class="mb-3">

                <label>Nama Event</label>

                <input
                    type="text"
                    class="form-control"
                    name="name"
                    value="{{ $event->name }}">

            </div>

            <div class="mb-3">

                <label>Tanggal</label>

                <input
                    type="date"
                    class="form-control"
                    name="event_date"
                    value="{{ $event->event_date }}">

            </div>

            <div class="mb-3">

                <label>Lokasi</label>

                <input
                    type="text"
                    class="form-control"
                    name="location"
                    value="{{ $event->location }}">

            </div>

            <div class="mb-3">

                <label>Deskripsi</label>

                <textarea
                    class="form-control"
                    name="description">{{ $event->description }}</textarea>

            </div>

        </div>

        <div class="card-footer">

            <button class="btn btn-primary">

                Update

            </button>

        </div>

    </form>

</div>

@endsection
