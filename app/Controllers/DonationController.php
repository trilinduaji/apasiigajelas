<?php
/**
 * SIPEDO - Donation Controller
 */
class DonationController {
    public function donate(): void {
        require_login();

        $programKode = $_POST['program_id'] ?? '';
        $amount      = (int) preg_replace('/[^0-9]/', '', $_POST['amount'] ?? '0');
        $method      = $_POST['method'] ?? '';

        $program = ProgramModel::findByKode($programKode);
        if (!$program || $amount <= 0) {
            flash('Data donasi tidak valid.', 'error');
            redirect_to(app_url('program-donatur'));
        }

        $user     = current_user();
        $proofRel = '';

        if (isset($_FILES['proof']) && $_FILES['proof']['error'] === UPLOAD_ERR_OK) {
            $ext     = strtolower(pathinfo($_FILES['proof']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'pdf'];
            if (in_array($ext, $allowed, true)) {
                $filename = 'bukti-' . date('Ymd-His') . '-' . substr(md5(uniqid()), 0, 6) . '.' . $ext;
                $dest     = BASE_PATH . '/public/assets/uploads/proofs/' . $filename;
                if (move_uploaded_file($_FILES['proof']['tmp_name'], $dest)) {
                    $proofRel = 'assets/uploads/proofs/' . $filename;
                }
            }
        }

        DonationModel::create([
            'user_id'    => (int) $user['id'],
            'program_id' => (int) $program['id'],
            'amount'     => $amount,
            'method'     => $method,
            'proof'      => $proofRel,
        ]);

        add_log('Mengirim donasi ke program: ' . $program['name'], $programKode);
        flash('Donasi berhasil dikirim. Menunggu verifikasi.', 'success');
        redirect_to(app_url('riwayat-donasi'));
    }

    public function verify(): void {
        require_login();

        $donationKode = $_POST['donation_id'] ?? '';
        $action       = $_POST['action']      ?? 'verify';
        $status       = $action === 'verify' ? 'verified' : 'rejected';

        $donation = DonationModel::findByKode($donationKode);
        if (!$donation) {
            flash('Donasi tidak ditemukan.', 'error');
            redirect_to(app_url('verifikasi'));
        }

        $user = current_user();
        DonationModel::updateStatus((int) $donation['id'], $status, (int) $user['id']);

        // Jika verified, update collected program
        if ($status === 'verified' && !empty($donation['program_id'])) {
            ProgramModel::addCollected((int) $donation['program_id'], (float) $donation['amount']);
        }

        $desc = $status === 'verified' ? 'Memverifikasi donasi' : 'Menolak donasi';
        add_log($desc, '#' . $donationKode);

        flash('Status donasi diperbarui.', 'success');
        redirect_to(app_url('verifikasi'));
    }
}
