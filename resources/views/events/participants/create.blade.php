@extends('layouts.app')

@section('title', 'Tambah Peserta')

@section('content')

<div class="card">

    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-user-plus"></i>
            Tambah Peserta
        </h3>

        <br>

        <small>
            Event :
            <strong>{{ $event->name }}</strong>
        </small>
    </div>

    <form method="POST"
          action="{{ route('events.participants.store', $event) }}">

        @csrf

        <div class="card-body">

            <div class="row">

                <div class="col-md-6">

                    <div class="form-group">
                        <label>Participant ID <span class="text-danger">*</span></label>

                        <input
                            type="text"
                            name="participant_code" required
                            value="{{ old('participant_code') }}"
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
                            name="participant_type" required
                            class="form-control @error('participant_type') is-invalid @enderror">

                            <option value="">-- Pilih Jenis Peserta --</option>

                            <option value="Dosen" {{ old('participant_type') == 'Dosen' ? 'selected' : '' }}>
                                Dosen
                            </option>

                            <option value="Staff" {{ old('participant_type') == 'Staff' ? 'selected' : '' }}>
                                Staff
                            </option>

                            <option value="Mahasiswa" {{ old('participant_type') == 'Mahasiswa' ? 'selected' : '' }}>
                                Mahasiswa
                            </option>

                            <option value="Guest" {{ old('participant_type') == 'Guest' ? 'selected' : '' }}>
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
                    name="name" required
                    value="{{ old('name') }}"
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
                            name="email" required
                            value="{{ old('email') }}"
                            class="form-control @error('email') is-invalid @enderror"
                            placeholder="contoh@email.com">

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
                            name="phone" required
                            value="{{ old('phone') }}"
                            class="form-control @error('phone') is-invalid @enderror"
                            placeholder="08xxxxxxxxxx">

                        @error('phone')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

            </div>

            <div class="form-group">

                <label>Campus</label>

                <select
                    name="campus" required
                    class="form-control @error('campus') is-invalid @enderror">

                    <option value="">-- Pilih Campus --</option>

                    @php
                        $campuses = [
                            'Kemanggisan',
                            'Senayan',
                            'Alam Sutera',
                            'Bekasi',
                            'Bandung',
                            'Malang',
                            'Semarang'
                        ];
                    @endphp

                    @foreach($campuses as $campus)
                        <option value="{{ $campus }}"
                            {{ old('campus') == $campus ? 'selected' : '' }}>
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
                            value="{{ old('faculty') }}"
                            class="form-control @error('faculty') is-invalid @enderror">

                        @error('faculty')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

                <div class="col-md-6">

                    <div class="form-group">

                        <label>Department / Unit</label>

                        <input
                            type="text"
                            name="department"
                            value="{{ old('department') }}"
                            class="form-control @error('department') is-invalid @enderror">

                        @error('department')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

            </div>

            <div class="form-group">

                <label>Jabatan</label>

                <input
                    type="text"
                    name="position"
                    value="{{ old('position') }}"
                    class="form-control @error('position') is-invalid @enderror">

                @error('position')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            <div class="form-group">

                <label>Catatan</label>

                <textarea
                    name="notes"
                    rows="3"
                    class="form-control @error('notes') is-invalid @enderror"
                    placeholder="Catatan tambahan (opsional)">{{ old('notes') }}</textarea>

                @error('notes')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

        </div>

        <div class="card-footer">

            <button
                type="submit"
                class="btn btn-primary">

                <i class="fas fa-save"></i>
                Simpan

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
