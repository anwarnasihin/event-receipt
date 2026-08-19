<div
    class="modal fade"
    id="modalEditUser"
    tabindex="-1"
    role="dialog"
    aria-labelledby="modalEditUserLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form
                id="formEditUser"
                method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">
                        <i class="fas fa-user-edit"></i>
                        Edit User
                    </h5>
                    <button
                        type="button"
                        class="close"
                        data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input
                            type="text"
                            id="edit_name"
                            name="name"
                            class="form-control"
                            required>
                    </div>
                    <div class="form-group">
                        <label>Username</label>
                        <input
                            type="text"
                            id="edit_username"
                            name="username"
                            class="form-control"
                            required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input
                            type="email"
                            id="edit_email"
                            name="email"
                            class="form-control"
                            required>
                    </div>
                    <div class="form-group">
                        <label>Lokasi</label>
                        <input
                            type="text"
                            id="edit_location"
                            name="location"
                            class="form-control"
                            placeholder="Contoh: Bekasi"
                            required>
                    </div>
                    <div class="form-group">
                        <label>Password Baru</label>
                        <input
                            type="password"
                            name="password"
                            class="form-control"
                            placeholder="Kosongkan jika tidak ingin mengubah password">
                        <small class="text-muted">
                            Biarkan kosong jika password tidak diubah.
                        </small>
                    </div>
                    <div class="form-group">
                        <label>Konfirmasi Password</label>
                        <input
                            type="password"
                            name="password_confirmation"
                            class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Role</label>
                        <select
                            id="edit_role"
                            name="role"
                            class="form-control"
                            required>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}">
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
                        class="btn btn-warning">
                        <i class="fas fa-save"></i>
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
