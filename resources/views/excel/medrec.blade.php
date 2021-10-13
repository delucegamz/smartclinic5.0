<table>
    <thead>
        <tr>
            <th>NIK Peserta</th>
            <th>Nama Peserta</th>
            <th>Factory</th>
            <th>Created_at</th>
            <th>Kode Diagnosa</th>
            <th>Nama Diagnosa</th>
            <th>View Data</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($medrec_list as $medrecdata)
        <tr>
            <td>{{ $medrecdata->nik_peserta }}</td>
            <td>{{ $medrecdata->nama_peserta }}</td>
            <td>{{ $medrecdata->nama_factory }}</td>
            <td>{{ $medrecdata->created_at }}</td>
            <td>{{ $medrecdata->iddiagnosa }}</td>
            <td>{{ $medrecdata->nama_diagnosa}}</td>

        </tr>
        @endforeach
    </tbody>
</table>