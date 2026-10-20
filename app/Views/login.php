<!DOCTYPE html>
<html>
<head>
    <title>Sun Son Solar - Login</title>
    <link rel="stylesheet" href="<?= base_url('registercustomer.css') ?>">
    <link rel="stylesheet" href="<?= base_url('dashboard.css') ?>">
</head>
<body>
    <div class="box" style="width:380px;">
        <img src="<?= base_url('logo.jpg') ?>" alt="Sun Son Solar Logo" class="logo">
        <h2>Login</h2>

        <?php if (session('error')): ?><div class="msg-err"><?= esc(session('error')) ?></div><?php endif; ?>
        <?php if (session('success')): ?><div class="msg"><?= esc(session('success')) ?></div><?php endif; ?>

        <form action="<?= site_url('login') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="form-grid">
                <div class="form-group full-width">
                    <label>Login as:</label>
                    <select name="type">
                        <option value="customer">Customer</option>
                        <option value="employee">Employee</option>
                    </select>
                </div>
                <div class="form-group full-width"><label>Username:</label><input type="text" name="username" required></div>
                <div class="form-group full-width"><label>Password:</label><input type="password" name="password" required></div>
            </div>
            <button type="submit">Login</button>
        </form>

        <div class="switch-link"><a href="<?= site_url('register') ?>">Create a customer account</a></div>
    </div>
</body>
</html>