@extends('layouts.app')

@section('title','Check In')

@section('content')

<div class="row justify-content-center">

<div class="col-md-6">

<div class="card">

<div class="card-header bg-primary text-white text-center">

<h3>

BINUS UNIVERSITY

</h3>

<h5>

EVENT RECEIPT

</h5>

</div>

<div class="card-body text-center">

@if(session('success'))

<div class="alert alert-success">

<h4>

✅ {{ session('success') }}

</h4>

</div>

@endif

<h2>

{{ $participant->name }}

</h2>

<p>

{{ $participant->participant_type }}

</p>

<hr>

@if(!$participant->attendance_status)

<form method="POST"

action="{{ route('participant.checkin.store',$participant->code) }}">

@csrf

<button

class="btn btn-success btn-lg">

Saya Hadir

</button>

</form>

@else

<div class="alert alert-success">

<h3>

ANDA SUDAH CHECK IN

</h3>

<p>

{{ $participant->checkin_at }}

</p>

</div>

@endif

</div>

</div>

</div>

</div>

@endsection
