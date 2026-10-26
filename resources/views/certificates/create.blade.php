<!DOCTYPE html>
<html>
<head><title>Unggah Sertifikat Lahan</title></head>
<body>
    <h2>Form Bukti Kepatuhan Standar Lingkungan</h2>
    <form action="{{ route('certificates.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <p><label>ID Petani:</label><br>
        <input type="text" name="farmer_id" required></p>

        <p><label>Jenis Sertifikasi:</label><br>
        <select name="certification_type" required>
            <option value="ISPO">ISPO</option>
            <option value="RSPO">RSPO</option>
            <option value="SKGR">SKGR</option>
            <option value="Lainnya">Lainnya</option>
        </select></p>

        <p><label>Dokumen Sertifikat (PDF/JPG):</label><br>
        <input type="file" name="document" accept=".pdf, .jpg, .jpeg, .png" required></p>

        <button type="submit">Unggah Bukti</button>
        <a href="{{ route('certificates.index') }}">Kembali ke Daftar</a>
    </form>
</body>
</html>