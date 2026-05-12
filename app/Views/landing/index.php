<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=1440" />
    <meta name="description" content="SIPEDO — Platform donasi digital terpercaya untuk program charity di seluruh Indonesia." />
    <title>SIPEDO — Beranda</title>

    <!-- Favicon -->
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'><rect width='32' height='32' rx='6' fill='%23001F3F'/><rect x='0' y='20' width='32' height='12' rx='0' fill='%2350C878'/></svg>" />

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;0,900;1,400;1,700&family=Poppins:wght@300;400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet" />

    <!-- Landing Page CSS -->
    <link rel="stylesheet" href="public/assets/css/landing.css" />
</head>
<body>

<!-- ============================================================
     NAVBAR
     ============================================================ -->
<nav class="navbar" id="navbar">
    <div class="container">
        <div class="navbar__inner">

            <a href="index.php" class="navbar__logo">
                <div class="navbar__logo-icon">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path d="M10 2C10 2 3 6 3 12C3 15.31 6.13 18 10 18C13.87 18 17 15.31 17 12C17 6 10 2 10 2Z" fill="white" opacity="0.9"/>
                    </svg>
                </div>
                <div class="navbar__logo-text">
                    <span class="navbar__logo-brand">SIPEDO</span>
                    <span class="navbar__logo-sub">Pengelolaan Donasi</span>
                </div>
            </a>

            <ul class="navbar__nav">
                <li><a href="#beranda"     class="navbar__nav-link active">Beranda</a></li>
                <li><a href="#program"     class="navbar__nav-link">Program</a></li>
                <li><a href="#leaderboard" class="navbar__nav-link">Leaderboard</a></li>
                <li><a href="index.php?route=auth/login" class="navbar__cta">Masuk / Daftar</a></li>
            </ul>

        </div>
    </div>
</nav>


<!-- ============================================================
     HERO SECTION
     ============================================================ -->
<section class="hero" id="beranda">
    <div class="hero__bg-decor" aria-hidden="true">
        <div class="hero__bg-lines"></div>
        <div class="hero__bg-circle-1"></div>
        <div class="hero__bg-circle-2"></div>
        <div class="hero__bg-accent-bar"></div>
    </div>

    <div class="container">
        <div class="hero__inner">

            <!-- Kiri: Headline & CTA -->
            <div class="hero__content">
                <div class="hero__badge">
                    <span class="hero__badge-dot"></span>
                    Platform Donasi Terpercaya
                </div>

                <h1 class="hero__title">
                    Satu Langkah Kecil<br />
                    untuk <em>Perubahan</em><br />
                    yang Besar
                </h1>

                <p class="hero__description">
                    SIPEDO menghubungkan kepedulian Anda dengan program charity yang
                    telah terverifikasi. Setiap donasi dicatat, transparan, dan berdampak
                    nyata bagi masyarakat yang membutuhkan.
                </p>

                <div class="hero__actions">
                    <a href="#program" class="btn-primary">
                        Mulai Berdonasi
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M3 8H13M9 4L13 8L9 12" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                    <a href="#leaderboard" class="btn-ghost">
                        <svg width="15" height="15" viewBox="0 0 15 15" fill="none">
                            <path d="M7.5 1L9.4 5.6L14.4 6.1L10.8 9.4L11.9 14.3L7.5 11.8L3.1 14.3L4.2 9.4L0.6 6.1L5.6 5.6L7.5 1Z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/>
                        </svg>
                        Lihat Leaderboard
                    </a>
                </div>
            </div>

            <!-- Kanan: Panel Statistik -->
            <div class="hero__stats">
                <p class="hero__stats-title">Statistik Donasi Terkini</p>

                <div class="hero__stat-item">
                    <span class="hero__stat-label">Total Donatur</span>
                    <div>
                        <span class="hero__stat-value accent"><?= number_format(max($donaturUnik, $donaturUnik), 0, ',', '.') ?></span>
                        <span class="hero__stat-sub">orang telah berdonasi</span>
                    </div>
                </div>

                <div class="hero__stat-item">
                    <span class="hero__stat-label">Program Aktif</span>
                    <div>
                        <span class="hero__stat-value"><?= $totalProgram ?></span>
                        <span class="hero__stat-sub">program berjalan</span>
                    </div>
                </div>

                <div class="hero__stat-item">
                    <span class="hero__stat-label">Donasi Terverifikasi</span>
                    <div>
                        <span class="hero__stat-value"><?= $verifiedCount ?></span>
                        <span class="hero__stat-sub">transaksi berhasil</span>
                    </div>
                </div>

                <div class="hero__total-raised">
                    <p class="hero__total-label">Total Dana Terkumpul</p>
                    <p class="hero__total-amount"><?= formatJuta($totalCollected) ?></p>
                </div>
            </div>

        </div>
    </div>
