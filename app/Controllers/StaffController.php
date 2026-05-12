<?php
/**
 * SIPEDO - Staff Controller (versi database)
 */
class StaffController {
    public function add(): void {
        require_login();
        $name  = trim($_POST['name']  ?? '');
        $email = trim($_POST['email'] ?? '');

        if (!$name || !$email) {
            flash('Nama dan email wajib diisi.', 'error');
            redirect_to(app_url('pengguna'));
        }

        $userId = StaffModel::create($name, $email);
        add_log('Menambah staff baru: ' . $name, 'STF-' . $userId);
        flash('Staff berhasil ditambahkan.', 'success');
        redirect_to(app_url('pengguna'));
    }

    public function setStatus(): void {
        require_login();
        $id     = (int) ($_POST['id']     ?? 0);
        $status = $_POST['status'] ?? 'inactive';

        $name = StaffModel::setStatus($id, $status);
        if ($name) {
            add_log('Mengubah status staff: ' . $name, 'UID-' . $id);
            flash('Status staff diperbarui.', 'success');
        }
        redirect_to(app_url('pengguna'));
    }

    public function delete(): void {
        require_login();
        $id = (int) ($_POST['id'] ?? 0);

        $name = StaffModel::delete($id);
        if ($name) {
            add_log('Menghapus staff: ' . $name, 'UID-' . $id);
            flash('Staff berhasil dihapus.', 'success');
        }
        redirect_to(app_url('pengguna'));
    }
}
