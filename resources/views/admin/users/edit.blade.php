@extends('layouts.app')

@section('content')
<div class="max-w-xl space-y-6">

    <h1 class="text-xl font-bold">Edit Role User</h1>

    <form method="POST" action="{{ route('admin.users.update', $user) }}"
          class="bg-slate-800 p-6 rounded-xl space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm mb-1">Nama</label>
            <input type="text" disabled
                   value="{{ $user->name }}"
                   class="w-full bg-slate-700 rounded p-2">
        </div>

        <div>
            <label class="block text-sm mb-1">Email</label>
            <input type="text" disabled
                   value="{{ $user->email }}"
                   class="w-full bg-slate-700 rounded p-2">
        </div>

        <div>
            <label class="block text-sm mb-1">Role</label>
            <select name="role"
                    class="w-full bg-slate-700 rounded p-2">
                <option value="user" @selected($user->role === 'user')>User</option>
                <option value="admin" @selected($user->role === 'admin')>Admin</option>
            </select>
        </div>

        <div class="flex justify-between">
            <a href="{{ route('admin.users.index') }}"
               class="text-gray-400 hover:underline">
                ← Kembali
            </a>

            <button class="bg-blue-600 px-4 py-2 rounded">
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection
