<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tugas extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'tugas';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const STATUS_RADIO = [
        '1' => 'Tugas Baru',
        '2' => 'Tugas Selesai',
    ];

    protected $fillable = [
        'jenis_id',
        'lokasi_id',
        'judul_tugas',
        'keterangan',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function jenis()
    {
        return $this->belongsTo(Jenis::class, 'jenis_id');
    }

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class, 'lokasi_id');
    }

    public function petugas()
    {
        return $this->belongsToMany(Petugas::class, 'petugas_tugas', 'tugas_id', 'petugas_id');
    }
}
