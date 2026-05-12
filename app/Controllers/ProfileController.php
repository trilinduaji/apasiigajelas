<?php
/**
 * SIPEDO - Profile Controller (versi database)
 * Disesuaikan dengan skema query.sql
 */
class ProfileController {
    public function update(): void {
        require_login();

        $user     = current_user();
        $userId   = (int) $user['id'];
        $action   = $_POST['action'] ?? 'update_profile';

        // Handle ganti password
        if ($action === 'change_password') {
            $oldPass = $_POST['old_password'] ?? '';
            $newPass = $_POST['new_password'] ?? '';

            if (!UserModel::verifyPassword($userId, $oldPass)) {
                flash('Password lama salah.', 'error');
            } elseif (strlen($newPass) < 3) {
                flash('Password minimal 3 karakter.', 'error');
            } else {
                UserModel::changePassword($userId, $newPass);
                flash('Password berhasil diubah.', 'success');
                add_log('Mengubah password', '-');
            }

            redirect_to(app_url('profil-' . current_role()));
            return;
        }

        // Handle update profil
        $name = trim($_POST['name'] ?? '');
        $photoRel = '';

        // Handle upload foto
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $ext      = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
            $allowed  = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (in_array($ext, $allowed, true)) {
                $safeEmail = preg_replace('/[^a-z0-9]/', '', strtolower($user['email'] ?? 'user'));
                $filename = 'avatar-' . $safeEmail . '-' . date('YmdHis') . '.' . $ext;
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
