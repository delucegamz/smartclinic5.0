<table>
    <thead>
        <tr>
            <th>NIK Peserta</th>
            <th>No Pemeriksaan Poli</th>
            <th>Nama Peserta</th>
            <th>Factory</th>
            <th>Nama Client</th>
            <th>Nama Departemen</th>
            <th>Poli</th>
            <th>Created_at</th>
            <th>Dokter Rawat</th>
            <th>Keluhan</th>
            <th>Catatan Pemeriksaan</th>
            <th>Kode Diagnosa</th>
            <th>Nama Diagnosa</th>
            <th>Diagnosa Dokter</th>
            <th>Pengguna</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($medrec_list as $medrecdata)
        <tr>
            <td>{{ $medrecdata->participant ? $medrecdata->participant->nik_peserta : '' }}</td>
            <td>{{ $medrecdata->no_pemeriksaan_poli }}</td>
            <td>{{ $medrecdata->nama_peserta }}</td>
            <td>{{ $medrecdata->nama_factory }}</td>
            <td>{{ $medrecdata->nama_client }}</td>
            <td>{{ $medrecdata->nama_departemen }}</td>
            <td>{{ $medrecdata->poliRegistration ? $medrecdata->poliRegistration->poli->nama_poli : '' }}</td>
            <td>{{ $medrecdata->created_at }}</td>
            <td>{{ $medrecdata->dokter_rawat }}</td>
            <td>{{ $medrecdata->keluhan }}</td>
            <td>{{ $medrecdata->catatan_pemeriksaan }}</td>
            <td>{{ $medrecdata->iddiagnosa }}</td>
            <td>{{ $medrecdata->diagnosis ? $medrecdata->diagnosis->nama_diagnosa : ''}}</td>
            <td>{{ $medrecdata->diagnosa_dokter }}</td>
            <td>{{ $medrecdata->user ? $medrecdata->user->staff->nama_karyawan : '' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>