</section>


<!-- ============================================================
     TRUST STRIP
     ============================================================ -->
<section class="section-trust" aria-label="Statistik kepercayaan SIPEDO">
    <div class="container">
        <div class="trust__grid">

            <div class="trust__item">
                <p class="trust__number">98<span>%</span></p>
                <p class="trust__label">Dana Tersalurkan</p>
            </div>
            <div class="trust__divider" aria-hidden="true"></div>

            <div class="trust__item">
                <p class="trust__number">24<span>/7</span></p>
                <p class="trust__label">Pemantauan Real-Time</p>
            </div>
            <div class="trust__divider" aria-hidden="true"></div>

            <div class="trust__item">
                <p class="trust__number"><span>+</span><?= number_format(max($donaturUnik * 10, 4800), 0, ',', '.') ?></p>
                <p class="trust__label">Donatur Terdaftar</p>
            </div>
            <div class="trust__divider" aria-hidden="true"></div>

            <div class="trust__item">
                <p class="trust__number"><span>Rp</span> 0</p>
                <p class="trust__label">Biaya Admin</p>
            </div>

        </div>
    </div>
</section>


<!-- ============================================================
     SECTION: PROGRAM CHARITY
     ============================================================ -->
<section class="section-programs" id="program">
    <div class="container">

        <div class="section-header">
            <span class="section-tag">Program Aktif</span>
            <h2 class="section-title">Pilih Program yang <em>Menyentuh Hatimu</em></h2>
            <p class="section-desc">
                Setiap program telah melalui kurasi dan verifikasi ketat oleh tim SIPEDO.
                Donasi Anda akan langsung disalurkan kepada yang berhak.
            </p>
        </div>

        <div class="programs__grid">

            <?php if (empty($displayPrograms)): ?>
                <div class="programs__empty">
                    <p>Belum ada program aktif saat ini. Silakan periksa kembali nanti.</p>
                </div>
            <?php else: ?>

                <?php foreach ($displayPrograms as $program):
                    $pct     = (int) min($program['pct'], 100);
                    $is_urgent = ($pct >= 90 && $program['status'] === 'active');
                    $terkumpul_rp = (float)$program['collected'];
                    $target_rp    = (float)$program['target']    * 1_000_000;
                ?>

                <article class="program-card">

                    <!-- Gambar / Placeholder -->
                    <div class="program-card__image-wrap">
                        <?php if (!empty($program['image'])): ?>
                            <img
                                class="program-card__image"
                                src="<?= e($program['image']) ?>"
                                alt="<?= e($program['name']) ?>"
                                loading="lazy"
                            />
                        <?php else: ?>
                            <!-- Placeholder gradient dari data program -->
                            <div class="program-card__image-placeholder" style="background: <?= e($program['gradient'] ?? 'linear-gradient(135deg,#001F3F,#004080)') ?>;">
                                <svg width="56" height="56" viewBox="0 0 56 56" fill="none">
                                    <path d="M28 8C28 8 12 16 12 28C12 36.84 19.16 44 28 44C36.84 44 44 36.84 44 28C44 16 28 8 28 8Z" fill="white" opacity="0.15"/>
                                    <path d="M28 20C28 20 20 24 20 30C20 33.31 23.58 36 28 36C32.42 36 36 33.31 36 30C36 24 28 20 28 20Z" fill="white" opacity="0.25"/>
                                </svg>
                            </div>
                        <?php endif; ?>

                        <span class="program-card__category-badge">
                            <?= e($program['category'] ?? 'Program') ?>
                        </span>

                        <?php if ($is_urgent): ?>
                            <span class="program-card__urgency">🔴 Segera Terpenuhi</span>
                        <?php endif; ?>
                    </div>

                    <div class="program-card__body">
                        <h3 class="program-card__title"><?= e($program['name']) ?></h3>
                        <p class="program-card__desc">
                            <?= e(substr($program['desc'] ?? '', 0, 115)) ?>...
                        </p>

                        <!-- Progress Bar -->
                        <div class="program-card__progress">
                            <div class="program-card__progress-info">
                                <span class="program-card__raised">
                                    <?= formatJuta((float)$program['collected']) ?>
                                </span>
                                <span class="program-card__percent"><?= $pct ?>%</span>
                            </div>

                            <div class="progress-bar" role="progressbar"
                                 aria-valuenow="<?= $pct ?>"
                                 aria-valuemin="0"
                                 aria-valuemax="100">
                                <div class="progress-bar__fill" style="width: <?= $pct ?>%;"></div>
                            </div>

                            <div class="program-card__meta">
                                <span class="program-card__target">
                                    Target: <?= formatJuta((float)$program['target']) ?>
                                </span>
                                <span class="program-card__donors">
                                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                                        <circle cx="4.5" cy="3.5" r="2" stroke="currentColor" stroke-width="1.2"/>
                                        <path d="M1 10C1 7.79 2.57 6 4.5 6" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/>
                                        <circle cx="8" cy="4" r="2.2" stroke="currentColor" stroke-width="1.2"/>
                                        <path d="M5 10.5C5 8.01 6.34 6 8 6C9.66 6 11 8.01 11 10.5" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/>
                                    </svg>
                                    <?= number_format((int)($terkumpul_rp / 50000), 0, ',', '.') ?> donatur
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="program-card__footer">
                        <a href="index.php?route=auth/login" class="btn-donate">Donasi Sekarang</a>
                        <span class="program-card__deadline">
                            <svg width="11" height="11" viewBox="0 0 11 11" fill="none">
                                <circle cx="5.5" cy="5.5" r="4.5" stroke="currentColor" stroke-width="1.2"/>
                                <path d="M5.5 3V5.5L7.5 7" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/>
                            </svg>
                            <?= e($program['deadline']) ?>
                        </span>
                    </div>

                </article>

                <?php endforeach; ?>
            <?php endif; ?>

        </div><!-- /.programs__grid -->

        <div class="programs__cta">
            <a href="index.php?route=auth/login" class="btn-outline">
                Login untuk Lihat Semua Program
                <svg width="15" height="15" viewBox="0 0 15 15" fill="none">
                    <path d="M2.5 7.5H12.5M9 3.5L13 7.5L9 11.5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
        </div>

    </div>
