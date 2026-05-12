<?php
/**
 * Dashboard Donatur - Donatur View
 * Menampilkan ringkasan donasi dan program aktif dari database
 */
$user = current_user();
$programs = ProgramModel::active();
$myDonations = DonationModel::byUserId((int)$user['id']);

// Hitung statistik donasi saya
$totalDonation = 0;
$supportedPrograms = [];
foreach ($myDonations as $donation) {
    if ($donation['status'] === 'verified') {
        $totalDonation += (float) $donation['amount'];
    }
    $supportedPrograms[$donation['program_id']] = true;
}

// Top donatur
$topDonors = DonationModel::topDonors(10);

// Hitung ranking saya
$myRank = '-';
foreach ($topDonors as $idx => $donor) {
    if ($donor['nama'] === $user['name']) {
        $myRank = '#' . ($idx + 1);
        break;
    }
}

// Statistik global
$totalCollected = ProgramModel::totalCollected();
$totalDonors = DonationModel::uniqueDonors();
$categories = array_values(array_unique(array_filter(array_map(fn($p) => $p['category'], $programs))));

// Helper functions
function program_icon_dashboard($category) {
    $icons = [
        'Pendidikan' => '📚',
        'Kesehatan' => '🏥',
        'Sosial' => '🤝',
        'Kedaruratan' => '🏘️',
        'Bencana Alam' => '🏘️',
        'Lingkungan' => '🌱',
        'Pangan' => '🍚',
        'Keagamaan' => '🕌',
        'Infrastruktur' => '🏗️',
    ];
    return $icons[$category] ?? '💚';
}

function program_gradient_dashboard($idx) {
    $gradients = [
        'linear-gradient(135deg, #1A3A5C 0%, #2A5A8C 100%)',
        'linear-gradient(135deg, #0F4C3A 0%, #1A7A5E 100%)',
        'linear-gradient(135deg, #7C1F1F 0%, #C0392B 100%)',
        'linear-gradient(135deg, #4A1D5C 0%, #7B3FAD 100%)',
        'linear-gradient(135deg, #1A4A2E 0%, #2D8C52 100%)',
    ];
    return $gradients[$idx % count($gradients)];
}
?>

