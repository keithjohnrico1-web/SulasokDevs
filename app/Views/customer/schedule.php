<?= $this->extend('customer/layout') ?>
<?= $this->section('content') ?>
<h3>Book a Service</h3>
<form method="POST" action="<?= site_url('customer/action') ?>">
    <?= csrf_field() ?>
    <input type="hidden" name="action" value="book"><input type="hidden" name="tab" value="schedule">
    <div class="form-grid">
        <div class="form-group"><label>Service:</label>
            <select name="service_id" required>
                <?php foreach ($services as $s): ?>
                    <option value="<?= $s['id'] ?>"><?= esc($s['name']) ?> (<?= esc($s['available_days']) ?>)</option>
                <?php endforeach; ?>
            </select></div>
        <div class="form-group"><label>Preferred Date:</label><input type="date" name="date" min="<?= date('Y-m-d') ?>" required></div>
    </div>
    <button type="submit">Request Schedule</button>
</form>

<h3>My Bookings</h3>
<table><tr><th>Service</th><th>Date</th><th>Status</th><th>Change</th></tr>
<?php foreach ($bookings as $b): ?>
    <tr><td><?= esc($b['name']) ?></td><td><?= esc($b['schedule_date']) ?></td><td><?= esc($b['status']) ?></td>
    <td>
    <?php if (in_array($b['status'], ['Pending', 'Approved'])): ?>
        <form method="POST" action="<?= site_url('customer/action') ?>" class="inline"><?= csrf_field() ?>
            <input type="hidden" name="action" value="reschedule"><input type="hidden" name="tab" value="schedule">
            <input type="hidden" name="id" value="<?= $b['id'] ?>">
            <input type="date" name="date" min="<?= date('Y-m-d') ?>" required style="width:140px"> <button class="btn">Reschedule</button></form>
    <?php endif; ?>
    <?php if ($b['status'] === 'Pending'): ?>
        <form method="POST" action="<?= site_url('customer/action') ?>" class="inline"><?= csrf_field() ?>
            <input type="hidden" name="action" value="cancel"><input type="hidden" name="tab" value="schedule">
            <input type="hidden" name="id" value="<?= $b['id'] ?>"><button class="btn">Cancel</button></form>
    <?php endif; ?>
    </td></tr>
<?php endforeach; ?></table>
<?= $this->endSection() ?>