<!DOCTYPE html>
<html>
<head>
<title>@yield( 'page_title' )</title>
<link rel="stylesheet" href="{{URL::asset('assets/css/bootstrap.min.css')}}">
<link rel="stylesheet" href="{{URL::asset('assets/fonts/fonts.css')}}">
<link rel="stylesheet" href="{{URL::asset('assets/css/font-awesome.min.css')}}">
<link rel="stylesheet" href="{{URL::asset('assets/css/animate.css')}}">
@yield( 'styles' )
<link rel="stylesheet" href="{{URL::asset('assets/css/style.css')}}">
<script type="text/javascript" src="{{URL::asset('assets/js/jquery.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('assets/js/jquery-migrate.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('assets/js/bootstrap.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('assets/js/jquery.validate.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('assets/js/additional-methods.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('assets/js/js.cookie.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('assets/js/jqClock.min.js')}}"></script>
@yield( 'scripts' )
<script type="text/javascript" src="{{URL::asset('assets/js/script.js')}}"></script>
</head>
<body class="dashboard">
    <div id="header">
        <div class="container">
            <div id="header-title"><a href="{{ url( '/' ) }}" title="Back to dashboard"><img src="{{URL::asset('assets/images/logo.png')}}" /></a></div>
            <div id="header-right">
                <div class="login-header-info-wrapper">
                    <span id="clock"></span>
                </div>
            </div>
        </div>
    </div>
    <div id="content-wrapper">
        <div class="container">
            <div id="menu">
                <div class="user-details clearfix">
                    <div class="user-photo">
                        @php
                            $current_user = Auth::user();
                            $idpengguna = $current_user['original']['idpengguna'];
                            $user = App\User::find( $idpengguna );

                            $propict = URL::asset( 'assets/images/guest.png' );

                            if( !empty( $user->foto ) ){
                                $propict = URL::asset( 'uploads/' . $user->foto );
                            }
                        @endphp
                        <img src="{{ $propict }}" />
                    </div>
                    <div class="user-description">
                        <div class="user-description-wrapper">
                            <div class="user-description-container">
                                <?php
                                    $staff = App\Staff::where( 'id_karyawan', '=', $user->id_karyawan )->first();
                                    $jobtitle = App\JobTitle::where( 'id_jabatan', '=', $staff->id_jabatan )->first();
                                ?>
                                <span class="user-id">{{ $idpengguna }}</span><br />
                                <span class="job-title">{{ $staff->nama_karyawan }} ( <a href="{{ url( 'logout' ) }}" class="logout-link">logout</a> )</span><br />
                                <span class="user-name">{{ $jobtitle->nama_jabatan }}</span>
                            </div>
                        </div>
                        
                    </div>
                </div>

                <div id="primary-menu">
                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                        @if( current_user_can( 'master_data' ) )
                        <div class="panel panel-default panel-general">
                            <div class="panel-heading" role="tab" id="headingOne">
                                <h4 class="panel-title">
                                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                        <span class="fa fa-gear"></span> Konfigurasi Umum & Manajemen
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                                <div class="panel-body">
                                    <ul class="fa-ul">
                                        @if( current_user_can( 'data_organisasi' ) )<li><i class="fa fa-li fa-circle"></i><a href="{{ url( '/company' ) }}">Data Perusahaan</a></li>@endif

                                        @if( current_user_can( 'data_legalitas' ) )<li><i class="fa fa-li fa-circle"></i><a href="{{ url( '/legality' ) }}">Data Legalitas</a></li>@endif
                                        @if( current_user_can( 'data_legalitas' ) )<li><i class="fa fa-li fa-circle"></i><a href="{{ url( '/legality-detail' ) }}">Data Pemilik Legalitas</a></li>@endif
                                        @if( current_user_can( 'setting' ) )<li><i class="fa fa-li fa-circle"></i><a href="{{ url( '/company/setting' ) }}">Konfigurasi Umum</a></li>@endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default panel-master">
                            <div class="panel-heading" role="tab" id="headingTwo">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        <span class="fa fa-folder-open"></span> Master Data
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                                <div class="panel-body">
                                    <ul class="fa-ul">
                                        @if( current_user_can( 'data_klien' ) )<li><i class="fa fa-li fa-circle"></i><a href="{{ url( '/client' ) }}">Data Client</a></li>@endif
                                        @if( current_user_can( 'data_factory' ) )<li><i class="fa fa-li fa-circle"></i><a href="{{ url( '/factory' ) }}">Data Factory</a></li>@endif
                                        @if( current_user_can( 'data_unit_kerja' ) )<li><i class="fa fa-li fa-circle"></i><a href="{{ url( '/department' ) }}">Data Unit Kerja</a></li>@endif
                                        @if( current_user_can( 'data_diagnosa' ) )<li><i class="fa fa-li fa-circle"></i><a href="{{ url( '/diagnosis' ) }}">Data Diagnosa</a></li>@endif
                                        @if( current_user_can( 'data_poli' ) )<li><i class="fa fa-li fa-circle"></i><a href="{{ url( '/poli' ) }}">Data Poli</a></li>@endif
                                        @if( current_user_can( 'data_tindakan' ) )<li><i class="fa fa-li fa-circle"></i><a href="{{ url( '/action-observation' ) }}">Data Tindakan/Pengobatan Observasi</a></li>@endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if( current_user_can( 'kepesertaan' ) )
                        <div class="panel panel-default panel-participant">
                            <div class="panel-heading" role="tab" id="headingThree">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                        <span class="fa fa-address-card"></span> Kepesertaan
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                                <div class="panel-body">
                                    <ul class="fa-ul">
                                        @if( current_user_can( 'data_peserta' ) )<li><i class="fa fa-li fa-circle"></i><a href="{{ url( '/participant' ) }}">Data Peserta</a></li>@endif
                                        @if( current_user_can( 'data_peserta' ) )<!--<li><i class="fa fa-li fa-circle"></i><a href="{{ url( '/participant/anc' ) }}">Data Peserta Hamil</a></li>-->@endif
                                        @if( current_user_can( 'data_peserta' ) )<li><i class="fa fa-li fa-circle"></i><a href="{{ url( '/participant/import' ) }}">Import Data Peserta</a></li>@endif
                                        <!--<li><i class="fa fa-li fa-circle"></i><a href="{{ url( '/participant-pregnant' ) }}">Data Peserta Hamil</a></li>
                                        <li><i class="fa fa-li fa-circle"></i><a href="{{ url( '/participant-tb' ) }}">Data Peserta TB</a></li>-->
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if( current_user_can( 'karyawan_user' ) )
                        <div class="panel panel-default panel-employee">
                            <div class="panel-heading" role="tab" id="headingFour">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                        <span class="fa fa-users"></span> Karyawan dan User
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
                                <div class="panel-body">
                                    <ul class="fa-ul">
                                        @if( current_user_can( 'data_jabatan' ) )<li><i class="fa fa-li fa-circle"></i><a href="{{ url( '/jobtitle' ) }}">Data Jabatan</a></li>@endif
                                        @if( current_user_can( 'data_karyawan' ) )<li><i class="fa fa-li fa-circle"></i><a href="{{ url( '/staff' ) }}">Data Karyawan</a></li>@endif
                                        @if( current_user_can( 'data_pengguna' ) )<li><i class="fa fa-li fa-circle"></i><a href="{{ url( '/user' ) }}">Data Pengguna</a></li>@endif
                                        <li><i class="fa fa-li fa-circle"></i><a href="{{ url( '/user/profile' ) }}">Profil Anda</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @else
                         <div class="panel panel-default panel-employee">
                            <div class="panel-heading" role="tab" id="headingFour">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                        <span class="fa fa-users"></span> Profil
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
                                <div class="panel-body">
                                    <ul class="fa-ul">
                                       <li><i class="fa fa-li fa-circle"></i><a href="{{ url( '/user/profile' ) }}">Profil Anda</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if( current_user_can( 'pemeriksaan_medis' ) )
                        <div class="panel panel-default panel-medical-check">
                            <div class="panel-heading" role="tab" id="headingFive">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                        <span class="fa fa-ambulance"></span> Pemeriksaan Medis
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseFive" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFive">
                                <div class="panel-body">
                                    <ul class="fa-ul">
                                        @if( current_user_can( 'pendaftaran_poli' ) )<li><i class="fa fa-li fa-circle"></i><a href="{{ url( '/poliregistration' ) }}">Pendaftaran Poli</a></li>@endif
                                        @if( current_user_can( 'pendaftaran_igd' ) )<li><i class="fa fa-li fa-circle"></i><a href="{{ url( '/poliregistration/emergency' ) }}">Pendaftaran IGD</a></li>@endif
                                        @if( current_user_can( 'pemeriksaan_dokter' ) )<li><i class="fa fa-li fa-circle"></i><a href="{{ url( '/medical-record' ) }}">Pemeriksaan Dokter</a></li>@endif
                                        @if( current_user_can( 'observasi' ) )<li><i class="fa fa-li fa-circle"></i><a href="{{ url( '/observation' ) }}">Observasi</a></li>@endif
                                        @if( current_user_can( 'ambulance' ) )<li><i class="fa fa-li fa-circle"></i><a href="{{ url( '/ambulance' ) }}">Ambulance</a></li>@endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if( current_user_can( 'laporan' ) )
                        <div class="panel panel-default panel-report">
                            <div class="panel-heading" role="tab" id="headingSix">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                                        <span class="fa fa-file-text"></span> Laporan
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseSix" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingSix">
                                <div class="panel-body">
                                    <ul class="fa-ul">
                                        @if( current_user_can( 'laporan_karyawan' ) )<li><i class="fa fa-li fa-circle"></i><a href="{{ url( 'report/staff' ) }}">Laporan Karyawan</a></li>@endif
                                        @if( current_user_can( 'laporan_peserta' ) )<li><i class="fa fa-li fa-circle"></i><a href="{{ url( 'report/participant' ) }}">Laporan Peserta</a></li>@endif
                                        @if( current_user_can( 'laporan_organisasi' ) )<li><i class="fa fa-li fa-circle"></i><a href="{{ url( 'report/organization' ) }}">Laporan Organisasi</a></li>@endif
                                        @if( current_user_can( 'laporan_kunjungan' ) )<li><i class="fa fa-li fa-circle"></i><a href="{{ url( 'report/visit' ) }}">Laporan Kunjungan</a></li>@endif
                                        @if( current_user_can( 'laporan_rekap_kunjungan' ) )<li><i class="fa fa-li fa-circle"></i><a href="{{ url( 'report/recap' ) }}">Laporan Rekap Kunjungan</a></li>@endif
                                        @if( current_user_can( 'laporan_rekam_medis' ) )<li><i class="fa fa-li fa-circle"></i><a href="{{ url( 'reports/medrec2' ) }}">Laporan Rekam Medis</a></li>@endif
                                        @if( current_user_can( 'laporan_rekam_medis' ) )<li><i class="fa fa-li fa-circle"></i><a href="{{ url( 'report/accident' ) }}">Laporan Kecelakaan Kerja</a></li>@endif
                                        @if( current_user_can( 'laporan_anc' ) )<li><i class="fa fa-li fa-circle"></i><a href="{{ url( 'report/anc' ) }}">Laporan ANC</a></li>@endif
                                        @if( current_user_can( 'laporan_observasi' ) )<li><i class="fa fa-li fa-circle"></i><a href="{{ url( 'report/observation' ) }}">Laporan Observasi</a></li>@endif
                                        @if( current_user_can( 'laporan_surat' ) )<li><i class="fa fa-li fa-circle"></i><a href="{{ url( 'report/letter' ) }}">Laporan Surat-Surat</a></li>@endif
                                        @if( current_user_can( 'laporan_pendaftaran' ) )<li><i class="fa fa-li fa-circle"></i><a href="{{ url( 'report/registration' ) }}">Laporan Pendaftaran</a></li>@endif
                                        @if( current_user_can( 'laporan_ambulance' ) )<li><i class="fa fa-li fa-circle"></i><a href="{{ url( 'report/ambulance' ) }}">Laporan Ambulance</a></li>@endif
                                        @if( current_user_can( 'laporan_stock_obat' ) )<li><i class="fa fa-li fa-circle"></i><a href="{{ url( 'report/medicinestock' ) }}">Laporan Stock Obat</a></li>@endif
                                        @if( current_user_can( 'laporan_resep_dokter' ) )<li><i class="fa fa-li fa-circle"></i><a href="{{ url( 'report/doctorrecipe' ) }}">Laporan Resep Dokter</a></li>@endif
                                        @if( current_user_can( 'laporan_obat_keluar' ) )<li><i class="fa fa-li fa-circle"></i><a href="{{ url( 'report/medicineout' ) }}">Laporan Obat Keluar</a></li>@endif
                                        @if( current_user_can( 'laporan_obat_masuk' ) )<li><i class="fa fa-li fa-circle"></i><a href="{{ url( 'report/medicinein' ) }}">Laporan Obat Masuk</a></li>@endif
                                        @if( current_user_can( 'laporan_top_10_penyakit' ) )<li><i class="fa fa-li fa-circle"></i><a href="{{ url( 'report/top10disease' ) }}">Laporan Top 10 Penyakit</a></li>@endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if( current_user_can( 'farmasi' ) )
                        <div class="panel panel-default panel-farmation">
                            <div class="panel-heading" role="tab" id="headingSeven">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                                        <span class="fa fa-medkit"></span> Farmasi
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseSeven" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingSeven">
                                <div class="panel-body">
                                    <ul class="fa-ul">
                                        @if( current_user_can( 'data_golongan_obat' ) )<li><i class="fa fa-li fa-circle"></i><a href="{{ url( '/medicine-group' ) }}">Data Golongan Obat</a></li>@endif
                                        @if( current_user_can( 'data_obat' ) )<li><i class="fa fa-li fa-circle"></i><a href="{{ url( '/medicine' ) }}">Data Obat</a></li>@endif
                                        @if( current_user_can( 'data_obat_masuk' ) )<li><i class="fa fa-li fa-circle"></i><a href="{{ url( '/medicine-in' ) }}">Data Obat Masuk</a></li>@endif
                                        @if( current_user_can( 'data_obat_keluar' ) )<li><i class="fa fa-li fa-circle"></i><a href="{{ url( '/medicine-out' ) }}">Data Obat Keluar</a></li>@endif
                                        @if( current_user_can( 'resep_obat_dokter' ) )<li><i class="fa fa-li fa-circle"></i><a href="{{ url( '/doctor-recipe' ) }}">Resep Obat Dokter</a></li>@endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if( current_user_can( 'surat' ) )
                        <div class="panel panel-default panel-letter">
                            <div class="panel-heading" role="tab" id="headingEight">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                                        <span class="fa fa-envelope"></span> Surat-Surat
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseEight" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingEight">
                                <div class="panel-body">
                                    <ul class="fa-ul">
                                        @if( current_user_can( 'surat_rujukan' ) )<li><i class="fa fa-li fa-circle"></i><a href="{{ url( '/reference-letter' ) }}">Surat Rujukan</a></li>@endif
                                        @if( current_user_can( 'surat_keterangan_sakit' ) )<li><i class="fa fa-li fa-circle"></i><a href="{{ url( '/sick-letter' ) }}">Surat Keterangan Sakit</a></li>@endif
                                        @if( current_user_can( 'surat_cuti' ) )<li><i class="fa fa-li fa-circle"></i><a href="{{ url( '/day-off-letter' ) }}">Surat Cuti</a></li>@endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div id="content">
                <div class="content-container">
                    @yield( 'content' )
                </div>
            </div>
        </div>
    </div>

    <div id="login-footer">
        <div class="container">
            <div class="login-footer-copyright">
                Copyright &copy; 2021 PT Indo Graha Dharmala.
            </div>
            <div class="login-colorpicker-wrapper">
                <p>Set Your Best Color</p>

                <div class="login-colorpicker">
                    <ul class="clearfix">
                        <li><a href="#" data-color="#43a6d4" class="color-43a6d4"></a></li>
                        <li><a href="#" data-color="#a3e0fd" class="color-a3e0fd"></a></li>
                        <li><a href="#" data-color="#d4fda3" class="color-d4fda3"></a></li>
                        <li><a href="#" data-color="#f1a3fd" class="color-f1a3fd"></a></li>
                        <li><a href="#" data-color="#89a898" class="color-89a898"></a></li>
                        <li><a href="#" data-color="#8aa669" class="color-8aa669"></a></li>
                        <li><a href="#" data-color="#a96eb2" class="color-a96eb2"></a></li>
                        <li><a href="#" data-color="#5b6d64" class="color-5b6d64"></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>  
</body>
</html>