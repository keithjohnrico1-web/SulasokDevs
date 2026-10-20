<!DOCTYPE html>
<html>
<head>
    <title>Sun Son Solar - Employee Registration</title>
    <link rel="stylesheet" href="<?= base_url('registercustomer.css') ?>">
    <link rel="stylesheet" href="<?= base_url('dashboard.css') ?>">
</head>
<body>
    <div class="box">
        <img src="<?= base_url('logo.jpg') ?>" alt="Sun Son Solar Logo" class="logo">
        <h2>Employee Registration</h2>

        <?php if (session('error')): ?><div class="msg-err"><?= esc(session('error')) ?></div><?php endif; ?>
        <?php if (session('success')): ?><div class="msg"><?= esc(session('success')) ?></div><?php endif; ?>

        <form action="<?= site_url('employee/register') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="form-grid">
                <div class="form-group"><label>First Name:</label>
                    <input type="text" name="firstname" value="<?= esc(old('firstname')) ?>" required></div>
                <div class="form-group"><label>Middle Name:</label>
                    <input type="text" name="middlename" value="<?= esc(old('middlename')) ?>"></div>
                <div class="form-group"><label>Last Name:</label>
                    <input type="text" name="lastname" value="<?= esc(old('lastname')) ?>" required></div>
                <div class="form-group"><label>Birthdate:</label>
                    <input type="date" name="birthdate" value="<?= esc(old('birthdate')) ?>" required></div>
                <div class="form-group"><label>Gender:</label>
                    <select name="gender" required>
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select></div>
                <div class="form-group"><label>Phone Number:</label>
                    <input type="tel" name="phone" value="<?= esc(old('phone')) ?>" required></div>
                <div class="form-group full-width"><label>Department:</label>
                    <select name="department" required>
                        <option value="">Select Department</option>
                        <option>Administration</option><option>IT</option><option>Dispatch</option>
                        <option>Technician</option><option>Accounting</option><option>HR</option>
                        <option>Marketing</option><option>Sales</option><option>Customer Service</option>
                    </select></div>
                <div class="form-group full-width"><label>Email Address:</label>
                    <input type="email" name="email" value="<?= esc(old('email')) ?>" required></div>
                <div class="form-group full-width"><label>Address:</label>
                    <input type="text" name="address" value="<?= esc(old('address')) ?>" required></div>
                <div class="form-group"><label>Username:</label>
                    <input type="text" name="username" value="<?= esc(old('username')) ?>" required></div>
                <div class="form-group"><label>Password (min 8 characters):</label>
                    <input type="password" name="password" minlength="8" required></div>
            </div>
            <button type="submit">Register as Employee</button>
        </form>

        <div class="switch-link"><a href="<?= site_url('employee') ?>">Back to Dashboard</a></div>
    </div>
</body>
</html>