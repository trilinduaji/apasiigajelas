<?php
/**
 * Pengaturan Sistem - Admin View
 * Menampilkan dan mengatur settings dari database
 */
$settings = SettingsModel::toArray();
$appName = $settings['app_name'] ?? 'SIPEDO';
$appSubtitle = $settings['app_subtitle'] ?? 'Sistem Pengelolaan Donasi';
$contactEmail = $settings['contact_email'] ?? 'admin@sipedo.org';
$verificationDeadline = $settings['verification_deadline'] ?? '24';
$defaultRole = $settings['default_role'] ?? 'donatur';
$maxUploadMb = $settings['max_upload_mb'] ?? '2';
?>
<div class="section-head">
    <h3 class="section-title">Pengaturan Sistem</h3>
</div>

<div class="grid two">
    <div class="card">
        <h3>Informasi Organisasi</h3>
        <form action="index.php?route=settings/update" method="post">
            <input type="hidden" name="section" value="org">
            <div class="field">
                <label>Nama Aplikasi</label>
                <input type="text" name="app_name" value="<?= e($appName) ?>">
            </div>
            <div class="field">
                <label>Subtitle Aplikasi</label>
                <input type="text" name="app_subtitle" value="<?= e($appSubtitle) ?>">
            </div>
            <div class="field">
                <label>Email Kontak</label>
                <input type="email" name="contact_email" value="<?= e($contactEmail) ?>">
            </div>
            <button class="btn" type="submit">Simpan Informasi</button>
        </form>
    </div>
    <div class="card">
        <h3>Kebijakan Sistem</h3>
        <form action="index.php?route=settings/update" method="post">
            <input type="hidden" name="section" value="policy">
            <div class="field">
                <label>Batas Waktu Verifikasi (Jam)</label>
                <input type="number" name="verification_deadline" value="<?= e($verificationDeadline) ?>" min="1" max="168">
            </div>
            <div class="field">
                <label>Role Default Registrasi</label>
                <select name="default_role">
                    <option value="donatur" <?= $defaultRole === 'donatur' ? 'selected' : '' ?>>Donatur</option>
                    <option value="staff" <?= $defaultRole === 'staff' ? 'selected' : '' ?>>Staff</option>
                </select>
            </div>
            <div class="field">
                <label>Ukuran Maks Upload (MB)</label>
                <input type="number" name="max_upload_mb" value="<?= e($maxUploadMb) ?>" min="1" max="10">
            </div>
            <button class="btn" type="submit">Simpan Kebijakan</button>
        </form>
    </div>
</div>

<div class="section-head" style="margin-top:24px;">
    <h3 class="section-title">Statistik Sistem</h3>
</div>
<div class="stats">
    <div class="card"><div class="label">Total Users</div><div class="value"><?= count(UserModel::all()) ?></div></div>
    <div class="card"><div class="label">Total Program</div><div class="value"><?= count(ProgramModel::all()) ?></div></div>
    <div class="card"><div class="label">Total Donasi</div><div class="value"><?= count(DonationModel::all()) ?></div></div>
    <div class="card"><div class="label">Total Log</div><div class="value"><?= ActivityLogModel::count() ?></div></div>
</div>
