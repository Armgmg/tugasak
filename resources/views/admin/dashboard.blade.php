@extends('layouts.app')

@section('content')
    <div class="p-6 space-y-6">

        <h1 class="text-2xl font-bold text-white">
            Dashboard Admin 👑
        </h1>

        <!-- Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-slate-800 rounded-xl p-6">
                <p class="text-gray-400 text-sm">Total User</p>
                <h2 class="text-3xl font-bold text-white">{{ $totalUsers }}</h2>
            </div>

            <div class="bg-slate-800 rounded-xl p-6">
                <p class="text-gray-400 text-sm">Admin</p>
                <h2 class="text-3xl font-bold text-emerald-400">{{ $totalAdmin }}</h2>
            </div>

            <div class="bg-slate-800 rounded-xl p-6">
                <p class="text-gray-400 text-sm">Member</p>
                <h2 class="text-3xl font-bold text-blue-400">{{ $totalMember }}</h2>
            </div>
        </div>

        <!-- User Terbaru -->
        <div class="bg-slate-800 rounded-xl p-6">
            <h2 class="text-lg font-semibold text-white mb-4">
                User Terbaru
            </h2>

            <table class="w-full text-sm text-gray-300">
                <thead>
                    <tr class="border-b border-slate-700">
                        <th class="text-left py-2">Nama</th>
                        <th class="text-left py-2">Email</th>
                        <th class="text-left py-2">Role</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($latestUsers as $user)
                        <tr class="border-b border-slate-700">
                            <td class="py-2">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="px-2 py-1 rounded text-xs
                                    @if($user->role === 'admin')
                                        bg-emerald-500/20 text-emerald-400
                                    @else
                                        bg-blue-500/20 text-blue-400
                                    @endif
                                    ">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
@endsection