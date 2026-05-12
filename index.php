<?php
/**
 * SIPEDO - Main Entry Point
 * All requests go through index.php
 *
 * URL format:  index.php?route=<route>[&page=<page>][&id=<id>]
 * Examples:
 *   index.php                    → Landing page
 *   index.php?route=auth/login   → Login page
 *   index.php?route=app          → App dashboard
 *   index.php?route=app&page=pengguna
 */

define('BASE_PATH', __DIR__);

// ── Bootstrap ────────────────────────────────────────
require_once BASE_PATH . '/core/App.php';
App::init(BASE_PATH);

require_once BASE_PATH . '/core/Router.php';
require_once BASE_PATH . '/core/View.php';
require_once BASE_PATH . '/core/helpers.php';
require_once BASE_PATH . '/config/session.php';

View::setPath(BASE_PATH . '/app/Views');

// ── Load Models & Controllers (via autoloader) ───────
// Triggered on first use via spl_autoload

// ── Load Routes ──────────────────────────────────────
require_once BASE_PATH . '/routes/web.php';

// ── Dispatch ─────────────────────────────────────────
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$route  = trim($_GET['route'] ?? '', '/');

Router::dispatch($method, $route);
