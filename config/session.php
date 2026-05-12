<?php
/**
 * SIPEDO - Session Bootstrap (versi database)
 * Data utama sekarang disimpan di MySQL, bukan session.
 * Session hanya digunakan untuk: currentUser, currentRole, flash message.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ── Muat koneksi database ─────────────────────────────
require_once BASE_PATH . '/config/database.php';

// ── Konstanta aplikasi ────────────────────────────────
define('APP_NAME',     'SIPEDO');
define('APP_SUBTITLE', 'Sistem Pengelolaan Donasi');

