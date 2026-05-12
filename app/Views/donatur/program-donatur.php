<?php
$allPrograms = array_values(array_filter($_SESSION['programs'] ?? [], fn($p) => ($p['status'] ?? '') !== 'deleted'));
$activePrograms = array_values(array_filter($allPrograms, fn($p) => ($p['status'] ?? '') === 'active'));

$q = trim($_GET['q'] ?? '');
$catFilt = $_GET['cat'] ?? '';
$sort = $_GET['sort'] ?? 'urgent';

$categories = array_values(array_unique(array_map(fn($p) => $p['cat'] ?? 'Lainnya', $allPrograms)));
sort($categories);

$filteredPrograms = array_values(array_filter($allPrograms, function ($p) use ($q, $catFilt) {
    $name = strtolower($p['name'] ?? '');
    $desc = strtolower($p['desc'] ?? '');
    $cat = $p['cat'] ?? '';
    if ($q !== '' && !str_contains($name . ' ' . $desc, strtolower($q))) return false;
    if ($catFilt !== '' && $cat !== $catFilt) return false;
    return true;
}));

usort($filteredPrograms, function ($a, $b) use ($sort) {
    if ($sort === 'progress') return ((float)($b['pct'] ?? 0)) <=> ((float)($a['pct'] ?? 0));
    if ($sort === 'target') return ((float)($b['target'] ?? 0)) <=> ((float)($a['target'] ?? 0));
    return ((float)($b['pct'] ?? 0)) <=> ((float)($a['pct'] ?? 0));
});

$featured = $filteredPrograms[0] ?? ($activePrograms[0] ?? ($allPrograms[0] ?? null));
$programCards = array_slice($filteredPrograms, 0, 3);
$urgentPrograms = array_values(array_filter($filteredPrograms, fn($p) => ($p['status'] ?? '') === 'active' && ((float)($p['pct'] ?? 0) >= 80 || stripos($p['cat'] ?? '', 'darurat') !== false || stripos($p['cat'] ?? '', 'bencana') !== false)));
if (empty($urgentPrograms)) $urgentPrograms = array_slice($filteredPrograms, 0, 2);

$verifiedDonations = array_values(array_filter($_SESSION['donations'] ?? [], fn($d) => ($d['status'] ?? '') === 'verified'));
$totalCollected = array_sum(array_map(fn($p) => (float)($p['collected'] ?? 0), $activePrograms));
$totalTarget = array_sum(array_map(fn($p) => (float)($p['target'] ?? 0), $activePrograms));
$avgProgress = $totalTarget > 0 ? round(($totalCollected / $totalTarget) * 100, 1) : 0;
$rankTotal = 0;
$currentUser = current_user();
foreach ($_SESSION['donations'] ?? [] as $donation) {
    if (($donation['donor'] ?? '') === ($currentUser['name'] ?? '') && ($donation['status'] ?? '') === 'verified') {
        $rankTotal += (int) str_replace('.', '', $donation['amount'] ?? '0');
    }
}

function program_amount_juta(array $p, string $key): string {
    $rp = ((float)($p[$key] ?? 0)) * 1000000;
    return 'Rp ' . number_format($rp, 0, ',', '.');
}

function program_desc_short(array $p, int $limit = 86): string {
    $text = trim($p['desc'] ?? '');
    if ($text === '') $text = 'Program bantuan SIPEDO yang membutuhkan dukungan donatur.';
    return strlen($text) > $limit ? substr($text, 0, $limit - 3) . '...' : $text;
}

function program_img_style(array $p): string {
    if (!empty($p['image'])) {
        return "background-image: linear-gradient(90deg, rgba(15,31,61,.76), rgba(15,31,61,.16)), url('" . e(pub($p['image'])) . "');";
    }
    return "background:" . e($p['gradient'] ?? 'linear-gradient(135deg,#0f1f3d,#1e3a5f)') . ";";
}

function program_category_icon(string $cat): string {
    $icons = [
        'Pendidikan' => '📚',
        'Kesehatan' => '🏥',
        'Sosial' => '🤝',
        'Kedaruratan' => '🏘️',
        'Bencana Alam' => '🏘️',
        'Lingkungan' => '🌱',
        'Pangan' => '🍚',
        'Infrastruktur' => '🏗️',
    ];
    return $icons[$cat] ?? '💚';
}
?>

