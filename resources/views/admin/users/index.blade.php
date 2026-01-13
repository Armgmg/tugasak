@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <h1 class="text-2xl font-bold">Manajemen User 👥</h1>

    @if(session('success'))
        <div class="bg-emerald-500/20 text-emerald-400 p-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-500/20 text-red-400 p-3 rounded">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-slate-800 rounded-xl p-6">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-700 text-left">
                    <th class="py-2">Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr class="border-b border-slate-700">
                    <td class="py-2">{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <span class="px-2 py-1 text-xs rounded
                            {{ $user->role === 'admin'
                                ? 'bg-emerald-500/20 text-emerald-400'
                                : 'bg-blue-500/20 text-blue-400' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td class="text-right space-x-2">
                        <a href="{{ route('admin.users.edit', $user) }}"
                           class="text-yellow-400 hover:underline">
                            Edit
                        </a>

                        <form action="{{ route('admin.users.destroy', $user) }}"
                              method="POST"
                              class="inline">
                            @csrf
                            @method('DELETE')
                            <button
                                onclick="return confirm('Yakin hapus user ini?')"
                                class="text-red-400 hover:underline">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection
