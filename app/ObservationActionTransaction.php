<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class ObservationActionTransaction extends Model
{
    protected $table = 't_pemeriksaan_observasi';
    protected $primaryKey = 'no_pemeriksaan_observasi';
	protected $fillable = array( 
		'id_pemeriksaan_observasi', 
		'id_observasi', 
		'id_pengguna'
	);
}
