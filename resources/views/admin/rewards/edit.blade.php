@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Reward</h1>
                <p class="text-gray-600 dark:text-gray-400">Perbarui informasi reward produk.</p>
            </div>
            <a href="{{ route('admin.rewards.index') }}"
                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>

        <!-- Form Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-8">
            <form action="{{ route('admin.rewards.update', $reward) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Nama Produk -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Produk</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $reward->name) }}" required
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-teal-500 focus:border-teal-500"
                        placeholder="Contoh: Voucher Belanja 50rb">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kategori -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Kategori</label>
                    <select name="category" id="category" required
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-teal-500 focus:border-teal-500">
                        <option value="Voucher" {{ old('category', $reward->category) == 'Voucher' ? 'selected' : '' }}>Voucher</option>
                        <option value="Barang" {{ old('category', $reward->category) == 'Barang' ? 'selected' : '' }}>Barang</option>
                        <option value="Sembako" {{ old('category', $reward->category) == 'Sembako' ? 'selected' : '' }}>Sembako</option>
                        <option value="Pulsa/Token" {{ old('category', $reward->category) == 'Pulsa/Token' ? 'selected' : '' }}>Pulsa/Token</option>
                        <option value="Lainnya" {{ old('category', $reward->category) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('category')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Deskripsi Produk</label>
                    <textarea name="description" id="description" rows="3" required
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-teal-500 focus:border-teal-500"
                        placeholder="Jelaskan detail produk secara singkat...">{{ old('description', $reward->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Poin -->
                <div>
                    <label for="poin_required" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Poin Dibutuhkan</label>
                    <div class="relative">
                        <input type="number" name="poin_required" id="poin_required" value="{{ old('poin_required', $reward->poin_required) }}" required min="1"
                            class="w-full pl-4 pr-12 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-teal-500 focus:border-teal-500">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 dark:text-gray-400">Pts</span>
                        </div>
                    </div>
                    @error('poin_required')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Gambar -->
                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Gambar Produk</label>
                    
                    @if($reward->image)
                        <div class="mb-4">
                            <p class="text-xs text-gray-500 mb-1">Gambar saat ini:</p>
                            <img src="{{ $reward->image }}" alt="Current Image" class="h-32 rounded-lg object-cover border border-gray-200 dark:border-gray-600">
                        </div>
                    @endif

                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-lg">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                <label for="image" class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-teal-600 hover:text-teal-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-teal-500">
                                    <span>Upload file baru</span>
                                    <input id="image" name="image" type="file" class="sr-only" accept="image/*">
                                </label>
                                <p class="pl-1">atau drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Biarkan kosong jika tidak ingin mengubah gambar</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, GIF max 2MB</p>
                        </div>
                    </div>
                    @error('image')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status Checkbox -->
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="status" name="status" type="checkbox" value="1" {{ old('status', $reward->status) ? 'checked' : '' }}
                            class="focus:ring-teal-500 h-4 w-4 text-teal-600 border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="status" class="font-medium text-gray-700 dark:text-gray-300">Produk Aktif</label>
                        <p class="text-gray-500 dark:text-gray-400">Jika tidak dicentang, produk tidak akan muncul di marketplace user.</p>
                    </div>
                </div>

                <!-- Submit -->
                <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="submit"
                        class="w-full px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white font-bold rounded-lg shadow-lg hover:shadow-xl transition flex justify-center items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Perbarui Produk
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
