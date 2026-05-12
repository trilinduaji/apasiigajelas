<?php
/**
 * SIPEDO - Auth Controller (versi database)
 */
class AuthController {
    public function showLogin(): void {
        if (current_user()) {
            redirect_to(app_url());
        }
        $mode = $_GET['mode'] ?? 'login';
        View::render('auth/login', compact('mode'));
    }

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
        $_SESSION['currentUser']  = $user;
        $_SESSION['currentRole']  = $user['role'];

        add_log('Login ke sistem', '-');
        redirect_to(app_url());
    }

    public function register(): void {
        $name     = trim($_POST['name']     ?? '');
        $email    = trim($_POST['email']    ?? '');
        $password = $_POST['password']      ?? '';
        $role     = $_POST['role']          ?? 'donatur';

        if (!$name || !$email || !$password) {
            flash('Lengkapi semua field.', 'error');
            redirect_to(base_url('auth/login') . '&mode=register');
        }

        if (UserModel::findByEmail($email)) {
            flash('Email sudah terdaftar.', 'error');
            redirect_to(base_url('auth/login') . '&mode=register');
        }

        UserModel::create($name, $email, $password, $role);
        flash('Akun berhasil dibuat. Silakan masuk.', 'success');
        redirect_to(base_url('auth/login') . '&mode=login');
    }

    public function logout(): void {
        add_log('Logout dari sistem', '-');
        session_destroy();
        redirect_to(base_url());
    }
}