</section>


<!-- ============================================================
     SECTION: LEADERBOARD — TOP 5 DONATUR
     ============================================================ -->
<section class="section-leaderboard" id="leaderboard">
    <div class="container">

        <div class="section-header">
            <span class="section-tag">Hall of Generosity</span>
            <h2 class="section-title">Donatur <em>Terbaik</em> SIPEDO</h2>
            <p class="section-desc">
                Penghargaan tertinggi untuk para pahlawan kemanusiaan yang telah
                memberikan yang terbaik bagi sesama.
            </p>
        </div>

        <div class="leaderboard__wrapper">

            <!-- PODIUM: Top 3 -->
            <?php if (!empty($topDonors)): ?>
            <div class="leaderboard__podium">
                <?php
                $podium_classes = ['podium-card--1st', 'podium-card--2nd', 'podium-card--3rd'];
                $rank_labels    = ['★', '#2', '#3'];
                $rank_css       = ['podium-rank--1', 'podium-rank--2', 'podium-rank--3'];

                for ($i = 0; $i < 3 && $i < count($topDonors); $i++):
                    $d = $topDonors[$i];
                ?>
                <div class="podium-card <?= $podium_classes[$i] ?>">
                    <div class="podium-rank <?= $rank_css[$i] ?>"><?= $rank_labels[$i] ?></div>

                    <?php if ($i === 0): ?>
                        <div class="podium-badge-crown">👑</div>
                    <?php endif; ?>

                    <div class="podium-avatar" style="background:<?= e($d['color']) ?>;">
                        <?= e($d['initials']) ?>
                    </div>

                    <p class="podium-name"><?= e($d['nama']) ?></p>
                    <p class="podium-amount"><?= formatRupiahLP($d['total']) ?></p>
                </div>
                <?php endfor; ?>
            </div>
            <?php endif; ?>

            <!-- TABLE: Rank 4 & 5 -->
            <?php if (count($topDonors) > 3): ?>
            <div class="leaderboard__table">
                <div class="leaderboard__table-header">
                    <span>Rank</span>
                    <span>Donatur</span>
                    <span>Program Favorit</span>
                    <span style="text-align:right;">Total Donasi</span>
                </div>

                <?php for ($i = 3; $i < 5 && $i < count($topDonors); $i++):
                    $d = $topDonors[$i];
                ?>
                <div class="leaderboard__row">
                    <div class="leaderboard__rank">#<?= $i + 1 ?></div>

                    <div class="leaderboard__donatur">
                        <div class="leaderboard__avatar" style="background:<?= e($d['color']) ?>;">
                            <?= e($d['initials']) ?>
                        </div>
                        <div>
                            <span class="leaderboard__donatur-name"><?= e($d['nama']) ?></span>
                            <span class="leaderboard__donatur-since"><?= $d['count'] ?> transaksi donasi</span>
                        </div>
                    </div>

                    <div class="leaderboard__program">
                        <?= e(substr($d['program'] ?? '—', 0, 35)) ?>
                    </div>

                    <div class="leaderboard__amount">
                        <?= formatRupiahFull($d['total']) ?>
                    </div>
                </div>
                <?php endfor; ?>
            </div>
            <?php endif; ?>

        </div><!-- /.leaderboard__wrapper -->
    </div>
