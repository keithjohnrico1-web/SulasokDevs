<!DOCTYPE html>
<html>
<head>
    <title>Sun Son Solar - Customer Dashboard</title>
    <link rel="stylesheet" href="<?= base_url('registercustomer.css') ?>">
    <link rel="stylesheet" href="<?= base_url('dashboard.css') ?>">
</head>
<body>
<div class="box wide">
    <div class="topbar"><h2>Welcome, <?= esc($me['firstname']) ?>!</h2><a href="<?= site_url('logout') ?>">Logout</a></div>
    <div class="tabs">
        <?php foreach ($tabs as $k => $v): ?>
            <a href="<?= site_url('customer?tab=' . $k) ?>" class="<?= $tab === $k ? 'active' : '' ?>"><?= $v ?></a>
        <?php endforeach; ?>
    </div>
    <?php if (! empty($msg)): ?><div class="msg"><?= esc($msg) ?></div><?php endif; ?>

    <?= $this->renderSection('content') ?>
</div>
</body>
</html>