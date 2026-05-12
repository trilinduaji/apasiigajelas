<?php
/**
 * SIPEDO - Profile Controller (versi database)
 */
class ProfileController {
    public function update(): void {
        require_login();

        $user     = current_user();
        $userId   = (int) $user['id'];
        $name     = trim($_POST['name'] ?? '');
        $action   = $_POST['action'] ?? 'profile';

        if ($action === 'password') {
            $current  = $_POST['current_password']  ?? '';
            $new      = $_POST['new_password']       ?? '';
            $confirm  = $_POST['confirm_password']   ?? '';

            if (!UserModel::verifyPassword($userId, $current)) {
                flash('Password lama salah.', 'error');
            } elseif ($new !== $confirm) {
                flash('Konfirmasi password tidak cocok.', 'error');
            } elseif (strlen($new) < 6) {
                flash('Password minimal 6 karakter.', 'error');
            } else {
                UserModel::changePassword($userId, $new);
                flash('Password berhasil diubah.', 'success');
                add_log('Mengubah password', '-');
            }

            redirect_to(app_url('profil-' . current_role()));
        }

        // Update profil + foto
        $photoRel = '';
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $ext      = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
            $allowed  = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (in_array($ext, $allowed, true)) {
                $filename = 'avatar-' . preg_replace('/[^a-z0-9]/', '', strtolower($user['name'] ?? 'user'))
                          . '-' . date('YmdHis') . '.' . $ext;
                $dest     = BASE_PATH . '/public/assets/uploads/avatars/' . $filename;
                if (move_uploaded_file($_FILES['photo']['tmp_name'], $dest)) {
                    $photoRel = 'assets/uploads/avatars/' . $filename;
                }
            }
        }

        if ($name) {
            UserModel::updateProfile($userId, $name, $photoRel);
            // Refresh session
            $_SESSION['currentUser'] = UserModel::findById($userId);
            flash('Profil berhasil diperbarui.', 'success');
            add_log('Memperbarui profil', '-');
        }

        redirect_to(app_url('profil-' . current_role()));
    }
}
