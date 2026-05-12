<?php
/**
 * SIPEDO - Program Controller (versi database)
 */
class ProgramController {
    public function add(): void {
        require_login();

        $name        = trim($_POST['name']        ?? '');
        $category    = trim($_POST['category']    ?? '');
        $target      = (int) preg_replace('/[^0-9]/', '', $_POST['target'] ?? '0');
        $deadline    = $_POST['deadline']         ?? '';
        $status      = $_POST['status']           ?? 'active';
        $description = trim($_POST['description'] ?? '');

        if (!$name || !$category || !$target || !$deadline) {
            flash('Lengkapi semua field program.', 'error');
            redirect_to(app_url('tambah-program'));
        }

        $imageRel = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $ext     = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];
            if (in_array($ext, $allowed, true)) {
                $filename = 'prog-' . date('Ymd-His') . '-' . substr(md5(uniqid()), 0, 6) . '.' . $ext;
                $dest     = BASE_PATH . '/public/assets/uploads/programs/' . $filename;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                    $imageRel = 'assets/uploads/programs/' . $filename;
                }
            }
        }

        $user = current_user();
        ProgramModel::create([
            'name'        => $name,
            'category'    => $category,
            'target'      => $target,
            'deadline'    => $deadline,
            'status'      => $status,
            'image'       => $imageRel,
            'description' => $description,
        ], (int) $user['id']);

        add_log('Menambah program baru: ' . $name, '-');
        flash('Program berhasil ditambahkan.', 'success');
        redirect_to(app_url('program-staff'));
    }

    public function edit(): void {
        require_login();

        $kode        = $_POST['id']            ?? '';
        $name        = trim($_POST['name']     ?? '');
        $category    = trim($_POST['category'] ?? '');
        $target      = (int) preg_replace('/[^0-9]/', '', $_POST['target'] ?? '0');
        $deadline    = $_POST['deadline']      ?? '';
        $status      = $_POST['status']        ?? 'active';
        $description = trim($_POST['description'] ?? '');

        $program = ProgramModel::findByKode($kode);
        if (!$program) {
            flash('Program tidak ditemukan.', 'error');
            redirect_to(app_url('program-staff'));
        }

        $imageRel = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg','jpeg','png','webp'], true)) {
                $filename = 'prog-' . date('Ymd-His') . '-' . substr(md5(uniqid()), 0, 6) . '.' . $ext;
                $dest     = BASE_PATH . '/public/assets/uploads/programs/' . $filename;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                    $imageRel = 'assets/uploads/programs/' . $filename;
                }
            }
        }

        ProgramModel::update((int) $program['id'], compact('name','category','target','deadline','status','description','imageRel') + ['image' => $imageRel]);
        add_log('Mengedit program: ' . $name, $kode);
        flash('Program berhasil diperbarui.', 'success');
        redirect_to(app_url('program-staff'));
    }

    public function close(): void {
        require_login();
        $kode = $_POST['id'] ?? '';
        $prog = ProgramModel::findByKode($kode);
        if ($prog) {
            ProgramModel::setStatus((int) $prog['id'], 'closed');
            add_log('Menutup program: ' . $prog['name'], $kode);
            flash('Program ditutup.', 'success');
        }
        redirect_to(app_url('program-staff'));
    }

    public function delete(): void {
        require_login();
        $kode = $_POST['id'] ?? '';
        $prog = ProgramModel::findByKode($kode);
        if ($prog) {
            ProgramModel::setStatus((int) $prog['id'], 'deleted');
            add_log('Menghapus program: ' . $prog['name'], $kode);
            flash('Program dihapus.', 'success');
        }
        redirect_to(app_url('program-admin'));
    }
}
