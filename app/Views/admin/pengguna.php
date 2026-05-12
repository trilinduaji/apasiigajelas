<?php
$qUser = trim($_GET['q_user'] ?? '');

$donors = [];
$idx = 1;
foreach ($_SESSION['users'] ?? [] as $email => $u) {
    if (($u['role'] ?? '') !== 'donatur') continue;

    $totalAmount = 0;
    $donationCount = 0;
    foreach ($_SESSION['donations'] ?? [] as $d) {
        if ($d['donor'] === $u['name']) {
            $totalAmount += (int) str_replace('.', '', $d['amount']);
            $donationCount++;
        }
    }

    $donors[] = [
        'id' => 'USR-' . str_pad((string) $idx, 2, '0', STR_PAD_LEFT),
        'name' => $u['name'],
        'email' => $email,
        'total' => $totalAmount,
        'count' => $donationCount,
        'status' => $donationCount > 0 ? 'active' : 'inactive',
    ];
    $idx++;
}

if ($qUser !== '') {
    $needle = strtolower($qUser);
    $donors = array_values(array_filter($donors, function ($d) use ($needle) {
        return stripos($d['name'], $needle) !== false
            || stripos($d['email'], $needle) !== false
            || stripos($d['id'], $needle) !== false;
    }));
}
?>
<div class="section-head">
    <h3 class="section-title">Manajemen Staff</h3>
</div>
<div class="card">
    <form action="index.php?route=staff/add" method="post" class="grid two">
        <input type="hidden" name="action" value="add">
        <div class="field">
            <label>Nama Staff</label>
            <input type="text" name="name" placeholder="Nama staff" required>
        </div>
        <div class="field">
            <label>Email Staff</label>
            <input type="email" name="email" placeholder="staff@sipedo.org" required>
        </div>
        <button class="btn" type="submit">Tambah Staff</button>
    </form>
</div>

<div class="section-head">
    <h3 class="section-title">Daftar Staff</h3>
</div>
<div class="panel table-wrap">
    <table>
        <thead><tr><th>ID</th><th>Nama</th><th>Email</th><th>Role</th><th>Status</th><th style="text-align:right;">Aksi</th></tr></thead>
        <tbody>
            <?php if (empty($_SESSION['staffList'])): ?>
                <tr><td colspan="6" style="text-align:center;color:#6b7280;padding:18px;">Belum ada staff terdaftar.</td></tr>
            <?php endif; ?>
            <?php foreach ($_SESSION['staffList'] as $s): ?>
                <tr>
                    <td class="id">#<?= e($s['id']) ?></td>
                    <td><?= e($s['name']) ?></td>
                    <td class="muted"><?= e($s['email']) ?></td>
                    <td><?= e($s['role']) ?></td>
                    <td><?= badge($s['status']) ?></td>
                    <td class="actions" style="justify-content:flex-end;text-align:right;">
                        <?php if ($s['status'] === 'active'): ?>
                            <form action="index.php?route=staff/set-status" method="post">
                                <input type="hidden" name="action" value="deactivate">
                                <input type="hidden" name="id" value="<?= e($s['id']) ?>">
                                <button class="btn amber" type="submit">Nonaktifkan</button>
                            </form>
                        <?php else: ?>
                            <form action="index.php?route=staff/set-status" method="post">
                                <input type="hidden" name="action" value="activate">
                                <input type="hidden" name="id" value="<?= e($s['id']) ?>">
                                <button class="btn green" type="submit">Aktifkan</button>
                            </form>
                        <?php endif; ?>
                        <form action="index.php?route=staff/delete" method="post" onsubmit="return confirm('Hapus staff <?= e($s['name']) ?>?');">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= e($s['id']) ?>">
                            <button class="btn red" type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="section-head">
    <h3 class="section-title">Daftar User Donatur</h3>
    <form action="index.php?route=app" method="get" class="filter-bar">
        <input type="hidden" name="page" value="pengguna">
        <input type="text" name="q_user" placeholder="Cari nama / email / ID..." value="<?= e($qUser) ?>">
        <button class="btn" type="submit">Cari</button>
        <?php if ($qUser !== ''): ?>
            <a class="btn light" href="index.php?route=app&page=pengguna">Reset</a>
        <?php endif; ?>
    </form>
</div>
<div class="panel table-wrap">
    <table>
        <thead><tr><th>ID</th><th>Nama</th><th>Email</th><th>Role</th><th>Status</th></tr></thead>
        <tbody>
            <?php if (empty($donors)): ?>
                <tr><td colspan="5" style="text-align:center;color:#6b7280;padding:18px;">
                    <?= $qUser !== '' ? 'Tidak ada donatur yang cocok dengan pencarian.' : 'Belum ada user donatur terdaftar.' ?>
                </td></tr>
            <?php endif; ?>
            <?php foreach ($donors as $d): ?>
                <tr>
                    <td class="id">#<?= e($d['id']) ?></td>
                    <td><?= e($d['name']) ?></td>
                    <td class="muted"><?= e($d['email']) ?></td>
                    <td>Donatur<?= $d['count'] > 0 ? ' · ' . $d['count'] . ' donasi' : '' ?></td>
                    <td><?= badge($d['status']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
