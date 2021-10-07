<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'idpengguna', 'id_karyawan', 'username', 'password', 'email', 'remember_token', 'status', 'foto'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    protected $table = 'm_pengguna';
    protected $primaryKey = 'idpengguna';
    public $incrementing = false;
    public $timestamps = false;

    public static function generate_id(){
        $ids = DB::table( 'm_pengguna' )
                ->select( 'idpengguna' )
                ->orderBy( 'idpengguna', 'desc' )
                ->first();

        $latest_id = ( $ids && !empty( $ids->idpengguna ) ) ? $ids->idpengguna : "P00000";
        $latest_id = str_replace( "P", "", $latest_id );
        $latest_id = (int) $latest_id;
        $latest_id++;

        $latest_id = "P" . str_pad( $latest_id, 5, "0", STR_PAD_LEFT );

        return $latest_id;
    }
}