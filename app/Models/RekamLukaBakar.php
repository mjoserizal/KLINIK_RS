<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekamLukaBakar extends Model
{
    use HasFactory;

    // Define the table associated with the model
    protected $table = 'rekam_luka_bakar';

    // Specify the fillable fields
    protected $fillable = [
        'no_rekam',
        'tgl_rekam',
        'pasien_id',
        'berat_badan',
        'persen_luka_bakar',
        'cairan',
        'status',
    ];

    // Define the relationship with Pasien model
    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'pasien_id');
    }

    // Define the relationship with Dokter model

    public function status_display()
    {
        switch ($this->status) {
            case 1:
                return '<span class="badge badge-outline-warning">
                            <i class="fa fa-circle text-warning mr-1"></i>
                             Antrian
                        </span>';
                break;
            case 2:
                return '<span class="badge badge-info light">
                            <i class="fa fa-circle text-info mr-1"></i>
                            Diperiksa
                        </span>';
                break;
            case 3:
                return '<span class="badge badge-warning light" style="width:100px">
                          Sudah Diperiksa
                        </span>';
                break;
            case 4:
                return '<span class="badge badge-danger light">
                            <i class="fa fa-circle text-danger mr-1"></i>
                            Lanjut
                        </span>';
                break;
            case 5:
                return '<span class="badge badge-primary light" style="width:100px">
                            <i class="fa fa-check text-primary mr-1"></i>
                            Selesai
                        </span>';
                break;
            default:
                # code...
                break;
        }
    }
}
