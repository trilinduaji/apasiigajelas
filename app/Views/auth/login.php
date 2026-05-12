<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPEDO - <?= $mode === 'register' ? 'Daftar' : 'Masuk' ?></title>
    <link rel="stylesheet" href="public/assets/css/style.css">
</head>
<body class="auth-page">
    <main class="auth-card">
        <div class="logo">
            <h1>SIPEDO</h1>
            <p>Sistem Pengelolaan Donasi</p>
        </div>

        <?php show_flash(); ?>

        <div class="tabs">
            <a class="tab <?= $mode === 'login'    ? 'active' : '' ?>"
               href="index.php?route=auth/login&mode=login">Masuk</a>
            <a class="tab <?= $mode === 'register' ? 'active' : '' ?>"
               href="index.php?route=auth/login&mode=register">Daftar</a>
        </div>

        <?php if ($mode === 'register'): ?>
            <form action="index.php?route=auth/register" method="post">
                <div class="field">
                    <label>Daftar Sebagai</label>
                    <select name="role" required>
                        <option value="donatur">Donatur</option>
                        <option value="staff">Staff</option>
                    </select>
                </div>
                <div class="field">
                    <label>Nama Lengkap</label>
                    <input type="text" name="name" placeholder="Nama lengkap" required>
                </div>
                <div class="field">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="email@gmail.com" required>
                </div>
                <div class="field">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Minimal 3 karakter" required>
                </div>
                <button class="btn full" type="submit">Buat Akun</button>
                <p class="hint">Sudah punya akun?
                    <a href="index.php?route=auth/login&mode=login">Masuk</a>
                </p>
            </form>
        <?php else: ?>
            <form action="index.php?route=auth/login" method="post">
                <div class="field">
                    <label>Masuk Sebagai</label>
                    <select name="role" required>
                        <option value="admin">Admin</option>
                        <option value="staff">Staff</option>
                        <option value="donatur">Donatur</option>
                    </select>
                </div>
                <div class="field">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="email@sipedo.org" required>
                </div>
                <div class="field">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <button class="btn full" type="submit">Masuk</button>
                <p class="hint">Demo: admin@s.id / staff@s.id / don@s.id — pass: 123</p>
                <p class="hint">Belum punya akun?
                    <a href="index.php?route=auth/login&mode=register">Daftar sekarang</a>
                </p>
            </form>
        <?php endif; ?>

        <p style="text-align:center;margin-top:1.5rem;font-size:.8rem;opacity:.5">
            <a href="index.php" style="color:inherit">← Kembali ke Beranda</a>
        </p>
    </main>
</body>
</html>

