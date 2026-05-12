<?php
/**
 * Log Aktivitas - Admin View
 * Menampilkan semua log aktivitas dari database
 */
$logs = ActivityLogModel::all();
?>
<div class="section-head">
    <h3 class="section-title">Log Aktivitas</h3>
</div>
<div class="panel table-wrap">
    <table>
        <thead><tr><th>#</th><th>Waktu</th><th>Pelaku</th><th>Role</th><th>Deskripsi</th><th>Referensi</th></tr></thead>
        <tbody>
            <?php if (empty($logs)): ?>
                <tr><td colspan="6" class="muted">Belum ada log aktivitas.</td></tr>
            <?php endif; ?>
            <?php foreach ($logs as $i => $log): ?>
                <tr>
                    <td class="id"><?= e($log['id']) ?></td>
                    <td class="muted"><?= e(timeAgo($log['created_at'])) ?></td>
                    <td><?= e($log['actor_name']) ?></td>
                    <td><?= e($log['role']) ?></td>
                    <td><?= e($log['description']) ?></td>
                    <td class="id"><?= e($log['ref']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
