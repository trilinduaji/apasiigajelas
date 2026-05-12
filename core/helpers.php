<?php
/**
 * SIPEDO - Global Helper Functions (versi database)
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
 */
function add_log(string $desc, string $ref): void {
    $user = current_user();
    $role = ucfirst(current_role() ?? 'User');
    DB::run(
        'INSERT INTO activity_logs (user_id, actor_name, role, description, ref)
         VALUES (?, ?, ?, ?, ?)',
        [
            $user['id']   ?? null,
            $user['name'] ?? 'System',
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

function formatJuta(float $juta): string {
    if ($juta >= 1000) {
        return 'Rp ' . number_format($juta / 1000, 1, ',', '.') . ' M';
    }
    return 'Rp ' . number_format($juta, 1, ',', '.') . ' Jt';
}

function formatRupiahLP(int $rp): string {
    if ($rp >= 1_000_000_000) {
        return 'Rp ' . number_format($rp / 1_000_000_000, 1, ',', '.') . ' M';
    } elseif ($rp >= 1_000_000) {
        return 'Rp ' . number_format($rp / 1_000_000, 1, ',', '.') . ' Jt';
    }
    return 'Rp ' . number_format($rp, 0, ',', '.');
}

function formatRupiahFull(int $rp): string {
    return 'Rp ' . number_format($rp, 0, ',', '.');
}

function pub(string $path): string {
    if (empty($path)) return '';
    if (str_starts_with($path, 'public/') || str_starts_with($path, '/')) return $path;
    return 'public/' . $path;
}
