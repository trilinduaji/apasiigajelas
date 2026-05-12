<div class="section-head">
    <h3 class="section-title">Tambah Program Donasi Baru</h3>
</div>
<div class="panel" style="padding:24px;">
    <form action="index.php?route=program/add" method="post" enctype="multipart/form-data">
        <input type="hidden" name="action" value="add">

        <div class="field">
            <label>Judul Program <span style="color:#dc2626;">*</span></label>
            <input type="text" name="name" placeholder="cth: Beasiswa Anak Yatim 2026" maxlength="150" required>
            <small style="color:#6b7280;">Maks. 150 karakter</small>
        </div>

        <div class="field">
            <label>Deskripsi Program</label>
            <textarea name="description" rows="3" placeholder="Jelaskan tujuan, manfaat, dan sasaran penerima program..."></textarea>
        </div>

        <div class="field">
            <label>Target Dana (Rp) <span style="color:#dc2626;">*</span></label>
            <input type="number" name="target" placeholder="cth: 50000000" min="100000" step="50000" required>
            <small style="color:#6b7280;">Minimal Rp 100.000</small>
        </div>

        <div class="field">
            <label>Tanggal Selesai <span style="color:#dc2626;">*</span></label>
            <input type="date" name="deadline" required>
        </div>

        <div class="field">
            <label>Kategori <span style="color:#dc2626;">*</span></label>
            <select name="category" required>
                <option value="">Pilih Kategori</option>
                <option value="Pendidikan">Pendidikan</option>
                <option value="Kesehatan">Kesehatan</option>
                <option value="Keagamaan">Keagamaan</option>
                <option value="Pangan">Pangan &amp; Gizi</option>
                <option value="Infrastruktur">Infrastruktur</option>
                <option value="Lingkungan">Lingkungan</option>
                <option value="Sosial">Sosial</option>
                <option value="Kedaruratan">Kedaruratan</option>
            </select>
        </div>

        <div class="field">
            <label>Status Awal</label>
            <select name="status">
                <option value="active">Aktif (langsung publish)</option>
                <option value="draft">Draft (simpan dulu)</option>
            </select>
        </div>

        <div class="field">
            <label>Gambar Banner Program</label>
            <input type="file" name="image" accept="image/jpeg,image/png,image/webp">
            <small style="color:#6b7280;">JPG / PNG / WEBP — maks. 2 MB. Rasio ideal 16:9 (mis. 800×450 px). Kosongkan jika tidak ingin upload gambar.</small>
        </div>

        <div style="display:flex;gap:12px;justify-content:flex-end;margin-top:16px;">
            <a class="btn light" href="index.php?route=app&page=program-staff">Batal</a>
            <button class="btn green" type="submit">Simpan Program</button>
        </div>
    </form>
</div>
