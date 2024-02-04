<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class PetugasTugas extends Model
{
    use HasApiTokens, HasFactory;

    public $table = 'petugas_tugas';

    protected $fillable = [
        'tugas_id',
        'petugas_id',
    ];

    public function tugas()
    {
        return $this->belongsTo(Tugas::class, 'tugas_id');
    }
}
