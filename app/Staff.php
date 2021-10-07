<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Staff extends Model
{
    protected $table = 'm_karyawan';
    protected $primaryKey = 'id_karyawan';
	protected $fillable = array( 
        'kode_karyawan', 
        'nik_karyawan', 
        'nama_karyawan', 
        'id_jabatan', 
        'jenis_kelamin', 
        'status_kawin', 
        'jumlah_anak', 
        't_badan', 
        'b_badan', 
        'tempat_lahir', 
        'tanggal_lahir',
        'alamat',
        'kota',
        'provinsi',
        'kode_pos',
        'no_telepon',
        'email',
        'agama',
        'bank',
        'no_rekening',
        'jenis_id',
        'no_id',
        'no_KK',
        'no_bpjs',
        'no_jamsostek',
        'foto_karyawan',
        'status',
        'id_pengguna'
    );

    public static function generate_id(){
        $kode_karyawan = get_staff_code();
        
        $src_id = $kode_karyawan . date( 'y' );

        $ids = DB::table( 'm_karyawan' )
                ->select( 'kode_karyawan' )
                ->orderBy( 'kode_karyawan', 'desc' )
                ->where( 'kode_karyawan', 'LIKE', $src_id . "%" )
                ->first();

        $latest_id = ( $ids && !empty( $ids->kode_karyawan ) ) ? $ids->kode_karyawan : $src_id . "000000";
        $latest_id = str_replace( $src_id, "", $latest_id );
        $latest_id = (int) $latest_id;
        $latest_id++;

        return $src_id . str_pad( $latest_id, 6, "0", STR_PAD_LEFT );
    }

    public static function generate_nik(){
        $kode_karyawan = get_staff_code();

        $ids = DB::table( 'm_karyawan' )
                ->select( 'nik_karyawan' )
                ->orderBy( 'nik_karyawan', 'desc' )
                ->where( 'nik_karyawan', 'LIKE', $kode_karyawan . "%" )
                ->first();

        $latest_id = ( $ids && !empty( $ids->nik_karyawan ) ) ? $ids->nik_karyawan : $kode_karyawan . "00000";
        $latest_id = str_replace( $kode_karyawan, "", $latest_id );
        $latest_id += 0;
        $latest_id++;

        return $kode_karyawan . str_pad( $latest_id, 5, "0", STR_PAD_LEFT );
    }
}
