<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Penugasan extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'penugasan';

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
        'pengaduan_id',
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

    public function pengaduan()
    {
        return $this->belongsTo(Pengaduan::class, 'pengaduan_id');
    }

    public function petugas()
    {
        return $this->belongsToMany(Petugas::class);
    }
}
