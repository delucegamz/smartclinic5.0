<table>
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
            <th style="">Tanggal Pelaporan</th>
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