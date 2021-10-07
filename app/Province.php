<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Province extends Model
{
    protected $table = 'm_propinsi';
    protected $primaryKey = 'id_propinsi';
	protected $fillable = array( 'nama_propinsi' );
}