<div class="donor-landing" id="donorLanding">
    <section class="dl-hero">
        <div class="dl-hero-content">
            <p class="dl-eyebrow">Dashboard Donatur</p>
            <h1>Pilih Program,<br>Ubah <em>Kehidupan</em></h1>
            <p class="dl-hero-copy">Setiap donasi Anda berdampak nyata. Temukan program bantuan yang sesuai dengan hati nurani Anda dan pantau kontribusi kebaikan Anda di SIPEDO.</p>
            <div class="dl-search-row">
                <input type="search" id="programSearch" class="dl-search" placeholder="Cari program bantuan..." autocomplete="off">
                <button type="button" class="dl-search-btn" id="resetProgramFilter">Reset</button>
            </div>
        </div>
        <div class="dl-hero-stats">
            <div class="dl-hero-stat">
                <strong><?= count($programs) ?></strong>
                <span>Program Aktif</span>
            </div>
            <div class="dl-hero-stat">
                <strong><?= e($totalDonors) ?></strong>
                <span>Donatur</span>
            </div>
            <div class="dl-hero-stat">
                <strong><?= formatRupiah($totalCollected) ?></strong>
                <span>Dana Terkumpul</span>
            </div>
        </div>
    </section>

    <div class="dl-main-grid">
        <div class="dl-programs-column">
            <div class="dl-section-head">
                <div>
                    <h2>Program Bantuan</h2>
                    <p>Menampilkan program aktif yang bisa Anda dukung hari ini.</p>
                </div>
                <a href="index.php?route=app&page=program-donatur">Lihat Semua →</a>
            </div>

            <div class="dl-filter-tabs" id="categoryFilters">
                <button type="button" class="dl-filter active" data-category="all">Semua</button>
                <?php foreach ($categories as $category): ?>
                    <button type="button" class="dl-filter" data-category="<?= e($category) ?>"><?= e($category) ?></button>
                <?php endforeach; ?>
            </div>

            <div class="dl-program-grid" id="programGrid">
                <?php if (empty($programs)): ?>
                    <p class="muted">Belum ada program aktif saat ini.</p>
                <?php endif; ?>
                <?php foreach (array_slice($programs, 0, 6) as $idx => $p): ?>
                    <?php
                        $raised = (float) $p['collected'];
                        $target = (float) $p['target'];
                        $isUrgent = $p['pct'] >= 90 || stripos($p['category'], 'darurat') !== false || stripos($p['category'], 'bencana') !== false;
                    ?>
                    <a class="dl-program-card" href="index.php?route=app&page=program-detail&id=<?= e($p['kode']) ?>" data-title="<?= e(strtolower($p['name'])) ?>" data-category="<?= e($p['category']) ?>">
                        <div class="dl-card-image" style="background: <?= !empty($p['image']) ? 'url(\'' . e(pub($p['image'])) . '\') center/cover' : e(program_gradient_dashboard($idx)) ?>;">
                            <?php if (empty($p['image'])): ?>
                                <span class="dl-card-emoji"><?= e(program_icon_dashboard($p['category'])) ?></span>
                            <?php endif; ?>
                            <span class="dl-card-category"><?= e($p['category']) ?></span>
                            <?php if ($isUrgent): ?><span class="dl-card-urgent">Mendesak</span><?php endif; ?>
                        </div>
                        <div class="dl-card-body">
                            <h3><?= e($p['name']) ?></h3>
                            <p class="dl-card-desc"><?= e($p['description'] ?: 'Program ' . strtolower($p['category']) . ' dengan deadline ' . formatTanggal($p['deadline']) . '. Bantu program ini mencapai target donasi.') ?></p>
                            <div class="dl-progress-meta">
                                <strong><?= formatRupiahFull($raised) ?></strong>
                                <span><?= e($p['pct']) ?>%</span>
                            </div>
                            <div class="dl-progress-track"><span style="width: <?= e(min(100, $p['pct'])) ?>%"></span></div>
                            <p class="dl-progress-goal">Target: <?= formatRupiahFull($target) ?> · Deadline <?= e(formatTanggal($p['deadline'])) ?></p>
                            <span class="dl-detail-cta">Lihat Detail &amp; Donasi →</span>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
            <p class="dl-empty-state" id="emptyProgramState" style="display:none;">Program tidak ditemukan. Coba kata kunci atau kategori lain.</p>
        </div>

        <aside class="dl-side-column">
            <section class="dl-widget">
                <div class="dl-widget-head">
                    <span>〽</span>
                    <div>
                        <h3>Donasi Saya</h3>
                        <p>Ringkasan aktivitas</p>
                    </div>
                </div>
                <div class="dl-quick-stats">
                    <div><strong><?= formatRupiah($totalDonation) ?></strong><span>Total Donasi</span></div>
                    <div><strong><?= count($myDonations) ?></strong><span>Kali Transaksi</span></div>
                    <div><strong><?= count($supportedPrograms) ?></strong><span>Program Didukung</span></div>
                    <div><strong><?= e($myRank) ?></strong><span>Peringkat Donatur</span></div>
                </div>
            </section>

            <section class="dl-widget">
                <div class="dl-widget-head">
                    <span>🏆</span>
                    <div>
                        <h3>Ranking Charity</h3>
                        <p>Top donatur saat ini</p>
                    </div>
                </div>
                <div class="dl-leaderboard">
                    <?php if (empty($topDonors)): ?>
                        <p class="muted">Belum ada data donatur.</p>
                    <?php endif; ?>
                    <?php foreach (array_slice($topDonors, 0, 5) as $idx => $donor): ?>
                        <div class="dl-rank-item <?= $donor['nama'] === $user['name'] ? 'is-current' : '' ?>">
                            <b><?= $idx === 0 ? '1' : ($idx === 1 ? '2' : ($idx === 2 ? '3' : $idx + 1)) ?></b>
                            <?= avatar($donor['initials'] ?? '?', $donor['color'] ?? '#059669') ?>
                            <div>
                                <strong><?= e($donor['nama']) ?><?= $donor['nama'] === $user['name'] ? ' (Anda)' : '' ?></strong>
                                <span><?= formatRupiahFull((float)$donor['total']) ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <section class="dl-cta-widget">
                <h3>Tingkatkan Dampak Anda</h3>
                <p>Mulai dari nominal kecil, kontribusi rutin Anda bisa membantu lebih banyak penerima manfaat.</p>
                <a href="index.php?route=app&page=program-donatur">Jelajahi Semua Program</a>
            </section>
        </aside>
    </div>
</div>

<script>
(function () {
    const searchInput = document.getElementById('programSearch');
    const resetButton = document.getElementById('resetProgramFilter');
    const filters = document.querySelectorAll('.dl-filter');
    const cards = document.querySelectorAll('.dl-program-card');
    const emptyState = document.getElementById('emptyProgramState');
    let activeCategory = 'all';

    function applyFilter() {
        const keyword = (searchInput.value || '').trim().toLowerCase();
        let visible = 0;

        cards.forEach(function (card) {
            const title = card.dataset.title || '';
            const category = card.dataset.category || '';
            const matchKeyword = !keyword || title.includes(keyword) || category.toLowerCase().includes(keyword);
            const matchCategory = activeCategory === 'all' || category === activeCategory;
            const shouldShow = matchKeyword && matchCategory;

            card.style.display = shouldShow ? '' : 'none';
            if (shouldShow) visible += 1;
        });

        emptyState.style.display = visible ? 'none' : 'block';
    }

    filters.forEach(function (button) {
        button.addEventListener('click', function () {
            filters.forEach(function (item) { item.classList.remove('active'); });
            button.classList.add('active');
            activeCategory = button.dataset.category;
            applyFilter();
        });
    });

    searchInput.addEventListener('input', applyFilter);
    resetButton.addEventListener('click', function () {
        searchInput.value = '';
        activeCategory = 'all';
        filters.forEach(function (item) { item.classList.toggle('active', item.dataset.category === 'all'); });
        applyFilter();
        searchInput.focus();
    });
})();
</script>
