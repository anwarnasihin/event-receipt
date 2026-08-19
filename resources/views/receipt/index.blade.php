@extends('layouts.app')
@section('title', 'Tanda Terima')
@section('content')
@if($events->count() > 0)

    <form id="eventForm">

        <label>Pilih Event</label>

        <select
            name="event"
            id="event"
            class="form-control"
            required>

            <option value="" selected disabled>
                -- Pilih Event --
            </option>

            @foreach($events as $event)
                <option value="{{ $event->id }}">
                    {{ $event->name }}
                </option>
            @endforeach

        </select>

        <button type="submit" class="btn btn-primary mt-3">
            Masuk
        </button>

    </form>

    @else

        <div class="alert alert-warning mt-3">

            <h5>
                <i class="fas fa-exclamation-triangle mr-2"></i>
                Tidak Ada Event Aktif
            </h5>

            <p class="mb-0">
                Saat ini belum ada event yang aktif.
                Silakan hubungi Administrator untuk mengaktifkan event terlebih dahulu.
            </p>

        </div>

    @endif
@endsection
@push('scripts')
<script>
document
    .getElementById('eventForm')
    .addEventListener('submit', function(e){
        e.preventDefault();

        let eventId = document.getElementById('event').value;

        if(eventId == ''){
            alert('Silakan pilih event.');
            return;
        }

        window.location.href = "{{ url('receipt') }}/" + eventId;
    });
</script>
@endpush


