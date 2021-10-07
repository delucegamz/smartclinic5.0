<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Accident extends Model
{
    protected $table = 't_kecelakaan';
    protected $primaryKey = 'id_kecelakaan';
	protected $fillable = array( 
		'no_kecelakaan', 
		'id_pemeriksaan_poli', 
		'id_peserta', 
		'nama_peserta', 
		'nama_factory', 
		'nama_departemen', 
		'nama_client', 
		'tanggal_lapor', 
		'jenis_kecelakaan', 
		'akibat_kecelakaan', 
		'tindakan',
		'penyebab_kecelakaan',
		'rekomendasi',
		'keterangan_kecelakaan',
		'hari_kejadian',
		'tanggal_kejadian',
		'saksi',
		'atasan_langsung',
		'telepon',
		'nama_penanggung_jawab',
		'jabatan',
		'id_pengguna',
		'user_update'
	);

    public static function generate_id(){
        $ids = DB::table( 't_kecelakaan' )
                ->select( 'no_kecelakaan' )
                ->orderBy( 'no_kecelakaan', 'desc' )
                ->where( 'no_kecelakaan', 'LIKE', "%K%" )
                ->first();

        $latest_id = ( $ids && !empty( $ids->no_kecelakaan ) ) ? $ids->no_kecelakaan : "K000000000";
        $latest_id = str_replace( "K", "", $latest_id );
        $latest_id = (int) $latest_id;
        $latest_id++;

        $latest_id = "K" . str_pad( $latest_id, 9, "0", STR_PAD_LEFT );

        return $latest_id;
    }
}
