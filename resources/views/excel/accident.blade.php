<table>
    <thead>
        <tr>
            <th>No.</th>
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
            <th>Tanggal Pelaporan</th>
            <th>Tindakan</th>

            <th>Jenis Kecelakaan</th>
            <th>Akibat Kecelakaan</th>
            <th>Tindakan Kecelakaan</th>
            <th>Penyebaab Kecelakaan</th>
            <th>Rekomendasi</th>
            <th>Keterangan Kecelakaan</th>
            <th>Hari Kejadian</th>
            <th>Tanggal Kejadian</th>
            <th>Saksi</th>
            <th>Atasan Langsung</th>
            <th>Telepon</th>
            <th>Nama Penanggung Jawab</th>
            <th>Jabatan</th>
        </tr>
    </thead>

    <tbody>
        @php
        $i=1;
        @endphp
        @foreach ($medicalRecords as $record)
        <tr>
            <td>{{$i}}</td>
            <td>{{ $record->participant ? $record->participant->nik_peserta : '' }}</td>
            <td>{{ $record->no_pemeriksaan_poli }}</td>
            <td>{{ $record->nama_peserta }}</td>
            <td>{{ $record->nama_factory }}</td>
            <td>{{ $record->nama_client }}</td>
            <td>{{ $record->nama_departemen }}</td>
            <td>{{ $record->poliRegistration ? $record->poliRegistration->poli->nama_poli : '' }}</td>
            <td>{{ $record->created_at }}</td>
            <td>{{ $record->dokter_rawat }}</td>
            <td>{{ $record->keluhan }}</td>
            <td>{{ $record->catatan_pemeriksaan }}</td>
            <td>{{ $record->iddiagnosa }}</td>
            <td>{{ $record->diagnosis ? $record->diagnosis->nama_diagnosa : ''}}</td>
            <td>{{ $record->diagnosa_dokter }}</td>
            <td>{{ $record->user ? $record->user->staff->nama_karyawan : '' }}</td>
            <td>{{ $record->accident ? $record->accident->tanggal_lapor : '' }}</td>
            <td>
                {{
                $record->sick_letter_count
                ? 'SKS'
                : ($record->referece_letter_count ? 'RUJUKAN' :'-')
                }}
            </td>
            <td>{{$record->accident ? $record->accident->jenis_kecelakaan : ''}}</td>
            <td>{{$record->accident ? $record->accident->akibat_kecelakaan : ''}}</td>
            <td>{{$record->accident ? $record->accident->tindakan : ''}}</td>
            <td>{{$record->accident ? $record->accident->penyebab_kecelakaan : ''}}</td>
            <td>{{$record->accident ? $record->accident->rekomendasi : ''}}</td>
            <td>{{$record->accident ? $record->accident->keterangan_kecelakaan : ''}}</td>
            <td>{{$record->accident ? $record->accident->hari_kejadian : ''}}</td>
            <td>{{$record->accident ? $record->accident->tanggal_kejadian : ''}}</td>
            <td>{{$record->accident ? $record->accident->saksi : ''}}</td>
            <td>{{$record->accident ? $record->accident->atasan_langsung : ''}}</td>
            <td>{{$record->accident ? $record->accident->telepon : ''}}</td>
            <td>{{$record->accident ? $record->accident->nama_penanggung_jawab : ''}}</td>
            <td>{{$record->accident ? $record->accident->jabatan : ''}}</td>
        </tr>
        $@php
        $i++;
        @endphp
        @endforeach
    </tbody>
</table>