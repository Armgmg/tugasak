@extends('layouts.app')

@section('content')
    <div class="flex-1 overflow-hidden focus:outline-none">
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 dark:bg-gray-900">
            <div class="container mx-auto px-6 py-8">
                <h3 class="text-3xl font-medium text-gray-700 dark:text-gray-200 mb-6">SPK Rekomendasi Reward (Metode SAW)
                </h3>

                <form action="{{ route('admin.saw.index') }}" method="POST">
                    @csrf
                    
                    <div class="flex justify-between items-center mb-6">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            <i class="fas fa-calculator mr-2"></i> Hitung / Simulasikan
                        </button>
                        <a href="{{ route('admin.saw.index') }}" class="text-blue-600 hover:text-blue-800">
                            Reset ke Default
                        </a>
                    </div>

                    <!-- 1. Kriteria & Bobot -->
                    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden mb-8">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-teal-600">
                            <h4 class="text-lg font-semibold text-white">1. Kriteria & Bobot (Input Simulasi)</h4>
                        </div>
                        <div class="p-6">
                            <table class="min-w-full">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 text-left text-xs leading-4 font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kode</th>
                                        <th class="px-6 py-3 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 text-left text-xs leading-4 font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama Kriteria</th>
                                        <th class="px-6 py-3 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 text-left text-xs leading-4 font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Atribut</th>
                                        <th class="px-6 py-3 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 text-left text-xs leading-4 font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Bobot (Edit)</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800">
                                    @foreach($criteria as $code => $info)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 dark:border-gray-700">{{ $code }}</td>
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 dark:border-gray-700">{{ $info['name'] }}</td>
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 dark:border-gray-700">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $info['type'] == 'benefit' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ ucfirst($info['type']) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 dark:border-gray-700">
                                                <input type="number" step="0.01" name="weights[{{ $code }}]" value="{{ $info['weight'] }}" class="w-24 px-2 py-1 text-sm border rounded dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- 2. Matriks Awal (X) -->
                    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden mb-8">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-teal-600">
                            <h4 class="text-lg font-semibold text-white">2. Matriks Keputusan Awal (X) - Input Simulasi</h4>
                        </div>
                        <div class="p-6 overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 text-left text-xs leading-4 font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Alternatif</th>
                                        @foreach($criteria as $code => $info)
                                            <th class="px-6 py-3 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 text-left text-xs leading-4 font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ $info['name'] }} ({{ $code }})</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800">
                                    @foreach($data as $altCode => $scores)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 dark:border-gray-700 font-medium">
                                                {{ $alternatives[$altCode] }} ({{ $altCode }})
                                            </td>
                                            @foreach($scores as $critCode => $score)
                                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 dark:border-gray-700">
                                                    <input type="number" step="0.01" name="matrix[{{ $altCode }}][{{ $critCode }}]" value="{{ $score }}" class="w-24 px-2 py-1 text-sm border rounded dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>

                <!-- 3. Normalisasi Matriks (R) -->
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden mb-8">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-blue-600">
                        <h4 class="text-lg font-semibold text-white">3. Matriks Ternormalisasi (R)</h4>
                    </div>
                    <div class="p-6 overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr>
                                    <th
                                        class="px-6 py-3 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 text-left text-xs leading-4 font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Alternatif</th>
                                    @foreach($criteria as $code => $info)
                                        <th
                                            class="px-6 py-3 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 text-left text-xs leading-4 font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            {{ $code }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800">
                                @foreach($normalized as $altCode => $scores)
                                    <tr>
                                        <td
                                            class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 dark:border-gray-700 font-medium">
                                            {{ $alternatives[$altCode] }} ({{ $altCode }})
                                        </td>
                                        @foreach($scores as $score)
                                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 dark:border-gray-700">
                                                {{ number_format($score, 2) }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- 4. Hasil Perankingan (V) -->
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden mb-8">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-indigo-600">
                        <h4 class="text-lg font-semibold text-white">4. Hasil Akhir & Ranking (V)</h4>
                    </div>
                    <div class="p-6 overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr>
                                    <th
                                        class="px-6 py-3 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 text-left text-xs leading-4 font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Ranking</th>
                                    <th
                                        class="px-6 py-3 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 text-left text-xs leading-4 font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Kode</th>
                                    <th
                                        class="px-6 py-3 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 text-left text-xs leading-4 font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Alternatif Reward</th>
                                    <th
                                        class="px-6 py-3 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 text-left text-xs leading-4 font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Nilai Preferensi (V)</th>
                                    <th
                                        class="px-6 py-3 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 text-left text-xs leading-4 font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Kesimpulan</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800">
                                @foreach($ranks as $index => $rank)
                                    <tr class="{{ $index == 0 ? 'bg-green-50 dark:bg-green-900/20' : '' }}">
                                        <td
                                            class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 dark:border-gray-700 font-bold text-center">
                                            #{{ $index + 1 }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 dark:border-gray-700 font-bold">
                                            {{ $rank['code'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 dark:border-gray-700">
                                            {{ $rank['name'] }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 dark:border-gray-700 font-bold text-teal-600 dark:text-teal-400">
                                            {{ $rank['score'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 dark:border-gray-700">
                                            @if($index == 0)
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Rekomendasi Utama
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Conclusion Alert -->
                <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 rounded-r shadow-sm" role="alert">
                    <p class="font-bold">Kesimpulan SAW</p>
                    <p>Berdasarkan perhitungan metode Simple Additive Weighting (SAW), alternatif terbaik adalah
                        <strong>{{ $ranks[0]['name'] }} ({{ $ranks[0]['code'] }})</strong> dengan nilai preferensi tertinggi
                        <strong>{{ $ranks[0]['score'] }}</strong>.
                    </p>
                </div>

            </div>
        </main>
    </div>
@endsection