</section>


<!-- ============================================================
     FOOTER
     ============================================================ -->
<footer class="footer" id="kontak">
    <div class="container">
        <div class="footer__grid">

            <!-- Brand -->
            <div class="footer__brand">
                <div class="footer__logo">
                    <div class="footer__logo-box">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path d="M10 2C10 2 3 6 3 12C3 15.31 6.13 18 10 18C13.87 18 17 15.31 17 12C17 6 10 2 10 2Z" fill="#001F3F"/>
                        </svg>
                    </div>
                    <span class="footer__brand-name">SIPEDO</span>
                </div>
                <p class="footer__tagline">
                    Platform donasi digital yang transparan dan terpercaya.
                    Setiap rupiah yang Anda berikan tercatat dan berdampak nyata.
                </p>
                <div class="footer__social">
                    <a href="#" class="footer__social-btn" aria-label="Instagram">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.7)" stroke-width="1.8">
                            <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
                            <circle cx="12" cy="12" r="4"/>
                            <circle cx="17.5" cy="6.5" r="1" fill="rgba(255,255,255,0.7)" stroke="none"/>
                        </svg>
                    </a>
                    <a href="#" class="footer__social-btn" aria-label="Twitter">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="rgba(255,255,255,0.7)">
                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                        </svg>
                    </a>
                    <a href="#" class="footer__social-btn" aria-label="Facebook">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="rgba(255,255,255,0.7)">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Navigasi -->
            <div>
                <h4 class="footer__col-title">Navigasi</h4>
                <ul class="footer__links">
                    <li><a href="#beranda"     class="footer__link">Beranda</a></li>
                    <li><a href="#program"     class="footer__link">Program Charity</a></li>
                    <li><a href="#leaderboard" class="footer__link">Leaderboard</a></li>
                    <li><a href="index.php?route=auth/login" class="footer__link">Masuk / Daftar</a></li>
                </ul>
            </div>

            <!-- Dukungan -->
            <div>
                <h4 class="footer__col-title">Dukungan</h4>
                <ul class="footer__links">
                    <li><a href="#" class="footer__link">FAQ</a></li>
                    <li><a href="#" class="footer__link">Cara Berdonasi</a></li>
                    <li><a href="#" class="footer__link">Laporan Keuangan</a></li>
                    <li><a href="#" class="footer__link">Verifikasi Program</a></li>
                </ul>
            </div>

            <!-- Kontak -->
            <div>
                <h4 class="footer__col-title">Kontak</h4>

                <div class="footer__contact-item">
                    <svg class="footer__contact-icon" viewBox="0 0 18 18" fill="none" stroke="currentColor" stroke-width="1.4">
                        <path d="M9 1C6.24 1 4 3.24 4 6c0 4.25 5 11 5 11s5-6.75 5-11c0-2.76-2.24-5-5-5z"/>
                        <circle cx="9" cy="6" r="2"/>
                    </svg>
                    <span class="footer__contact-text">Jl. Sudirman No. 88, Jakarta Pusat<br />DKI Jakarta 10220</span>
                </div>

                <div class="footer__contact-item">
                    <svg class="footer__contact-icon" viewBox="0 0 18 18" fill="none" stroke="currentColor" stroke-width="1.4">
                        <path d="M3 4h12c.55 0 1 .45 1 1v8c0 .55-.45 1-1 1H3c-.55 0-1-.45-1-1V5c0-.55.45-1 1-1z"/>
                        <polyline points="2,4 9,10 16,4"/>
                    </svg>
                    <span class="footer__contact-text">halo@sipedo.id</span>
                </div>

                <div class="footer__contact-item">
                    <svg class="footer__contact-icon" viewBox="0 0 18 18" fill="none" stroke="currentColor" stroke-width="1.4">
                        <path d="M3.3 3h2.7l1.3 3.3-1.8 1.2c.8 1.6 2.2 3 3.8 3.8l1.2-1.8L14 10.7V14c0 .7-.6 1-1.3 1C5.5 14.9 3 7.9 3 4.3 3 3.6 3.3 3 3.3 3z"/>
                    </svg>
                    <span class="footer__contact-text">+62 21 5555 0123</span>
                </div>
            </div>

        </div>

        <div class="footer__bottom">
            <p class="footer__copyright">
                &copy; <?= date('Y') ?> <strong>SIPEDO</strong>.
                Sistem Informasi Pengelolaan Donasi. Hak Cipta Dilindungi Undang-Undang.
            </p>
            <nav class="footer__legal">
                <a href="#">Kebijakan Privasi</a>
                <a href="#">Syarat &amp; Ketentuan</a>
            </nav>
        </div>

    </div>
