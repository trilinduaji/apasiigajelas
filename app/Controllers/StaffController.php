<?php
/**
 * SIPEDO - Staff Controller (versi database)
 * Disesuaikan dengan skema query.sql
 */
class StaffController {
    
    /**
     * Tambah staff baru
     */
    public function add(): void {
        require_login();
        $name    = trim($_POST['name']    ?? '');
        $email   = trim($_POST['email']   ?? '');
        $jabatan = trim($_POST['jabatan'] ?? 'Staff Verifikasi');

        if (!$name || !$email) {
            flash('Nama dan email wajib diisi.', 'error');
            redirect_to(app_url('pengguna'));
        }

        $userId = StaffModel::create($name, $email, $jabatan);
        $staff  = StaffModel::findById($userId);
        $kode   = $staff['kode'] ?? ('STF-' . $userId);
        
        add_log('Menambah staff baru: ' . $name, $kode);
        flash('Staff berhasil ditambahkan.', 'success');
        redirect_to(app_url('pengguna'));
    }

    /**
     * Set status staff (active/inactive)
     */
    public function setStatus(): void {
        require_login();
        $id     = (int) ($_POST['id']     ?? 0);
        $status = $_POST['status'] ?? 'inactive';

        $name = StaffModel::setStatus($id, $status);
        if ($name) {
            add_log('Mengubah status staff: ' . $name . ' ke ' . $status, 'UID-' . $id);
            flash('Status staff diperbarui.', 'success');
        }
        redirect_to(app_url('pengguna'));
    }

    /**
     * Update jabatan staff
     */
    public function updateJabatan(): void {
        require_login();
        $id      = (int) ($_POST['id']      ?? 0);
        $jabatan = trim($_POST['jabatan'] ?? '');

        if ($jabatan) {
            StaffModel::updateJabatan($id, $jabatan);
            $user = UserModel::findById($id);
            add_log('Mengubah jabatan staff: ' . ($user['name'] ?? 'Unknown'), 'UID-' . $id);
            flash('Jabatan staff diperbarui.', 'success');
        }
        redirect_to(app_url('pengguna'));
    }

    /**
     * Hapus staff
     */
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
