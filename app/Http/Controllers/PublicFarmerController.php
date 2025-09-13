<?php

namespace App\Http\Controllers;

use App\Models\Farmer;
use Illuminate\Http\Request;

class PublicFarmerController extends Controller
{
    public function create() { return view('public.farmer-create'); }

    public function store(Request $r)
    {
        $data = $r->validate([
            'nama'=>'required|string|max:120',
            'nik'=>'required|string|max:20|unique:farmers,nik',
            'telp'=>'nullable|string|max:30',
            'alamat'=>'nullable|string',
            'desa'=>'required|string|max:100',
            'kecamatan'=>'required|string|max:100',
            'jenis_ternak'=>'required|string|max:100',
            'jumlah_ternak'=>'required|integer|min:0',
        ]);

        $data['status'] = 'pending';
        Farmer::create($data);

        return back()->with('ok', 'Data terkirim. Menunggu validasi perangkat desa.');
    }
}