</footer>


<!-- Scroll to Top -->
<button class="scroll-top" id="scrollTopBtn" aria-label="Kembali ke atas"
        onclick="window.scrollTo({top:0,behavior:'smooth'})">
    <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
        <path d="M9 14V4M4 9L9 4L14 9" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
</button>


<!-- ============================================================
     JAVASCRIPT
     ============================================================ -->
<script>
(function () {
    'use strict';

    var navbar    = document.getElementById('navbar');
    var scrollBtn = document.getElementById('scrollTopBtn');

    window.addEventListener('scroll', function () {
        var y = window.scrollY || window.pageYOffset;
        navbar.classList.toggle('scrolled', y > 20);
        scrollBtn.classList.toggle('visible', y > 400);
    });

    // Active nav link via IntersectionObserver
    var sections = document.querySelectorAll('section[id]');
    var navLinks = document.querySelectorAll('.navbar__nav-link');
    var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                var id = entry.target.getAttribute('id');
                navLinks.forEach(function (link) {
                    link.classList.toggle('active', link.getAttribute('href') === '#' + id);
                });
            }
        });
    }, { rootMargin: '-30% 0px -60% 0px' });
    sections.forEach(function (s) { observer.observe(s); });

    // Animate progress bars on load
    var bars = document.querySelectorAll('.progress-bar__fill');
    bars.forEach(function (bar) {
        var w = bar.style.width;
        bar.style.width = '0%';
        setTimeout(function () { bar.style.width = w; }, 300);
    });

})();
</script>

</body>
</html>

