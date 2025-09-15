@extends('layouts.app')

@section('title', 'Pendaftaran Peternak')

@section('content')
<div class="mx-auto w-full max-w-3xl">

    @if (session('ok'))
        <div class="mb-6 rounded-lg border border-green-600 bg-green-50 p-4 text-green-800">
            {{ session('ok') }}
        </div>
    @endif

    {{-- ring kartu dibuat terlihat --}}
    <div class="overflow-hidden rounded-2xl bg-white ring-1 ring-gray-200 shadow-sm">
        <div class="border-b p-6">
            <h1 class="text-xl font-semibold">Formulir Pendaftaran Peternak</h1>
            <p class="mt-1 text-sm text-gray-600">
                Tanda <span class="text-red-500">*</span> wajib diisi.
            </p>
        </div>

        <form method="POST" action="{{ route('farmer.public.store') }}" class="p-6">
            @csrf

            @php
                $input = 'mt-1 w-full rounded-lg border border-gray-300
                          focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-gray-900';
                $textarea = $input;
            @endphp

            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium">Nama <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama') }}" required class="{{ $input }}">
                    @error('nama') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium">NIK <span class="text-red-500">*</span></label>
                    <input type="text" name="nik" value="{{ old('nik') }}" required class="{{ $input }}">
                    @error('nik') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium">No. Telepon</label>
                    <input type="text" name="telp" value="{{ old('telp') }}" class="{{ $input }}">
                </div>

                <div>
                    <label class="block text-sm font-medium">Desa <span class="text-red-500">*</span></label>
                    <input type="text" name="desa" value="{{ old('desa') }}" required class="{{ $input }}">
                    @error('desa') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium">Kecamatan <span class="text-red-500">*</span></label>
                    <input type="text" name="kecamatan" value="{{ old('kecamatan') }}" required class="{{ $input }}">
                    @error('kecamatan') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium">Jenis Ternak <span class="text-red-500">*</span></label>
                    <input type="text" name="jenis_ternak" value="{{ old('jenis_ternak') }}" required class="{{ $input }}">
                    @error('jenis_ternak') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium">Jumlah Ternak <span class="text-red-500">*</span></label>
                    <input type="number" name="jumlah_ternak" min="0" step="1"
                           value="{{ old('jumlah_ternak', 0) }}" required class="{{ $input }}">
                    @error('jumlah_ternak') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium">Alamat</label>
                    <textarea name="alamat" rows="4" class="{{ $textarea }}">{{ old('alamat') }}</textarea>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-between gap-3">
                <p class="text-xs text-gray-500">Data Anda hanya digunakan untuk proses verifikasi oleh petugas.</p>
                <button type="submit"
                        class="inline-flex items-center rounded-lg bg-gray-900 px-5 py-2.5 text-white hover:opacity-90">
                    Kirim Pendaftaran
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
