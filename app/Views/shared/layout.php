<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPEDO - <?= e($titles[$page] ?? 'Dashboard') ?></title>
    <link rel="stylesheet" href="public/assets/css/style.css">
    <?php if ($hasPageCss): ?>
        <link rel="stylesheet" href="public/assets/css/<?= e(basename($page)) ?>.css">
    <?php endif; ?>
</head>
<body>
    <div class="app">
        <aside class="sidebar">
            <div class="brand">
                <h1>SIPEDO</h1>
                <p>Sistem Pengelolaan Donasi</p>
            </div>

            <div class="user">
                <?= user_avatar($user) ?>
                <div>
                    <strong><?= e($user['name']) ?></strong>
                    <small><?= e(ucfirst($role)) ?></small>
                </div>
            </div>

            <?php foreach ($menus as $group => $items): ?>
                <div class="nav-title"><?= e($group) ?></div>
                <nav class="nav">
                    <?php foreach ($items as $key => $label): ?>
                        <a class="<?= is_active_page($page, $key) ?>"
                           href="index.php?route=app&page=<?= e($key) ?>"><?= e($label) ?></a>
                    <?php endforeach; ?>
                </nav>
            <?php endforeach; ?>

            <form class="logout" action="index.php?route=auth/logout" method="post">
                <button class="btn red full" type="submit">Keluar dari Akun</button>
            </form>
        </aside>

        <main class="main">
            <header class="topbar">
                <h2 class="page-title"><?= e($titles[$page] ?? 'Dashboard') ?></h2>
            </header>

            <section class="content">
                <?php show_flash(); ?>
                <?php include $pageFile; ?>
            </section>
        </main>
    </div>
</body>
</html>

