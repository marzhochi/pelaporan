<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTugasRequest;
use App\Http\Requests\UpdateTugasRequest;
use App\Http\Resources\Admin\TugasResource;
use App\Models\Tugas;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends Controller
{
    public function index()
    {
        return new TugasResource(Tugas::with(['petugas', 'pengaduan', 'kategori'])->get());
    }
}
