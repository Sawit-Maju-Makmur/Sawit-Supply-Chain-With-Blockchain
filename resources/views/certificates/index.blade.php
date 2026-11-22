<!DOCTYPE html>
<html>
<head>
    <title>Daftar Sertifikat Lahan</title>
</head>
<body>
    <h2>Daftar Dokumen Kepatuhan Lingkungan</h2>
    <a href="{{ route('certificates.create') }}"> + Unggah Dokumen Baru</a>
    <br><br>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>ID Petani</th>
            <th>Sertifikasi</th>
            <th>Nama Dokumen</th>
            <th>Aksi</th>
        </tr>
        @foreach($certificates as $cert)
        <tr>
            <td>{{ $cert->farmer_id }}</td>
            <td>{{ $cert->certification_type }}</td>
            <td><a href="{{ asset('storage/' . $cert->file_path) }}" target="_blank">Lihat File</a></td>
            <td>
                <form action="{{ route('certificates.destroy', $cert->id) }}" method="POST" onsubmit="return confirm('Hapus dokumen ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="color: red;">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
</body>
</html>