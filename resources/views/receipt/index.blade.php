@extends('layouts.app')
@section('title', 'Tanda Terima')
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
        class="btn btn-primary">
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
            alert('Silakan pilih event.');
            return;
        }

        window.location.href = "{{ url('receipt') }}/" + eventId;
    });
</script>
@endpush


