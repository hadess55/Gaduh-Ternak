<x-filament::section>
    <x-slot name="heading">Aksi Cepat</x-slot>

    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
        @if(auth()->user()?->hasRole('admin'))
            <x-filament::button
                tag="a"
                href="{{ \App\Filament\Resources\FarmerResource::getUrl('create') }}"
                icon="heroicon-o-plus"
                color="primary"
                class="w-full">
                Tambah Peternak
            </x-filament::button>

            <x-filament::button
                tag="a"
                href="{{ url('/admin/users') }}"
                icon="heroicon-o-users"
                color="gray"
                class="w-full">
                Kelola Pengguna
            </x-filament::button>
        @endif

        <x-filament::button
            tag="a"
            href="{{ route('home') }}"
            icon="heroicon-o-home"
            color="gray"
            class="w-full">
            Beranda
        </x-filament::button>

        <x-filament::button
            tag="a"
            href="https://filamentphp.com/docs/3.x" target="_blank"
            icon="heroicon-o-book-open"
            color="gray"
            class="w-full">
            Dokumentasi
        </x-filament::button>
    </div>
</x-filament::section>
