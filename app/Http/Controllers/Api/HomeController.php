<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\PengaduanResource;
use App\Models\Pengaduan;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        return new PengaduanResource(Pengaduan::with(['lokasi'])
        ->where('status', 1)
        ->where('judul_pengaduan', 'LIKE', '%' . $search . '%')
        ->get());
    }

    public function show(Pengaduan $pengaduan)
    {
        return new PengaduanResource($pengaduan->load(['lokasi']));
    }
}
