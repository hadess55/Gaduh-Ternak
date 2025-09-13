<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <style>
    body{ font-family: DejaVu Sans, sans-serif; font-size: 12px; }
    .title{ text-align:center; font-weight:bold; font-size:16px; margin-bottom:10px;}
    .meta td{ padding:2px 6px; vertical-align:top;}
    .box{ border:1px solid #333; padding:10px; margin-top:8px;}
  </style>
</head>
<body>
  <div class="title">UNDANGAN / BERITA ACARA MEDIASI</div>
  <table class="meta">
    <tr><td>No. Kasus</td><td>: {{ $dispute->id }}</td></tr>
    <tr><td>Pelapor</td><td>: {{ $dispute->reporter->name }}</td></tr>
    <tr><td>Terlapor</td><td>: {{ optional($dispute->defendant)->name ?? '-' }}</td></tr>
    <tr><td>Lokasi / Waktu</td><td>: {{ $dispute->location }} / {{ $dispute->occurred_at->format('d M Y H:i') }}</td></tr>
    <tr><td>Jalur</td><td>: {{ ucfirst($dispute->legal_route) }}</td></tr>
  </table>

  <div class="box">
    <strong>Deskripsi Kejadian:</strong><br>
    {!! nl2br(e($dispute->description)) !!}
  </div>

  @if($dispute->legalBasis)
  <div class="box">
    <strong>Rujukan Hukum:</strong><br>
    {{ $dispute->legalBasis->title }}<br>
    {!! nl2br(e($dispute->legalBasis->article_ref)) !!}
  </div>
  @endif

  <p style="margin-top:40px">Petugas,</p>
  <p style="margin-top:60px"><u>_________________________</u></p>
</body>
</html>
