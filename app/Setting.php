<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Setting extends Model
{
    protected $table = 'm_setting';
    protected $primaryKey = 'ID';
	protected $fillable = array( 'setting_name', 'setting_value' );
}
