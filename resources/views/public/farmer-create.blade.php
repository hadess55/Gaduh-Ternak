<!doctype html>
<html lang="id">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Pendaftaran Peternak</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
</head>
<body class="container">
  <h2>Pendaftaran Peternak</h2>
  @if(session('ok')) <article class="success">{{ session('ok') }}</article> @endif
  <form method="post" action="{{ route('farmer.public.store') }}">
    @csrf
    <label>Nama @error('nama')<small style="color:red">{{ $message }}</small>@enderror
      <input name="nama" value="{{ old('nama') }}" required>
    </label>
    <label>NIK @error('nik')<small style="color:red">{{ $message }}</small>@enderror
      <input name="nik" value="{{ old('nik') }}" required>
    </label>
    <label>No. Telepon
      <input name="telp" value="{{ old('telp') }}">
    </label>
    <label>Alamat
      <textarea name="alamat">{{ old('alamat') }}</textarea>
    </label>
    <label>Desa @error('desa')<small style="color:red">{{ $message }}</small>@enderror
      <input name="desa" value="{{ old('desa') }}" required>
    </label>
    <label>Kecamatan @error('kecamatan')<small style="color:red">{{ $message }}</small>@enderror
      <input name="kecamatan" value="{{ old('kecamatan') }}" required>
    </label>
    <label>Jenis Ternak @error('jenis_ternak')<small style="color:red">{{ $message }}</small>@enderror
      <input name="jenis_ternak" value="{{ old('jenis_ternak') }}" required>
    </label>
    <label>Jumlah Ternak @error('jumlah_ternak')<small style="color:red">{{ $message }}</small>@enderror
      <input type="number" name="jumlah_ternak" value="{{ old('jumlah_ternak',0) }}" min="0" required>
    </label>
    <button type="submit">Kirim</button>
  </form>
</body>
</html>
