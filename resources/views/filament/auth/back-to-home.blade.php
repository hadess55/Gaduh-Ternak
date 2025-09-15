<div class="mt-4">
    {{-- versi tombol penuh ala Filament --}}
    <x-filament::button
        tag="a"
        href="{{ route('home') }}"
        icon="heroicon-o-arrow-left"
        color="gray"
        class="w-full"
    >
        Beranda
    </x-filament::button>

    {{-- atau kalau belum ada route('home'), gunakan url('/') --}}
    {{-- <x-filament::button tag="a" href="{{ url('/') }}" icon="heroicon-o-arrow-left" color="gray" class="w-full">
        Kembali ke Beranda
    </x-filament::button> --}}
</div>
