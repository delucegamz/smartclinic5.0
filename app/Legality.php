<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Legality extends Model
{
    protected $table = 'm_legalitas';
    protected $primaryKey = 'id_legalitas';
	protected $fillable = array( 
		'nama_legalitas',  
		'keterangan'
	);
}
