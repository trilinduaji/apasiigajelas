<?php
/**
 * Detail Program - Donatur View
 * Menampilkan detail program dan form donasi
 */
$id = $_GET['id'] ?? '';
$program = ProgramModel::findByKode($id);

if (!$program || ($program['status'] ?? '') === 'deleted') {
    echo '<div class="flash flash-error">Program tidak ditemukan.</div>';
    echo '<a class="btn light" href="index.php?route=app&page=program-donatur">Kembali ke Daftar Program</a>';
    return;
}

// Ambil donasi verified untuk program ini
$allDonations = DonationModel::all();
$donations = array_values(array_filter(
    $allDonations,
    fn($d) => (int)$d['program_id'] === (int)$program['id'] && $d['status'] === 'verified'
));

$raised = (float) $program['collected'];
$target = (float) $program['target'];
$remaining = max(0, $target - $raised);

$role = current_role();
$backPage = match ($role) {
    'staff' => 'program-staff',
    'admin' => 'program-admin',
    default => 'program-donatur',
};
?>
<div style="display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:14px;flex-wrap:wrap;">
    <a class="btn light" href="index.php?route=app&page=<?= e($backPage) ?>">← Kembali ke Daftar Program</a>
    <?php if ($role === 'staff'): ?>
        <div style="display:flex;gap:8px;flex-wrap:wrap;">
            <a class="btn green" href="index.php?route=app&page=edit-program&id=<?= e($program['kode']) ?>">Edit Program</a>
            <?php if ($program['status'] === 'active'): ?>
                <form action="index.php?route=program/close" method="post" onsubmit="return confirm('Tutup program <?= e($program['name']) ?>?');">
                    <input type="hidden" name="id" value="<?= e($program['kode']) ?>">
                    <button class="btn amber" type="submit">Tutup Program</button>
                </form>
            <?php endif; ?>
        </div>
    <?php elseif ($role === 'admin'): ?>
        <div style="display:flex;gap:8px;flex-wrap:wrap;">
            <?php if ($program['status'] === 'active'): ?>
                <form action="index.php?route=program/close" method="post" onsubmit="return confirm('Tutup program <?= e($program['name']) ?>?');">
                    <input type="hidden" name="id" value="<?= e($program['kode']) ?>">
                    <button class="btn amber" type="submit">Tutup Program</button>
                </form>
            <?php endif; ?>
            <form action="index.php?route=program/delete" method="post" onsubmit="return confirm('Hapus program <?= e($program['name']) ?>?');">
                <input type="hidden" name="id" value="<?= e($program['kode']) ?>">
                <button class="btn red" type="submit">Hapus Program</button>
            </form>
        </div>
    <?php endif; ?>
</div>

<article class="program-detail">
    <div class="pd-banner" <?= empty($program['image']) ? 'style="background:' . e($program['gradient'] ?? 'linear-gradient(135deg,#0D1B3E,#2A4080)') . ';"' : '' ?>>
        <?php if (!empty($program['image'])): ?>
            <img src="<?= e(pub($program['image'])) ?>" alt="<?= e($program['name']) ?>">
        <?php endif; ?>
    </div>

    <div class="pd-body">
        <div class="pd-meta-row">
            <span class="pd-cat"><?= e($program['category']) ?></span>
            <?= badge($program['status']) ?>
        </div>
        <h1 class="pd-title"><?= e($program['name']) ?></h1>
        <p class="pd-deadline">Tenggat: <strong><?= e(formatTanggal($program['deadline'])) ?></strong></p>

        <h3 class="pd-section-title">Deskripsi Program</h3>
        <p class="pd-desc"><?= e($program['description'] ?: 'Program ' . strtolower($program['category']) . ' dengan deadline ' . formatTanggal($program['deadline']) . '. Dukungan Anda membantu program ini mencapai target donasi yang ditetapkan.') ?></p>

        <div class="pd-stats">
            <div>
                <div class="pd-stat-label">Terkumpul</div>
                <div class="pd-stat-value pd-emerald"><?= formatRupiahFull($raised) ?></div>
            </div>
            <div>
                <div class="pd-stat-label">Target</div>
                <div class="pd-stat-value"><?= formatRupiahFull($target) ?></div>
            </div>
            <div>
                <div class="pd-stat-label">Kekurangan</div>
                <div class="pd-stat-value"><?= formatRupiahFull($remaining) ?></div>
            </div>
            <div>
                <div class="pd-stat-label">Donatur</div>
                <div class="pd-stat-value"><?= count($donations) ?></div>
            </div>
        </div>

        <div class="pd-progress-row">
            <div class="progress" style="flex:1;height:10px;"><span style="width:<?= e(min(100, (float) $program['pct'])) ?>%"></span></div>
            <strong class="pd-emerald"><?= e($program['pct']) ?>%</strong>
        </div>
    </div>
</article>

<?php if ($program['status'] === 'active' && $role === 'donatur'): ?>
    <div class="panel" style="padding:20px;margin-top:18px;">
        <h3 class="section-title" style="margin-bottom:14px;">Donasi untuk Program Ini</h3>
        <form action="index.php?route=donation/donate" method="post" enctype="multipart/form-data">
            <input type="hidden" name="program_id" value="<?= e($program['kode']) ?>">
            <div class="grid two">
                <div class="field">
                    <label>Jumlah Donasi (Rp)</label>
                    <input type="number" name="amount" min="1000" placeholder="50000" required>
                </div>
                <div class="field">
                    <label>Metode Pembayaran</label>
                    <select name="method">
                        <option>BCA Transfer</option>
                        <option>Mandiri Transfer</option>
                        <option>BRI Transfer</option>
                        <option>BNI Transfer</option>
                        <option>QRIS</option>
                        <option>Cash</option>
                    </select>
                </div>
            </div>
            <div class="field">
                <label>Bukti Pembayaran</label>
                <input type="file" name="proof" accept="image/jpeg,image/png,image/webp,application/pdf">
                <small style="color:#6b7280;">JPG / PNG / WEBP / PDF — maks. 2 MB. Lampirkan screenshot bukti transfer.</small>
            </div>
            <button class="btn green full" type="submit">Donasi Sekarang</button>
        </form>
    </div>
<?php elseif ($role === 'donatur'): ?>
    <div class="panel" style="padding:20px;margin-top:18px;text-align:center;color:#6b7280;">
        Program ini sudah <?= e($program['status'] === 'closed' ? 'selesai' : 'tidak aktif') ?> dan tidak menerima donasi baru.
    </div>
<?php endif; ?>
