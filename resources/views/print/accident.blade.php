<html>

    <head>
        <title>DAFTAR KUNJUNGAN PASIEN KECELAKAAN KERJA</title>
        <link rel="stylesheet" href="{{URL::asset('assets/css/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{URL::asset('assets/css/print.css')}}">
        <script type="text/javascript">
            window.print();
        </script>
    </head>

    <body>
        <div id="header" class="text-center">
            <h2>{{ get_company_name() }}</h2>
            <h5>{{ get_company_address() }}</h5>
        </div>
        <?php
            $date_from_formatted = ( !empty( $start_date ) ) ? date( 'd M Y', strtotime( $start_date ) ) : '';
            $date_to_formatted = ( !empty( $end_date ) ) ? date( 'd M Y', strtotime( $end_date ) ) : date( 'd M Y' );

            $current_user = Auth::user();
            $idpengguna = $current_user['original']['idpengguna'];
            $user = App\User::find( $idpengguna );
            $staff = App\Staff::where( 'id_karyawan', '=', $user->id_karyawan )->first();
        ?>
        <h2 style="text-align: center; margin: 30px 0 5px;">DAFTAR KUNJUNGAN PASIEN KECELAKAAN KERJA</h2>
        <h3 style="text-align: center; margin: 0 0 30px;">PERIODE: {{ $date_from_formatted }} - {{ $date_to_formatted }}
        </h3>
        <h5 style="text-align: center; margin: 0 0 10px;">DATE PRINTED : {{ date( 'd-m-Y' ) }}</h5>
        <h5 style="text-align: center; margin: 0 0 30px;">PRINTED BY : {{ $staff->nama_karyawan }}</h5>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th style="width:50px;">No. </th>
                    <th style="">KAT</th>
                    <th style="">Nama</th>
                    <th style="">NIK</th>
                    <th style="">Tanggal Lahir</th>
                    <th style="">L/P</th>
                    <th style="">Factory</th>
                    <th style="">Departemen</th>
                    <th style="">Tanggal Kejadian</th>
                    <th style="">Tindakan</th>
                </tr>
            </thead>
            <tbody>
                @php
                $i = 1;
                @endphp
                @foreach( $medicalRecords as $record )
                <tr>
                    <td>{{ $i }}</td>
                    <td>{{ ( $record->uraian == 22 ? 'KK' : 'Kontrol' ) }}</td>
                    <td>{{ $record->participant->nama_peserta }}</td>
                    <td>{{ $record->participant->nik_peserta }}</td>
                    <td>{{ date( 'd-m-Y', strtotime( $record->participant->tanggal_lahir ) ) }}</td>
                    <td>{{ ucwords( $record->participant->jenis_kelamin ) }}</td>
                    <td>{{ $record->nama_factory }}</td>
                    <td>{{ $record->nama_departemen }}</td>
                    <td>{{ $record->accident ? $record->accident->tanggal_lapor : '' }}</td>
                    <td>
                        {{
                        $record->sick_letter_count
                        ? 'SKS'
                        : ($record->referece_letter_count ? 'RUJUKAN' :'-')
                        }}
                    </td>
                </tr>
                @php
                $i++;
                @endphp
                @endforeach
            </tbody>
        </table>
    </body>

</html>