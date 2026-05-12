<?php
/**
 * SIPEDO - Landing Page Controller
 */
class LandingController {
    public function index(): void {
        if (current_user()) {
            redirect_to(app_url());
        }

        $programs  = ProgramModel::all();
        $donations = DonationModel::all();

        // Hanya program aktif, urutkan by persentase
        $active = array_filter($programs, fn($p) => $p['status'] === 'active');
        usort($active, fn($a, $b) => $b['pct'] <=> $a['pct']);
        $displayPrograms = array_slice(array_values($active), 0, 6);

        // Statistik global
        $totalCollected = array_sum(array_column($programs, 'collected'));
        $totalTarget    = array_sum(array_column($programs, 'target'));
        $totalProgram   = count($active);
        $donaturUnik    = DonationModel::uniqueDonors();
        $verifiedCount  = count(DonationModel::verified());

        // Top donatur
        $topDonors = DonationModel::topDonors(5);

        View::render('landing/index', compact(
            'displayPrograms',
            'totalCollected',
            'totalTarget',
            'totalProgram',
            'donaturUnik',
            'verifiedCount',
            'topDonors'
        ));
    }
}
