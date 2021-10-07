<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Company extends Model
{
    protected $table = 'setup_organisasi';
    protected $primaryKey = 'id_organisasi';
	protected $fillable = array( 
		'nama_organisasi',  
		'alamat_organisasi', 
		'kota_organisasi', 
		'provinsi_organisasi', 
		'kode_pos_organisasi', 
		'no_telepon_1', 
		'no_telepon_2', 
		'no_fax', 
		'email',
		'kode_karyawan'
	);

    public $timestamps = false;
}
