<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\TugasResource;
use App\Models\Tugas;

class HomeController extends Controller
{
    public function index()
    {
        return new TugasResource(Tugas::with(['petugas', 'pengaduan', 'kategori'])->get());
    }
}
