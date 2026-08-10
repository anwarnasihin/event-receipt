<table
    id="users-table"
    class="table table-bordered table-hover">
    <thead class="thead-light">
        <tr>
            <th width="5%">No</th>
            <th>Nama</th>
            <th>Email</th>
            <th width="20%">Role</th>
            <th width="15%" class="text-center">
                Aksi
            </th>
        </tr>
    </thead>
    <tbody>
        @forelse($users as $user)
            <tr>
                <td>
                    {{ $loop->iteration }}
                </td>
                <td>
                    {{ $user->name }}
                </td>
                <td>
                    {{ $user->email }}
                </td>
                <td>
                    @foreach($user->roles as $role)
                        @if($role->name == 'Administrator')
                            <span class="badge badge-danger">
                                Administrator
                            </span>
                        @elseif($role->name == 'Petugas')
                            <span class="badge badge-primary">
                                Petugas
                            </span>
                        @else
                            <span class="badge badge-secondary">

                                Viewer

                            </span>
                        @endif
                    @endforeach
                </td>
                <td class="text-center">
                    <button
                        type="button"
                        class="btn btn-warning btn-sm btn-edit"
                        data-id="{{ $user->id }}"
                        data-name="{{ $user->name }}"
                        data-username="{{ $user->username }}"
                        data-email="{{ $user->email }}"
                        data-role="{{ optional($user->roles->first())->name }}"
                        data-toggle="modal"
                        data-target="#modalEditUser">
                        <i class="fas fa-edit"></i>
                    </button>
                    <form
                        action="{{ route('users.destroy', $user) }}"
                        method="POST"
                        class="d-inline form-delete">

                        @csrf
                        @method('DELETE')

                        <button
                            type="submit"
                            class="btn btn-danger btn-sm">

                            <i class="fas fa-trash"></i>

                        </button>

                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center">
                    Belum ada data user.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
{{-- <div class="mt-3">
    {{ $users->links() }}
</div> --}}

@push('scripts')
<script>
$(document).ready(function(){

    // ==========================
    // Edit User
    // ==========================
    $('.btn-edit').click(function(){

        let id    = $(this).data('id');
        let name  = $(this).data('name');
        let username = $(this).data('username');
        let email = $(this).data('email');
        let role  = $(this).data('role');

        $('#edit_name').val(name);
        $('#edit_username').val(username);
        $('#edit_email').val(email);
        $('#edit_role').val(role);

        $('#formEditUser').attr(
            'action',
            '{{ route('users.update', ':id') }}'.replace(':id', id)
        );

    });

    // ==========================
    // Delete User
    // ==========================
    $('.form-delete').submit(function(e){

        e.preventDefault();

        let form = this;

        Swal.fire({

            title: 'Hapus User?',
            text: 'Data user yang dihapus tidak dapat dikembalikan.',
            icon: 'warning',

            showCancelButton: true,

            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',

            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'

        }).then((result)=>{

            if(result.isConfirmed){

                form.submit();

            }

        });

    });

});
</script>
@endpush
