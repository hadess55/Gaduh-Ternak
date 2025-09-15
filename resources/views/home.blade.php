@extends('layouts.app')

@section('title','Beranda')

@section('content')
  <section class="grid gap-10 md:grid-cols-2">
    <div class="flex flex-col justify-center gap-4">
      <h1 class="text-3xl font-bold md:text-4xl">Sistem Digital <span class="text-gray-700">Gaduh Ternak</span></h1>
      <p class="text-gray-600 leading-relaxed">
        Platform pelaporan & penyelesaian sengketa ternak berbasis hukum perdata dan pidana,
        mendukung hilirisasi produk peternakan dan peningkatan mutu peternak.
      </p>
      <div class="flex gap-3">
        <a href="{{ url('/pendaftaran-peternak') }}"
           class="rounded-lg bg-gray-900 px-4 py-2 text-white hover:opacity-90">Daftar Peternak</a>
        <a href="{{ url('/admin') }}"
           class="rounded-lg border border-gray-900 px-4 py-2 text-gray-900 hover:bg-gray-900 hover:text-white">Masuk Admin</a>
      </div>
    </div>

    <div class="rounded-2xl border bg-white p-6 shadow-sm">
      <div class="aspect-[4/3] w-full rounded-xl bg-gradient-to-br from-gray-100 to-gray-200"></div>
      <p class="mt-3 text-center text-sm text-gray-500">Ilustrasi sistem (ganti dengan gambar kamu)</p>
    </div>
  </section>

  <section id="kontak" class="mt-16 grid gap-6 rounded-2xl border bg-white p-6 shadow-sm md:grid-cols-3">
    <div>
      <h3 class="font-semibold">Kontak</h3>
      <p class="text-gray-600 text-sm">Dinas Peternakan Kabupaten</p>
    </div>
    <div class="text-sm text-gray-600">
      Email: dinas@contoh.go.id <br> Telp: (021) 123-4567
    </div>
    <div class="text-sm text-gray-600">
      Alamat: Jl. Desa Sejahtera No. 1, Sukamakmur
    </div>
  </section>
@endsection
