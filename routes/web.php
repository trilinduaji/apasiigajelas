<?php
/**
 * SIPEDO - Route Definitions
 */

// ── Landing & Auth (GET) ─────────────────────────────
Router::get('',           fn() => (new LandingController)->index());
Router::get('landing',    fn() => (new LandingController)->index());
Router::get('auth/login', fn() => (new AuthController)->showLogin());

// ── App (GET) ────────────────────────────────────────
Router::get('app',        fn() => (new AppController)->show());

// ── Auth (POST) ──────────────────────────────────────
Router::post('auth/login',    fn() => (new AuthController)->login());
Router::post('auth/register', fn() => (new AuthController)->register());
Router::post('auth/logout',   fn() => (new AuthController)->logout());

// ── Donation (POST) ──────────────────────────────────
Router::post('donation/donate', fn() => (new DonationController)->donate());
Router::post('donation/verify', fn() => (new DonationController)->verify());
Router::post('donation/reject', fn() => (new DonationController)->verify()); // same handler

// ── Program (POST) ───────────────────────────────────
Router::post('program/add',    fn() => (new ProgramController)->add());
Router::post('program/edit',   fn() => (new ProgramController)->edit());
Router::post('program/close',  fn() => (new ProgramController)->close());
Router::post('program/delete', fn() => (new ProgramController)->delete());

// ── Staff (POST) ─────────────────────────────────────
Router::post('staff/add',        fn() => (new StaffController)->add());
Router::post('staff/set-status', fn() => (new StaffController)->setStatus());
Router::post('staff/delete',     fn() => (new StaffController)->delete());

// ── Profile (POST) ───────────────────────────────────
Router::post('profile/update', fn() => (new ProfileController)->update());

