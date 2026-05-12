<?php
/**
 * SIPEDO - App Controller
 * Handles the main authenticated app shell + page routing
 * Data diambil dari database dan di-inject sebagai variabel ke view.
 */
class AppController {
    private array $menus = [
        'admin' => [
            'Manajemen' => [
                'dash-admin'   => 'Dashboard',
                'pengguna'     => 'Pengguna & Staff',
                'program-admin'=> 'Program Bantuan',
                'rekap-donasi' => 'Rekap Donasi',
            ],
            'Sistem' => [
                'log'          => 'Log Aktivitas',
                'pengaturan'   => 'Pengaturan Sistem',
                'profil-admin' => 'Profil Saya',
            ],
        ],
        'staff' => [
            'Menu Staff' => [
                'dash-staff'    => 'Dashboard',
                'verifikasi'    => 'Verifikasi Donasi',
                'program-staff' => 'Program Bantuan',
                'tambah-program'=> 'Tambah Program',
                'progress-staff'=> 'Progress & Donatur',
                'riwayat-staff' => 'Riwayat Verifikasi',
                'profil-staff'  => 'Profil Saya',
            ],
        ],
        'donatur' => [
            'Menu Donatur' => [
                'dash-donatur'   => 'Beranda',
                'program-donatur'=> 'Jelajahi Program',
                'riwayat-donasi' => 'Riwayat Donasi',
                'profil-donatur' => 'Profil Saya',
            ],
        ],
    ];

    private array $titles = [
        'dash-admin'    => 'Dashboard Admin',
        'pengguna'      => 'Pengguna & Staff',
        'program-admin' => 'Program Bantuan',
        'rekap-donasi'  => 'Rekap Donasi',
        'log'           => 'Log Aktivitas',
        'laporan'       => 'Laporan & Ekspor',
        'pengaturan'    => 'Pengaturan Sistem',
        'profil-admin'  => 'Profil Saya',
        'dash-staff'    => 'Dashboard Staff',
        'verifikasi'    => 'Panel Verifikasi Donasi',
        'program-staff' => 'Program Bantuan',
        'tambah-program'=> 'Tambah Program Baru',
        'edit-program'  => 'Edit Program',
        'progress-staff'=> 'Progress & Donatur',
        'riwayat-staff' => 'Riwayat Verifikasi',
        'profil-staff'  => 'Profil Saya',
        'dash-donatur'  => 'Beranda',
        'program-donatur'=> 'Jelajahi Program',
        'program-detail'=> 'Detail Program',
        'riwayat-donasi'=> 'Riwayat Donasi',
        'profil-donatur'=> 'Profil Saya',
    ];

    public function show(): void {
        require_login();

        $role        = current_role();
        $defaultPage = ['admin' => 'dash-admin', 'staff' => 'dash-staff', 'donatur' => 'dash-donatur'][$role] ?? 'dash-donatur';
        $page        = $_GET['page'] ?? $defaultPage;
        $user        = current_user();
        $menus       = $this->menus[$role] ?? [];
        $titles      = $this->titles;

        // Page-specific CSS
        $pageCss    = App::basePath() . '/public/assets/css/' . basename($page) . '.css';
        $hasPageCss = file_exists($pageCss);

        // Include the page partial
        $pageFile = App::basePath() . '/app/Views/' . $this->resolveViewDir($role, $page) . '/' . basename($page) . '.php';
        if (!file_exists($pageFile)) {
            $pageFile = App::basePath() . '/app/Views/' . $this->resolveViewDir($role, $defaultPage) . '/' . basename($defaultPage) . '.php';
            $page = $defaultPage;
        }

        // ── Preload data dari database ──────────────────────────────
        $donations = DonationModel::all();
        $programs  = ProgramModel::all();
        $staffList = StaffModel::all();
        $logs      = DB::fetchAll(
            'SELECT id, user_id, actor_name, role, description, ref, created_at
             FROM activity_logs
             ORDER BY created_at DESC
             LIMIT 200'
        );
        $settings  = DB::fetchAll('SELECT `key`, `value`, `description` FROM settings');
        $settingsMap = array_column($settings, 'value', 'key');
        $allUsers  = UserModel::all();
        // ──────────────────────────────────────────────────────────────

        View::render('shared/layout', compact(
            'role', 'page', 'user', 'menus', 'titles', 'hasPageCss', 'pageFile', 'defaultPage',
            'donations', 'programs', 'staffList', 'logs', 'settingsMap', 'allUsers'
        ));
    }

    private function resolveViewDir(string $role, string $page): string {
        $adminPages  = ['dash-admin','pengguna','program-admin','rekap-donasi','log','laporan','pengaturan','profil-admin'];
        $staffPages  = ['dash-staff','verifikasi','program-staff','tambah-program','edit-program','progress-staff','riwayat-staff','profil-staff'];
        $donaturPages= ['dash-donatur','program-donatur','program-detail','riwayat-donasi','profil-donatur'];

        if (in_array($page, $adminPages,   true)) return 'admin';
        if (in_array($page, $staffPages,   true)) return 'staff';
        if (in_array($page, $donaturPages, true)) return 'donatur';

        return match($role) {
            'admin'  => 'admin',
            'staff'  => 'staff',
            default  => 'donatur',
        };
    }
}
