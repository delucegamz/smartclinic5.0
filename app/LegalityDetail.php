<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class LegalityDetail extends Model
{
    protected $table = 't_legalitas';
    protected $primaryKey = 'id_t_legalitas';
	protected $fillable = array( 
		'nama_pemilik',  
		'nama_legalitas',
		'exp_legalitas',
		'status',
		'keterangan'
	);
}
