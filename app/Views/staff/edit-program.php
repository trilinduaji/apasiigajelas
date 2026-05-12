<?php
/**
 * Edit Program - Staff View
 * Form untuk mengedit program yang sudah ada
 */
if (current_role() !== 'staff') {
    echo '<div class="flash flash-error">Hanya staff yang dapat mengubah program.</div>';
    return;
}

$id = $_GET['id'] ?? '';
$program = ProgramModel::findByKode($id);

if (!$program || ($program['status'] ?? '') === 'deleted') {
    echo '<div class="flash flash-error">Program tidak ditemukan.</div>';
    echo '<a class="btn light" href="index.php?route=app&page=program-staff">Kembali</a>';
    return;
}

$categories = ['Pendidikan', 'Kesehatan', 'Sosial', 'Kedaruratan', 'Bencana Alam', 'Lingkungan', 'Pangan', 'Keagamaan', 'Infrastruktur'];
if (!in_array($program['category'], $categories, true) && $program['category'] !== '') {
    array_unshift($categories, $program['category']);
}

$targetRupiah = (int) $program['target'];
?>
<a class="btn light" href="index.php?route=app&page=program-detail&id=<?= e($id) ?>" style="margin-bottom:14px;">← Kembali ke Detail</a>

<div class="section-head">
    <h3 class="section-title">Edit Program: <?= e($program['name']) ?></h3>
</div>

<div class="panel" style="padding:24px;">
    <?php if (!empty($program['image'])): ?>
        <div style="margin-bottom:18px;">
            <small style="color:#6b7280;display:block;margin-bottom:6px;">Gambar saat ini</small>
            <img src="<?= e(pub($program['image'])) ?>" alt="<?= e($program['name']) ?>" style="max-width:280px;max-height:160px;border-radius:10px;object-fit:cover;">
        </div>
    <?php endif; ?>

    <form action="index.php?route=program/edit" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= e($id) ?>">

        <div class="field">
            <label>Judul Program <span style="color:#dc2626;">*</span></label>
            <input type="text" name="name" value="<?= e($program['name']) ?>" maxlength="150" required>
        </div>

        <div class="field">
            <label>Deskripsi Program</label>
            <textarea name="description" rows="4"><?= e($program['description'] ?? '') ?></textarea>
        </div>

        <div class="field">
            <label>Target Dana (Rp) <span style="color:#dc2626;">*</span></label>
            <input type="number" name="target" value="<?= e($targetRupiah) ?>" min="100000" step="50000" required>
            <small style="color:#6b7280;">Saat ini terkumpul <?= formatRupiahFull((float)$program['collected']) ?>.</small>
        </div>

        <div class="field">
            <label>Tanggal Selesai (Deadline)</label>
            <input type="date" name="deadline" value="<?= e($program['deadline']) ?>">
        </div>

        <div class="field">
            <label>Kategori <span style="color:#dc2626;">*</span></label>
            <select name="category" required>
                <?php foreach ($categories as $c): ?>
                    <option value="<?= e($c) ?>" <?= $program['category'] === $c ? 'selected' : '' ?>><?= e($c) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="field">
            <label>Status</label>
            <select name="status">
                <option value="active"   <?= $program['status'] === 'active'   ? 'selected' : '' ?>>Aktif</option>
                <option value="closed"   <?= $program['status'] === 'closed'   ? 'selected' : '' ?>>Selesai</option>
            </select>
        </div>

        <div class="field">
            <label>Ganti Gambar Banner</label>
            <input type="file" name="image" accept="image/jpeg,image/png,image/webp">
            <small style="color:#6b7280;">JPG / PNG / WEBP — maks. 2 MB. Kosongkan jika tidak ingin mengubah gambar.</small>
        </div>

        <div style="display:flex;gap:12px;justify-content:flex-end;margin-top:16px;">
            <a class="btn light" href="index.php?route=app&page=program-detail&id=<?= e($id) ?>">Batal</a>
            <button class="btn green" type="submit">Simpan Perubahan</button>
        </div>
    </form>
</div>
