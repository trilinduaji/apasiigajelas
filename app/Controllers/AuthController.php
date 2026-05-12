<?php
/**
 * SIPEDO - Auth Controller (versi database)
 * Disesuaikan dengan skema query.sql - tanpa status di tabel users
 */
class AuthController {
    
    /**
     * Tampilkan halaman login
     */
    public function showLogin(): void {
        if (current_user()) {
            redirect_to(app_url());
        }
        $mode = $_GET['mode'] ?? 'login';
        View::render('auth/login', compact('mode'));
    }

    /**
     * Proses login
     */
    public function login(): void {
        $email    = trim($_POST['email']    ?? '');
        $password = $_POST['password']      ?? '';
        $role     = $_POST['role']          ?? '';

        $user = UserModel::authenticate($email, $password, $role);
        if (!$user) {
            flash('Email, password, atau role salah.', 'error');
            redirect_to(base_url('auth/login') . '&mode=login');
        }

        // Simpan di session (hanya data ringan)
        $_SESSION['currentUser'] = $user;
        $_SESSION['currentRole'] = $user['role'];

        add_log('Login ke sistem', '-');
        redirect_to(app_url());
    }

    /**
     * Proses registrasi
     */
    public function register(): void {
        $name     = trim($_POST['name']     ?? '');
        $email    = trim($_POST['email']    ?? '');
        $password = $_POST['password']      ?? '';
        $role     = $_POST['role']          ?? 'donatur';

        // Validasi input
        if (!$name || !$email || !$password) {
            flash('Lengkapi semua field.', 'error');
            redirect_to(base_url('auth/login') . '&mode=register');
        }

        // Cek email sudah terdaftar
        if (UserModel::findByEmail($email)) {
            flash('Email sudah terdaftar.', 'error');
            redirect_to(base_url('auth/login') . '&mode=register');
        }

        // Buat user baru
        UserModel::create($name, $email, $password, $role);
        
        flash('Akun berhasil dibuat. Silakan masuk.', 'success');
        redirect_to(base_url('auth/login') . '&mode=login');
    }

    /**
     * Proses logout
     */
    public function logout(): void {
        add_log('Logout dari sistem', '-');
        session_destroy();
        redirect_to(base_url());
    }
}
