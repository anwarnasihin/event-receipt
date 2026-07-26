@extends('layouts.app')

@section('title', 'Import Peserta')

@section('content')

<div class="card">

    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-file-excel text-success"></i>
            Import Data Peserta
        </h3>
    </div>

    <form action="{{ route('events.participants.import.store', $event) }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf

        <div class="card-body">

            <div class="form-group">
                <label>Jenis Peserta</label>

                <select name="participant_type" class="form-control" required>
                    <option value="">-- Pilih Jenis Peserta --</option>
                    <option value="Dosen">Dosen</option>
                    <option value="Staff">Staff</option>
                    <option value="Mahasiswa">Mahasiswa</option>
                    <option value="Guest">Guest</option>
                </select>
            </div>

            <div class="form-group">
                <label>File Excel</label>

                <input type="file"
                       name="file"
                       class="form-control"
                       accept=".xlsx,.xls"
                       required>

                <small class="text-muted">
                    Format yang didukung: .xlsx atau .xls
                </small>
            </div>

            <div class="alert alert-info">

                <strong>Format Excel:</strong>

                <ul class="mb-0">
                    <li>Participant Code</li>
                    <li>Nama</li>
                    <li>Email</li>
                    <li>No HP</li>
                    <li>Base Campus</li>
                </ul>

            </div>

        </div>

        <div class="card-footer">

            <a href="{{ route('events.participants.index', $event) }}"
               class="btn btn-secondary">

                <i class="fas fa-arrow-left"></i>

                Kembali

            </a>

            <button type="submit"
                    class="btn btn-success">

                <i class="fas fa-file-import"></i>

                Import

            </button>

        </div>

    </form>

</div>

@endsection
