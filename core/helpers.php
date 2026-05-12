<?php
/**
 * SIPEDO - Global Helper Functions (versi database)
 * Disesuaikan dengan skema query.sql
 */

function e($value): string {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function redirect_to(string $url): never {
    header('Location: ' . $url);
    exit;
}

function current_user(): ?array {
    return $_SESSION['currentUser'] ?? null;
}

function current_role(): ?string {
    return $_SESSION['currentRole'] ?? null;
}

function require_login(): void {
    if (!current_user()) {
        redirect_to(base_url('auth/login'));
    }
}

function flash(string $message, string $type = 'success'): void {
    $_SESSION['flash'] = ['message' => $message, 'type' => $type];
}

function show_flash(): void {
    if (!isset($_SESSION['flash'])) return;
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    echo '<div class="flash flash-' . e($flash['type']) . '">' . e($flash['message']) . '</div>';
}

/**
 * Catat log aktivitas ke database
 * Sesuai dengan tabel activity_logs: user_id, actor_name, role, description, ref
 */
function add_log(string $desc, string $ref = ''): void {
    $user = current_user();
    $role = ucfirst(current_role() ?? 'System');
    $actorName = $user['name'] ?? 'System';
    
    DB::run(
        'INSERT INTO activity_logs (user_id, actor_name, role, description, ref)
         VALUES (?, ?, ?, ?, ?)',
        [
            $user['id'] ?? null,
            $actorName,
            $role,
            $desc,
            $ref,
        ]
    );
}

function badge(string $status): string {
    $labels = [
        'pending'  => 'Pending',
        'verified' => 'Terverifikasi',
        'rejected' => 'Ditolak',
        'active'   => 'Aktif',
        'inactive' => 'Nonaktif',
        'closed'   => 'Selesai',
        'deleted'  => 'Dihapus',
    ];
    return '<span class="badge badge-' . e($status) . '">' . e($labels[$status] ?? $status) . '</span>';
}

function avatar(string $initials, string $color): string {
    return '<span class="avatar" style="background:' . e($color) . '">' . e($initials) . '</span>';
}

function user_avatar(array $user): string {
    if (!empty($user['photo'])) {
        return '<span class="avatar avatar-photo"><img src="' . e(pub($user['photo'])) . '" alt=""></span>';
    }
    return avatar($user['initials'] ?? '?', $user['color'] ?? '#666');
}

function progress_bar($pct): string {
    return '<div class="progress-text">' . e($pct) . '%</div><div class="progress"><span style="width:' . e($pct) . '%"></span></div>';
}

function is_active_page(string $page, string $target): string {
    return $page === $target ? 'active' : '';
}

function base_url(string $path = ''): string {
    return '/index.php' . ($path ? '?route=' . $path : '');
}

function app_url(string $page = ''): string {
    return '/index.php?route=app' . ($page ? '&page=' . $page : '');
}

function asset_url(string $path): string {
    return '/public/assets/' . ltrim($path, '/');
}

/**
 * Format Rupiah dalam bentuk ringkas (Juta/Miliar)
 */
function formatRupiah(float $rp): string {
    if ($rp >= 1_000_000_000) {
        return 'Rp ' . number_format($rp / 1_000_000_000, 1, ',', '.') . ' M';
    } elseif ($rp >= 1_000_000) {
        return 'Rp ' . number_format($rp / 1_000_000, 1, ',', '.') . ' Jt';
    }
    return 'Rp ' . number_format($rp, 0, ',', '.');
}

/**
 * Format Rupiah penuh
 */
function formatRupiahFull(float $rp): string {
    return 'Rp ' . number_format($rp, 0, ',', '.');
}

/**
 * @deprecated Use formatRupiah() instead
 */
function formatJuta(float $juta): string {
    return formatRupiah($juta * 1_000_000);
}

/**
 * @deprecated Use formatRupiah() instead
 */
function formatRupiahLP(int $rp): string {
    return formatRupiah($rp);
}

function pub(string $path): string {
    if (empty($path)) return '';
    if (str_starts_with($path, 'public/') || str_starts_with($path, '/')) return $path;
    return 'public/' . $path;
}

/**
 * Format tanggal Indonesia
 */
function formatTanggal(string $date): string {
    $timestamp = strtotime($date);
    if (!$timestamp) return $date;
    
    $bulan = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];
    
    return date('d', $timestamp) . ' ' . $bulan[(int)date('n', $timestamp)] . ' ' . date('Y', $timestamp);
}

/**
 * Format waktu relatif
 */
function timeAgo(string $datetime): string {
    $timestamp = strtotime($datetime);
    if (!$timestamp) return $datetime;
    
    $diff = time() - $timestamp;
    
    if ($diff < 60) return 'Baru saja';
    if ($diff < 3600) return floor($diff / 60) . ' menit lalu';
    if ($diff < 86400) return floor($diff / 3600) . ' jam lalu';
    if ($diff < 2592000) return floor($diff / 86400) . ' hari lalu';
    
    return formatTanggal($datetime);
}
