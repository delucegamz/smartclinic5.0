<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class CompanyStructure extends Model
{
    protected $table = 'm_struktur_organisasi';
    protected $primaryKey = 'id_struktur_organisasi';
	protected $fillable = array( 
		'nama_jabatan',  
		'id_karyawan'
	);
}
