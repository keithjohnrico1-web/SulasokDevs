<?= $this->extend('customer/layout') ?>
<?= $this->section('content') ?>
<h3>Contact Support</h3>
<form method="POST" action="<?= site_url('customer/action') ?>"><?= csrf_field() ?>
    <input type="hidden" name="action" value="ticket"><input type="hidden" name="tab" value="support">
    <div class="form-grid">
        <div class="form-group full-width"><label>Subject:</label><input name="subject" required></div>
        <div class="form-group full-width"><label>Message:</label><textarea name="message" rows="4" required></textarea></div>
    </div><button type="submit">Submit Ticket</button>
</form>

<h3>My Tickets</h3>
<table><tr><th>Subject</th><th>Message</th><th>Reply</th></tr>
<?php foreach ($tickets as $t): ?>
    <tr><td><?= esc($t['subject']) ?></td><td><?= esc($t['message']) ?></td><td><?= esc($t['reply'] ?? 'Waiting for reply') ?></td></tr>
<?php endforeach; ?></table>
<?= $this->endSection() ?>