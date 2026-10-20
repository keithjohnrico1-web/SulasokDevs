<?= $this->extend('customer/layout') ?>
<?= $this->section('content') ?>
<h3>Completed Services &amp; Payments</h3>
<table><tr><th>Receipt #</th><th>Service</th><th>Date</th><th>Amount Paid</th></tr>
<?php foreach ($bookings as $b): ?>
    <tr><td>SSS-<?= str_pad($b['id'], 5, '0', STR_PAD_LEFT) ?></td><td><?= esc($b['name']) ?></td>
        <td><?= esc($b['schedule_date']) ?></td><td>₱<?= number_format($b['amount'], 2) ?></td></tr>
<?php endforeach; ?></table>
<?= $this->endSection() ?>