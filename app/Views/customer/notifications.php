<?= $this->extend('customer/layout') ?>
<?= $this->section('content') ?>
<h3>Notifications</h3>
<?php foreach ($notes as $n): ?>
    <div class="note"><b><?= esc($n['type']) ?>:</b> <?= esc($n['message']) ?> <small>(<?= esc($n['created_at']) ?>)</small></div>
<?php endforeach; ?>
<?php foreach ($updates as $n): ?>
    <div class="note">Your <?= esc($n['name']) ?> on <?= esc($n['schedule_date']) ?> was <b><?= esc($n['status']) ?></b>.</div>
<?php endforeach; ?>
<?= $this->endSection() ?>