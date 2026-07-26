@extends('layouts.app')

@section('content')

<div class="container">

    <div class="card">

        <div class="card-header">

            <h3>Tambah Item Souvenir</h3>

            <small>
                Event :
                <strong>{{ $event->name }}</strong>
            </small>

        </div>

        <form method="POST"
              action="{{ route('events.items.store',$event) }}">

            @csrf

            <div class="card-body">

                <div class="mb-3">

                    <label class="form-label">

                        Nama Item

                    </label>

                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        required>

                </div>

                <div class="mb-3">

                    <label class="form-label">

                        Qty

                    </label>

                    <input
                        type="number"
                        name="qty"
                        value="0"
                        class="form-control">

                </div>

                <div class="form-check">

                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="active"
                        value="1"
                        checked>

                    <label class="form-check-label">

                        Aktif

                    </label>

                </div>

            </div>

            <div class="card-footer">

                <button class="btn btn-primary">

                    Simpan

                </button>

                <a href="{{ route('events.items.index',$event) }}"
                   class="btn btn-secondary">

                    Kembali

                </a>

            </div>

        </form>

    </div>

</div>

@endsection