<div class="explore-page" data-explore-page>
    <section class="xp-hero">
        <div class="xp-hero-copy">
            <span class="xp-eyebrow">Jelajahi Program</span>
            <h1>Temukan Perubahan yang Ingin Anda Buat</h1>
            <p>Pilih program bantuan yang paling dekat dengan hati Anda. Semua program tercatat dalam sistem SIPEDO dan dapat dipantau progresnya.</p>
        </div>

        <form class="xp-search-card" action="index.php" method="get">
            <input type="hidden" name="route" value="app">
            <input type="hidden" name="page" value="program-donatur">
            <div class="xp-search-input">
                <span>⌕</span>
                <input type="text" name="q" value="<?= e($q) ?>" placeholder="Cari program kemanusiaan...">
            </div>
            <select name="cat" aria-label="Kategori">
                <option value="">Kategori</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= e($cat) ?>" <?= $catFilt === $cat ? 'selected' : '' ?>><?= e($cat) ?></option>
                <?php endforeach; ?>
            </select>
            <select name="sort" aria-label="Urutkan">
                <option value="urgent" <?= $sort === 'urgent' ? 'selected' : '' ?>>Urutkan</option>
                <option value="progress" <?= $sort === 'progress' ? 'selected' : '' ?>>Progress</option>
                <option value="target" <?= $sort === 'target' ? 'selected' : '' ?>>Target</option>
            </select>
            <button type="submit">Cari</button>
        </form>

        <div class="xp-category-row" data-category-tabs>
            <a class="<?= $catFilt === '' ? 'active' : '' ?>" href="index.php?route=app&page=program-donatur">Semua</a>
            <?php foreach (array_slice($categories, 0, 6) as $cat): ?>
                <a class="<?= $catFilt === $cat ? 'active' : '' ?>" href="index.php?route=app&page=program-donatur&cat=<?= urlencode($cat) ?>"><?= e($cat) ?></a>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="xp-stats-grid" aria-label="Ringkasan program">
        <article class="xp-stat-card"><strong><?= number_format(count($activePrograms), 0, ',', '.') ?></strong><span>Program Aktif</span></article>
        <article class="xp-stat-card"><strong><?= formatJuta($totalCollected) ?></strong><span>Dana Terkumpul</span></article>
        <article class="xp-stat-card"><strong><?= count($verifiedDonations) ?></strong><span>Donasi Terverifikasi</span></article>
        <article class="xp-stat-card"><strong><?= e($avgProgress) ?>%</strong><span>Rata-rata Progress</span></article>
    </section>

    <?php if (!$featured): ?>
        <div class="xp-empty">Belum ada program bantuan yang tersedia saat ini.</div>
    <?php else: ?>
        <section class="xp-feature-section">
            <div class="xp-section-heading">
                <div>
                    <h2>Mendesak: Prioritas Utama</h2>
                    <p>Program dengan kebutuhan bantuan paling dekat.</p>
                </div>
                <a href="index.php?route=app&page=program-donatur">Lihat Semua →</a>
            </div>

            <article class="xp-feature-card" style="<?= program_img_style($featured) ?>">
                <div class="xp-feature-overlay">
                    <div class="xp-feature-badges">
                        <span><?= e($featured['cat'] ?? 'Program') ?></span>
                        <?php if (($featured['status'] ?? '') === 'active'): ?><small>Berjalan</small><?php endif; ?>
                    </div>
                    <h2><?= e($featured['name']) ?></h2>
                    <p><?= e(program_desc_short($featured, 150)) ?></p>
                    <div class="xp-feature-actions">
                        <a class="xp-primary-btn" href="index.php?route=app&page=program-detail&id=<?= e($featured['id']) ?>">Donasi Sekarang</a>
                        <div class="xp-feature-progress">
                            <strong><?= e(min(100, (float)($featured['pct'] ?? 0))) ?>% Tercapai</strong>
                            <span><i style="width:<?= e(min(100, (float)($featured['pct'] ?? 0))) ?>%"></i></span>
                        </div>
                        <em><?= e(program_amount_juta($featured, 'collected')) ?> / <?= e(program_amount_juta($featured, 'target')) ?></em>
                    </div>
                </div>
                <?php if (empty($featured['image'])): ?><div class="xp-feature-icon"><?= e(program_category_icon($featured['cat'] ?? '')) ?></div><?php endif; ?>
            </article>
        </section>

        <section class="xp-programs-section">
            <div class="xp-section-heading">
                <div>
                    <h2>Program Unggulan</h2>
                    <p>Pilih misi yang sesuai dengan nilai Anda.</p>
                </div>
                <a href="index.php?route=app&page=program-donatur">Semua Program →</a>
            </div>

            <?php if (empty($programCards)): ?>
                <div class="xp-empty">Tidak ada program yang cocok dengan pencarian.</div>
            <?php else: ?>
                <div class="xp-program-grid">
                    <?php foreach ($programCards as $p): ?>
                        <article class="xp-program-card" data-program-card data-name="<?= e(strtolower(($p['name'] ?? '') . ' ' . ($p['desc'] ?? '') . ' ' . ($p['cat'] ?? ''))) ?>" data-cat="<?= e($p['cat'] ?? '') ?>">
                            <a href="index.php?route=app&page=program-detail&id=<?= e($p['id']) ?>" class="xp-card-media" style="<?= program_img_style($p) ?>">
                                <span><?= e($p['cat'] ?? 'Program') ?></span>
                                <?php if (empty($p['image'])): ?><b><?= e(program_category_icon($p['cat'] ?? '')) ?></b><?php endif; ?>
                            </a>
                            <div class="xp-card-body">
                                <h3><?= e($p['name']) ?></h3>
                                <p><?= e(program_desc_short($p, 92)) ?></p>
                                <div class="xp-card-metrics">
                                    <div><small>Target</small><strong><?= e(formatJuta((float)($p['target'] ?? 0))) ?></strong></div>
                                    <div><small>Terkumpul</small><strong><?= e($p['pct'] ?? 0) ?>%</strong></div>
                                </div>
                                <div class="xp-card-progress"><span style="width:<?= e(min(100, (float)($p['pct'] ?? 0))) ?>%"></span></div>
                                <a class="xp-card-btn" href="index.php?route=app&page=program-detail&id=<?= e($p['id']) ?>"><?= ($p['status'] ?? '') === 'active' ? 'Donasi Sekarang' : 'Lihat Detail' ?></a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

        <section class="xp-bottom-grid">
            <div class="xp-impact-list">
                <div class="xp-section-heading compact">
                    <div>
                        <h2>Impact Stories</h2>
                        <p>Kabar terbaru dari program bantuan.</p>
                    </div>
                    <a href="index.php?route=app&page=riwayat-donasi">Lihat Selengkapnya →</a>
                </div>
                <?php foreach (array_slice($urgentPrograms, 0, 2) as $story): ?>
                    <article class="xp-story-card">
                        <div class="xp-story-thumb" style="<?= program_img_style($story) ?>"><?php if (empty($story['image'])): ?><span><?= e(program_category_icon($story['cat'] ?? '')) ?></span><?php endif; ?></div>
                        <div>
                            <small><?= e(strtoupper($story['cat'] ?? 'UPDATE')) ?> · Baru saja</small>
                            <h3><?= e($story['name']) ?></h3>
                            <p><?= e(program_desc_short($story, 128)) ?></p>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>

            <aside class="xp-donor-panel">
                <h2>Top Donors</h2>
                <?php
                    $ranked = [];
                    foreach ($_SESSION['donations'] ?? [] as $d) {
                        if (($d['status'] ?? '') !== 'verified') continue;
                        $donor = $d['donor'] ?? 'Anonymous';
                        if (!isset($ranked[$donor])) $ranked[$donor] = ['name' => $donor, 'init' => $d['init'] ?? 'DN', 'col' => $d['col'] ?? '#059669', 'total' => 0, 'count' => 0];
                        $ranked[$donor]['total'] += (int) str_replace('.', '', $d['amount'] ?? '0');
                        $ranked[$donor]['count']++;
                    }
                    usort($ranked, fn($a, $b) => $b['total'] <=> $a['total']);
                    $ranked = array_slice($ranked, 0, 3);
                ?>
                <?php if (empty($ranked)): ?>
                    <p class="xp-muted">Belum ada donatur terverifikasi.</p>
                <?php else: ?>
                    <?php foreach ($ranked as $i => $donor): ?>
                        <div class="xp-donor-row">
                            <strong><?= $i + 1 ?></strong>
                            <?= avatar($donor['init'], $donor['col']) ?>
                            <div><b><?= e($donor['name']) ?></b><small><?= e($donor['count']) ?> Donasi</small></div>
                            <em>Rp <?= number_format($donor['total'], 0, ',', '.') ?></em>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
                <a class="xp-outline-btn" href="index.php?route=app&page=riwayat-donasi">Lihat Riwayat Donasi</a>
            </aside>
        </section>
    <?php endif; ?>
</div>

<script>
(function () {
    const root = document.querySelector('[data-explore-page]');
    if (!root) return;

    const search = root.querySelector('.xp-search-input input');
    const cards = Array.from(root.querySelectorAll('[data-program-card]'));
    if (search && cards.length) {
        search.addEventListener('input', function () {
            const keyword = this.value.trim().toLowerCase();
            cards.forEach(function (card) {
                card.style.display = card.dataset.name.includes(keyword) ? '' : 'none';
            });
        });
    }
})();
</script>
