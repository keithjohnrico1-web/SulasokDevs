<?= $this->extend('customer/layout') ?>
<?= $this->section('content') ?>
<h3>Update Information</h3>
<form method="POST" action="<?= site_url('customer/action') ?>"><?= csrf_field() ?>
    <input type="hidden" name="action" value="profile"><input type="hidden" name="tab" value="profile">
    <div class="form-grid">
        <div class="form-group"><label>Phone:</label><input name="phone" value="<?= esc($me['phone']) ?>" required></div>
        <div class="form-group"><label>Email:</label><input type="email" name="email" value="<?= esc($me['email']) ?>" required></div>
        <div class="form-group full-width"><label>Address:</label><input name="address" value="<?= esc($me['address']) ?>" required></div>
    </div><button type="submit">Save</button>
</form>

<h3>Preferences</h3>
<form method="POST" action="<?= site_url('customer/action') ?>"><?= csrf_field() ?>
    <input type="hidden" name="action" value="prefs"><input type="hidden" name="tab" value="profile">
    <div class="days">
        <label><input type="checkbox" name="rem" <?= $me['pref_reminders'] ? 'checked' : '' ?>> Schedule reminders</label>
        <label><input type="checkbox" name="promo" <?= $me['pref_promos'] ? 'checked' : '' ?>> Promotions</label>
    </div>
    <button type="submit">Save Preferences</button>
</form>

<h3>Change Password</h3>
<form method="POST" action="<?= site_url('customer/action') ?>"><?= csrf_field() ?>
    <input type="hidden" name="action" value="password"><input type="hidden" name="tab" value="profile">
    <div class="form-grid">
        <div class="form-group"><label>Current Password:</label><input type="password" name="old" required></div>
        <div class="form-group"><label>New Password:</label><input type="password" name="new" minlength="8" required></div>
    </div><button type="submit">Change Password</button>
</form>
<?= $this->endSection() ?>