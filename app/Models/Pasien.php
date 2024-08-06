<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pasien extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pasien';

    protected $fillable = [
        'no_rm', 'nama', 'tmp_lahir', 'tgl_lahir', 'jk', 'alamat_lengkap',
        'kelurahan', 'kecamatan', 'kabupaten', 'kodepos', 'agama',
        'status_menikah', 'pendidikan', 'pekerjaan', 'kewarganegaraan',
        'no_hp', 'umur', 'kategori_umur'
    ];

    protected $dates = ['tgl_lahir', 'deleted_at'];

    // Mutator to set umur and kategori_umur based on tgl_lahir
    public function setTglLahirAttribute($value)
    {
        $this->attributes['tgl_lahir'] = $value;

        if ($value) {
            $birthdate = Carbon::parse($value);
            $age = $birthdate->age;
            $this->attributes['umur'] = $age;
            $this->attributes['kategori_umur'] = $age >= 18 ? 'Dewasa' : 'Anak-Anak';
        }
    }


    function getGeneralUncent()
    {
        return $this->general_uncent != null ? asset('images/pasien/' . $this->general_uncent) : null;
    }

    function rekamGigi()
    {
        return RekamGigi::where('pasien_id', $this->id)->get();
    }


    function isRekamGigi()
    {
        return RekamGigi::where('pasien_id', $this->id)->get()->count() > 0 ? true : false;
    }

    function statusPasien()
    {
        $lastData = Carbon::createFromFormat('Y-m-d H:i:s', '2023-05-22 18:00:00');

        $rekam = Rekam::where('pasien_id', $this->id)
            ->whereIn('status', [4, 5])
            ->count();
        if ($rekam > 0) {
            if ($this->created_at > $lastData) {
                return ' <span class="badge badge-outline-primary">
                              <i class="fa fa-circle text-primary mr-1"></i>
                              Sudah Periksa
                        </span>';
            } else {
                return ' <span class="badge badge-outline-success">
                              <i class="fa fa-circle text-success mr-1"></i>
                              Sudah Periksa
                        </span>';
            }
        } else {
            if ($this->created_at > $lastData) {
                return ' <span class="badge badge-outline-primary">
                              <i class="fa fa-circle text-primary mr-1"></i>
                              Pasien Baru
                        </span>';
            } else {
                return ' <span class="badge badge-outline-danger">
                              <i class="fa fa-circle text-danger mr-1"></i>
                              Pasien Lama
                        </span>';
            }
        }
    }
}
