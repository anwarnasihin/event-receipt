@extends('layouts.app')

@section('title', 'Edit Peserta')

@section('content')

<div class="card">

    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-user-edit"></i>
            Edit Peserta
        </h3>

        <br>

        <small>
            Event :
            <strong>{{ $event->name }}</strong>
        </small>
    </div>

    <form method="POST"
      action="{{ route('events.participants.update', [$event, $participant]) }}"
      enctype="multipart/form-data">

        @csrf
        @method('PUT')

        <div class="card-body">

            <div class="row">

                <div class="col-md-6">

                    <div class="form-group">
                        <label>Participant ID</label>

                        <input
                            type="text"
                            name="participant_code"
                            value="{{ old('participant_code', $participant->participant_code) }}"
                            class="form-control @error('participant_code') is-invalid @enderror"
                            placeholder="NIM / NIDN / Kode Staff">

                        @error('participant_code')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

                <div class="col-md-6">

                    <div class="form-group">

                        <label>Jenis Peserta <span class="text-danger">*</span></label>

                        <select
                            name="participant_type"
                            class="form-control @error('participant_type') is-invalid @enderror">

                            <option value="">-- Pilih Jenis Peserta --</option>

                            <option value="Dosen"
                                {{ old('participant_type', $participant->participant_type) == 'Dosen' ? 'selected' : '' }}>
                                Dosen
                            </option>

                            <option value="Staff"
                                {{ old('participant_type', $participant->participant_type) == 'Staff' ? 'selected' : '' }}>
                                Staff
                            </option>

                            <option value="Mahasiswa"
                                {{ old('participant_type', $participant->participant_type) == 'Mahasiswa' ? 'selected' : '' }}>
                                Mahasiswa
                            </option>

                            <option value="Guest"
                                {{ old('participant_type', $participant->participant_type) == 'Guest' ? 'selected' : '' }}>
                                Guest
                            </option>

                        </select>

                        @error('participant_type')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

            </div>

            <div class="form-group">

                <label>Nama Lengkap <span class="text-danger">*</span></label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name', $participant->name) }}"
                    class="form-control @error('name') is-invalid @enderror"
                    placeholder="Masukkan nama peserta">

                @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            <div class="row">

                <div class="col-md-6">

                    <div class="form-group">

                        <label>Email</label>

                        <input
                            type="email"
                            name="email"
                            value="{{ old('email', $participant->email) }}"
                            class="form-control @error('email') is-invalid @enderror">

                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

                <div class="col-md-6">

                    <div class="form-group">

                        <label>No. HP</label>

                        <input
                            type="text"
                            name="phone"
                            value="{{ old('phone', $participant->phone) }}"
                            class="form-control @error('phone') is-invalid @enderror">

                        @error('phone')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

            </div>

            @php
                $campuses = [
                    'Kemanggisan',
                    'Senayan',
                    'Kijang',
                    'Alam Sutera',
                    'Bekasi',
                    'Bandung',
                    'Malang',
                    'Semarang',
                    'Medan'
                ];
            @endphp

            <div class="form-group">

                <label>Campus</label>

                <select
                    name="campus"
                    class="form-control @error('campus') is-invalid @enderror">

                    <option value="">-- Pilih Campus --</option>

                    @foreach($campuses as $campus)
                        <option value="{{ $campus }}"
                            {{ strtolower(trim(old('campus', $participant->campus))) === strtolower(trim($campus)) ? 'selected' : '' }}>
                            {{ $campus }}
                        </option>
                    @endforeach

                </select>

                @error('campus')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            <div class="row">

                <div class="col-md-6">

                    <div class="form-group">

                        <label>Fakultas</label>

                        <input
                            type="text"
                            name="faculty"
                            value="{{ old('faculty', $participant->faculty) }}"
                            class="form-control @error('faculty') is-invalid @enderror">

                    </div>

                </div>

                <div class="col-md-6">

                    <div class="form-group">

                        <label>Department / Unit</label>

                        <input
                            type="text"
                            name="department"
                            value="{{ old('department', $participant->department) }}"
                            class="form-control @error('department') is-invalid @enderror">

                    </div>

                </div>

            </div>

            <div class="form-group">

                <label>Jabatan</label>

                <input
                    type="text"
                    name="position"
                    value="{{ old('position', $participant->position) }}"
                    class="form-control @error('position') is-invalid @enderror">

            </div>

            <div class="form-group">

                <label>Catatan</label>

                <textarea
                    name="notes"
                    rows="3"
                    class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $participant->notes) }}</textarea>

            </div>

            {{-- FOTO BUKTI PENYERAHAN --}}
            <div class="form-group">

                <label>
                    Foto Bukti Penyerahan
                </label>

                @php
                    $latestReceipt = $participant->receipts()->latest('id')->first();
                @endphp

                @if($latestReceipt && $latestReceipt->photo)

                    <div class="mb-3">

                        <p class="mb-2">
                            <small class="text-muted">
                                Foto bukti penyerahan saat ini:
                            </small>
                        </p>

                        <img
                            src="{{ asset('storage/' . $latestReceipt->photo) }}"
                            alt="Foto Bukti Penyerahan"
                            style="
                                max-width:300px;
                                max-height:220px;
                                object-fit:contain;
                                border:1px solid #ddd;
                                border-radius:6px;
                                padding:5px;
                            "
                        >

                    </div>

                @else

                    <div class="alert alert-warning">
                        <i class="fas fa-info-circle"></i>
                        Foto bukti penyerahan belum tersedia.
                    </div>

                @endif

                <input
                    type="file"
                    name="photo"
                    accept="image/jpeg,image/png,image/jpg"
                    class="form-control @error('photo') is-invalid @enderror">

                @error('photo')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

                <small class="form-text text-muted">
                    Upload foto baru jika foto bukti penyerahan sebelumnya salah.
                    Format JPG, JPEG, atau PNG. Maksimal 5 MB.
                </small>

            </div>

        </div>

        <div class="card-footer">

            <button
                type="submit"
                class="btn btn-primary">

                <i class="fas fa-save"></i>
                Update

            </button>

            <a href="{{ route('events.participants.index', $event) }}"
               class="btn btn-secondary">

                <i class="fas fa-arrow-left"></i>
                Kembali

            </a>

        </div>

    </form>

</div>

@endsection
