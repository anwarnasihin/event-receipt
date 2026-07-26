@extends('layouts.app')

@section('title', 'Settings')

@section('content')

<div class="container-fluid">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}

            <button type="button"
                    class="close"
                    data-dismiss="alert">
                <span>&times;</span>
            </button>

        </div>
    @endif

    <form action="{{ route('settings.update') }}" method="POST">

        @csrf
        @method('PUT')

        <div class="card">

            <div class="card-header">

                <h3 class="card-title">

                    <i class="fas fa-cogs"></i>

                    System Settings

                </h3>

            </div>

            <div class="card-body">

                <div class="row">

                    <!-- GENERAL -->
                    <div class="col-md-6">

                        <div class="card card-outline card-primary">

                            <div class="card-header">

                                <h3 class="card-title">
                                    General
                                </h3>

                            </div>

                            <div class="card-body">

                                <div class="form-group">

                                    <label>Nama Aplikasi</label>

                                    <input
                                        type="text"
                                        name="app_name"
                                        class="form-control"
                                        value="{{ old('app_name', $setting->app_name) }}">

                                </div>

                                <div class="form-group">

                                    <label>Nama Instansi</label>

                                    <input
                                        type="text"
                                        name="company_name"
                                        class="form-control"
                                        value="{{ old('company_name', $setting->company_name) }}">

                                </div>

                                <div class="form-group">

                                    <label>Timezone</label>

                                    <input
                                        type="text"
                                        name="timezone"
                                        class="form-control"
                                        value="{{ old('timezone', $setting->timezone) }}">

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- NETWORK -->
                    <div class="col-md-6">

                        <div class="card card-outline card-info">

                            <div class="card-header">

                                <h3 class="card-title">
                                    Network
                                </h3>

                            </div>

                            <div class="card-body">

                                <div class="form-group">

                                    <label>Server URL</label>

                                    <div class="input-group">

                                        <input
                                            id="app_url"
                                            type="text"
                                            name="app_url"
                                            class="form-control"
                                            value="{{ old('app_url', $setting->app_url) }}">

                                        <div class="input-group-append">

                                            <button
                                                type="button"
                                                class="btn btn-primary"
                                                id="detect-ip">

                                                Detect IP

                                            </button>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="row">

                    <!-- QR -->
                    <div class="col-md-6">

                        <div class="card card-outline card-success">

                            <div class="card-header">

                                <h3 class="card-title">

                                    QR Code

                                </h3>

                            </div>

                            <div class="card-body">

                                <div class="form-group">

                                    <label>QR Size</label>

                                    <input
                                        type="number"
                                        name="qr_size"
                                        class="form-control"
                                        value="{{ old('qr_size', $setting->qr_size) }}">

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- Webcam -->
                    <div class="col-md-6">

                        <div class="card card-outline card-warning">

                            <div class="card-header">

                                <h3 class="card-title">

                                    Webcam

                                </h3>

                            </div>

                            <div class="card-body">

                                <div class="form-check mb-2">

                                    <input
                                        type="checkbox"
                                        class="form-check-input"
                                        name="enable_webcam"
                                        id="enable_webcam"
                                        {{ $setting->enable_webcam ? 'checked' : '' }}>

                                    <label
                                        class="form-check-label"
                                        for="enable_webcam">

                                        Enable Webcam

                                    </label>

                                </div>

                                <div class="form-check mb-3">

                                    <input
                                        type="checkbox"
                                        class="form-check-input"
                                        name="auto_capture"
                                        id="auto_capture"
                                        {{ $setting->auto_capture ? 'checked' : '' }}>

                                    <label
                                        class="form-check-label"
                                        for="auto_capture">

                                        Auto Capture

                                    </label>

                                </div>

                                <div class="form-group">

                                    <label>Capture Delay (detik)</label>

                                    <input
                                        type="number"
                                        name="capture_delay"
                                        class="form-control"
                                        value="{{ old('capture_delay', $setting->capture_delay) }}">

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <div class="card-footer text-right">

                <button
                    type="submit"
                    class="btn btn-success">

                    <i class="fas fa-save"></i>

                    Simpan Settings

                </button>

            </div>

        </div>

    </form>

</div>

@endsection

@push('scripts')

<script>

document.getElementById('detect-ip').addEventListener('click', function () {

    let protocol = window.location.protocol;

    let host = window.location.host;

    document.getElementById('app_url').value = protocol + '//' + host;

});

</script>

@endpush
