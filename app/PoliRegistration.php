<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class PoliRegistration extends Model
{
    protected $table = 't_pendaftaran';
    protected $primaryKey = 'id_pendaftaran';
	protected $fillable = array( 
        'no_daftar', 
        'id_peserta', 
        'id_poli', 
        'no_antrian', 
        'tgl_daftar', 
        'waktu_daftar', 
        'tgl_selesai', 
        'waktu_selesai', 
        'catatan_pendaftaran', 
        'status', 
        'id_pengguna', 
        'user_update' 
    );

	public static function generate_id(){
		$date = date( 'Ymd' );

        $ids = DB::table( 't_pendaftaran' )
                ->select( 'no_daftar' )
                ->where( 'no_daftar', 'LIKE', "%M" . $date . "%" )
                ->orderBy( 'no_antrian', 'desc' )
                ->first();

        $latest_id = ( $ids && !empty( $ids->no_daftar ) ) ? $ids->no_daftar : "M" . $date . "000";
        $latest_id = str_replace( "M" . $date, "", $latest_id );
        $latest_id = (int) $latest_id;
        $latest_id++;

        $latest_id = "M$date" . str_pad( $latest_id, 3, "0", STR_PAD_LEFT );

        return $latest_id;
    }

    public static function get_ordering_no(){
		$date = date( 'Ymd' );

        $no_antrian = DB::table( 't_pendaftaran' )
                ->select( 'no_antrian' )
                ->where( 'created_at', '>=', date( "Y-m-d" ) . " 00:00:00" ) 
                ->where( 'created_at', '<=', date( "Y-m-d" ) . " 23:59:59" ) 
                ->orderBy( 'no_antrian', 'desc' )
                ->first();

        $latest_order = ( $no_antrian && !empty( $no_antrian->no_antrian ) ) ? $no_antrian->no_antrian : 0;
        $latest_order++;

        return $latest_order;
    }

    public static function get_registration_no( $id = 0 ){
        $poliregistration = PoliRegistration::find( $id );

        return $poliregistration->no_daftar ? $poliregistration->no_daftar : '';
    }

    public function poli()
    {
        return $this->belongsTo(Poli::class, 'id_poli');
    }
}
