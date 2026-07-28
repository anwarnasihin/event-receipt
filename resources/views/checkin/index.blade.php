@extends('layouts.app')

@section('title', 'Absensi Peserta')

@section('content')

<form id="eventForm">

    <div class="mb-3">
        <label class="form-label">
            Pilih Event
        </label>

        <select
            class="form-control"
            id="event">

            <option value="">
                -- Pilih Event --
            </option>

            @foreach($events as $event)
                <option value="{{ $event->id }}">
                    {{ $event->name }}
                </option>
            @endforeach

        </select>

    </div>

    <button
        type="submit"
        class="btn btn-success">

        <i class="fas fa-user-check mr-1"></i>
        Masuk

    </button>

</form>

@endsection

@push('scripts')
<script>

document
    .getElementById('eventForm')
    .addEventListener('submit', function(e){

        e.preventDefault();

        let eventId = document.getElementById('event').value;

        if(eventId == ''){
            Swal.fire(
                'Perhatian',
                'Silakan pilih event.',
                'warning'
            );
            return;
        }

        window.location.href = '/checkin/event/' + eventId;

    });

</script>
@endpush
