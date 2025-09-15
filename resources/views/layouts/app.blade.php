<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Gaduh Ternak')</title>

  <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
  <link rel="manifest" href="favicon/site.webmanifest">

  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="min-h-dvh flex flex-col bg-gray-50 text-gray-900 antialiased">
  <x-navbar />

  {{-- ini yang mendorong footer ke paling bawah --}}
  <main class="container mx-auto px-4 py-10 flex-1">
    @yield('content')
  </main>

  <footer class="mt-auto border-t bg-white">
    <div class="container mx-auto px-4 py-8 text-center text-sm text-gray-500">
      © {{ date('Y') }} Gaduh Ternak
    </div>
  </footer>
</body>
</html>
