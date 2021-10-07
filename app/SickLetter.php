<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class SickLetter extends Model
{
    protected $table = 't_surat_sakit';
    protected $primaryKey = 'id_surat_sakit';
	protected $fillable = array( 'no_surat_sakit', 'id_pemeriksaan_poli', 'id_peserta', 'umur_peserta', 'dari_tanggal', 'sampai_tanggal', 'lama', 'dokter_jaga', 'tanggal_surat_sakit', 'id_pengguna', 'user_update', 'status' );

	public static function generate_id(){
        $ids = DB::table( 't_surat_sakit' )
                ->select( 'no_surat_sakit' )
                ->orderBy( 'no_surat_sakit', 'desc' )
                ->where( 'no_surat_sakit', 'LIKE', "%SKS%" )
                ->first();

        $latest_id = ( $ids && !empty( $ids->no_surat_sakit ) ) ? $ids->no_surat_sakit : "SKS0000000";
        $latest_id = str_replace( "SKS", "", $latest_id );
        $latest_id = (int) $latest_id;
        $latest_id++;

        $latest_id = "SKS" . str_pad( $latest_id, 7, "0", STR_PAD_LEFT );

        return $latest_id;
    }
}
