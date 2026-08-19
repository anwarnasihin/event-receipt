<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Http\RedirectResponse;

class UserManagementController extends Controller
{
    /**
     * Daftar User
     */
    public function index()
    {
        $users = User::with('roles')
        ->orderBy('name')
        ->get();

        $roles = Role::orderBy('name')->get();

        return view('users.index', compact(
            'users',
            'roles'
        ));
    }

    /**
 * Simpan User Baru
 */
public function store(StoreUserRequest $request)
{
    $user = User::create([
    'name' => $request->name,
    'username' => $request->username,
    'email' => $request->email,
    'location' => $request->location,
    'password' => Hash::make($request->password),
    ]);

    $user->assignRole(
    $request->input('role')
    );

    return redirect()
        ->route('users.index')
        ->with(
            'success',
            'User berhasil ditambahkan.'
        );
}

    /**
 * Update User
 */
public function update(
    UpdateUserRequest $request,
    User $user
)
{
    $data = [
    'name' => $request->name,
    'username' => $request->username,
    'email' => $request->email,
    'location' => $request->location,
    ];

    if ($request->filled('password')) {

        $data['password'] = Hash::make(
            $request->password
        );

    }

    $user->update($data);

    $user->syncRoles([
        $request->role
    ]);

    return redirect()
        ->route('users.index')
        ->with(
            'success',
            'User berhasil diperbarui.'
        );
}

    /**
 * Hapus User
 */
public function destroy(User $user)
{
    if (auth()->id() === $user->id) {

        return back()->with(
            'error',
            'Anda tidak dapat menghapus akun yang sedang digunakan.'
        );

    }

    $user->syncRoles([]);

    $user->delete();

    return back()->with(
        'success',
        'User berhasil dihapus.'
    );
}
}
