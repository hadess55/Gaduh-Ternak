@props([
  // bisa override dari pemanggil jika mau
  'brand' => 'Gaduh Ternak',
  'links' => [
      ['label' => 'Beranda',   'href' => url('/')],
      ['label' => 'Pendaftaran Peternak', 'href' => url('/pendaftaran-peternak')],
  ],
  // link admin (kanan)
  'adminUrl' => url('/admin'),
])

<nav class="sticky top-0 z-40 border-b bg-white/90 backdrop-blur supports-[backdrop-filter]:bg-white/60">
  <div class="container mx-auto px-4">
    <div class="flex h-16 items-center justify-between">
      {{-- Brand --}}
      <a href="{{ url('/') }}" class="flex items-center gap-2 font-semibold text-gray-900">
        <span class="text-xl">{{ $brand }}</span>
      </a>

      {{-- Desktop menu --}}
      <div class="hidden items-center gap-6 md:flex">
        @foreach(($links ?? []) as $link)
          @php $active = url()->current() === $link['href']; @endphp
          <a href="{{ $link['href'] }}"
             @class([
              'text-sm transition-colors',
              'text-gray-900 font-medium' => $active,
              'text-gray-600 hover:text-gray-900' => ! $active,
             ])>
            {{ $link['label'] }}
          </a>
        @endforeach

        <a href="{{ $adminUrl }}"
           class="inline-flex items-center rounded-lg border border-gray-900 px-3 py-1.5 text-sm font-semibold text-gray-900 hover:bg-gray-900 hover:text-white">
          Admin
        </a>
      </div>

      {{-- Mobile button --}}
      <button id="mobile-menu-button"
              class="inline-flex items-center justify-center rounded-md p-2 text-gray-700 hover:bg-gray-100 md:hidden"
              aria-label="Toggle menu">
        <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>
    </div>

    {{-- Mobile menu --}}
    <div id="mobile-menu" class="hidden border-t pb-4 md:hidden">
      <div class="flex flex-col gap-2 pt-3">
        @foreach(($links ?? []) as $link)
          @php $active = url()->current() === $link['href']; @endphp
          <a href="{{ $link['href'] }}"
             @class([
               'rounded-md px-3 py-2 text-sm',
               'bg-gray-900 text-white' => $active,
               'text-gray-700 hover:bg-gray-100' => ! $active,
             ])>
            {{ $link['label'] }}
          </a>
        @endforeach

        <a href="{{ $adminUrl }}"
           class="rounded-md border border-gray-900 px-3 py-2 text-sm font-semibold text-gray-900 hover:bg-gray-900 hover:text-white">
          Admin
        </a>
      </div>
    </div>
  </div>
</nav>
