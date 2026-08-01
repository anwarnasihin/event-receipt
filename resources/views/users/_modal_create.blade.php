<div
    class="modal fade"
    id="modalCreateUser"
    tabindex="-1"
    role="dialog"
    aria-labelledby="modalCreateUserLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form
                action="{{ route('users.store') }}"
                method="POST">
                @csrf
                <div class="modal-header bg-primary">
                    <h5 class="modal-title">
                        <i class="fas fa-user-plus"></i>
                        Tambah User
                    </h5>
                    <button
                        type="button"
                        class="close text-white"
                        data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            placeholder="Masukkan nama lengkap"
                            required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            placeholder="email@binus.ac.id"
                            required>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input
                            type="password"
                            name="password"
                            class="form-control"
                            placeholder="********"
                            required>
                    </div>
                    <div class="form-group">
                        <label>Konfirmasi Password</label>
                        <input
                            type="password"
                            name="password_confirmation"
                            class="form-control"
                            placeholder="********"
                            required>
                    </div>
                    <div class="form-group">
                        <label>Role</label>
                        <select
                            name="role"
                            class="form-control"
                            required>
                            <option value="">
                                -- Pilih Role --
                            </option>
                            @foreach($roles as $role)
                                <option
                                    value="{{ $role->name }}">
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal">
                        Batal
                    </button>
                    <button
                        type="submit"
                        class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
