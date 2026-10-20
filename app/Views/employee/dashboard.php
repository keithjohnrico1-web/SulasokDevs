<!DOCTYPE html>
<html>
<head>
    <title>Sun Son Solar - Employee Dashboard</title>
    <link rel="stylesheet" href="<?= base_url('registercustomer.css') ?>">
    <link rel="stylesheet" href="<?= base_url('dashboard.css') ?>">
</head>
<body>
<div class="box wide">
    <div class="topbar">
        <h2><?= esc(session('name')) ?> (<?= esc(session('dept')) ?>)</h2>
        <a href="<?= site_url('logout') ?>">Logout</a>
    </div>

    <div class="tabs">
        <?php foreach ($tabs as $k => $v): ?>
            <a href="<?= site_url('employee?tab=' . $k) ?>" class="<?= $tab === $k ? 'active' : '' ?>"><?= $v ?></a>
        <?php endforeach; ?>
    </div>

    <?php if (! empty($msg)): ?><div class="msg"><?= esc($msg) ?></div><?php endif; ?>

<?php $act = site_url('employee/action'); ?>

<?php if ($tab === 'checkin'): ?>
    <h3>Time In</h3>
    <form method="POST" action="<?= $act ?>" id="ci">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="checkin">
        <input type="hidden" name="lat" id="lat"><input type="hidden" name="lng" id="lng">
        <button type="button" class="checkin-btn" onclick="go()">CHECK IN</button>
    </form>
    <p id="st" style="text-align:center;font-size:13px;color:#555;"></p>
    <script>
    function go() {
        document.getElementById('st').innerText = 'Getting your location...';
        navigator.geolocation.getCurrentPosition(function (p) {
            document.getElementById('lat').value = p.coords.latitude;
            document.getElementById('lng').value = p.coords.longitude;
            document.getElementById('ci').submit();
        }, function () { document.getElementById('st').innerText = 'Please allow location access, then try again.'; });
    }
    </script>
    <h3>My Recent Check-ins</h3>
    <table><tr><th>Time</th><th>Coordinates</th></tr>
    <?php foreach ($checkins as $c): ?>
        <tr><td><?= esc($c['checkin_time']) ?></td><td><?= esc($c['latitude']) ?>, <?= esc($c['longitude']) ?></td></tr>
    <?php endforeach; ?></table>

<?php elseif ($tab === 'jobs'): ?>
    <h3>Jobs Assigned to Me</h3>
    <table><tr><th>Customer</th><th>Address</th><th>Service</th><th>Date</th></tr>
    <?php foreach ($jobs as $j): ?>
        <tr><td><?= esc($j['firstname'] . ' ' . $j['lastname']) ?></td><td><?= esc($j['address']) ?></td>
            <td><?= esc($j['name']) ?></td><td><?= esc($j['schedule_date']) ?></td></tr>
    <?php endforeach; ?></table>

<?php elseif ($tab === 'announce'): ?>
    <?php if ($admin): ?>
    <h3>Post Announcement to Employees</h3>
    <form method="POST" action="<?= $act ?>">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="announce">
        <textarea name="message" rows="3" required></textarea>
        <button type="submit">Post</button>
    </form>
    <?php endif; ?>
    <h3>Announcements</h3>
    <?php foreach ($announcements as $n): ?>
        <div class="note"><?= esc($n['message']) ?> <small>(<?= esc($n['created_at']) ?>)</small></div>
    <?php endforeach; ?>

<?php elseif ($tab === 'report'): ?>
    <h3>Report a Problem</h3>
    <form method="POST" action="<?= $act ?>">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="report">
        <textarea name="details" rows="5" placeholder="Equipment issue, customer concern, etc." required></textarea>
        <button type="submit">Submit Report</button>
    </form>

<?php elseif ($tab === 'users' && ! empty($editing)): ?>
    <?php if (! $u): ?>
        <p>User not found.</p>
    <?php else: $t = $editType; ?>
    <h3>Edit <?= $t === 'e' ? 'Employee' : 'Customer' ?>: <?= esc($u['username']) ?></h3>
    <form method="POST" action="<?= $act ?>">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="edituser">
        <input type="hidden" name="t" value="<?= $t ?>">
        <input type="hidden" name="id" value="<?= $u['id'] ?>">
        <div class="form-grid">
            <div class="form-group"><label>First Name:</label><input name="firstname" value="<?= esc($u['firstname']) ?>" required></div>
            <div class="form-group"><label>Middle Name:</label><input name="middlename" value="<?= esc($u['middlename']) ?>"></div>
            <div class="form-group"><label>Last Name:</label><input name="lastname" value="<?= esc($u['lastname']) ?>" required></div>
            <div class="form-group"><label>Birthdate:</label><input type="date" name="birthdate" value="<?= esc($u['birthdate']) ?>" required></div>
            <div class="form-group"><label>Gender:</label>
                <select name="gender">
                    <?php foreach (['Male', 'Female', 'Other'] as $g): ?>
                        <option<?= $u['gender'] === $g ? ' selected' : '' ?>><?= $g ?></option>
                    <?php endforeach; ?>
                </select></div>
            <div class="form-group"><label>Phone:</label><input name="phone" value="<?= esc($u['phone']) ?>" required></div>
            <?php if ($t === 'e'): ?>
            <div class="form-group full-width"><label>Department:</label>
                <select name="department">
                    <?php foreach (['Administration', 'IT', 'Dispatch', 'Technician', 'Accounting', 'HR', 'Marketing', 'Sales', 'Customer Service'] as $d): ?>
                        <option<?= $u['department'] === $d ? ' selected' : '' ?>><?= $d ?></option>
                    <?php endforeach; ?>
                </select></div>
            <?php endif; ?>
            <div class="form-group full-width"><label>Email:</label><input type="email" name="email" value="<?= esc($u['email']) ?>" required></div>
            <div class="form-group full-width"><label>Address:</label><input name="address" value="<?= esc($u['address']) ?>" required></div>
        </div>
        <button type="submit">Save Changes</button>
    </form>
    <?php endif; ?>
    <div class="switch-link"><a href="<?= site_url('employee?tab=users') ?>">Back to Users</a></div>

<?php elseif ($tab === 'users'): ?>
    <h3>Employees
        <a class="btn" style="background:#ff9900;color:white;text-decoration:none;border-radius:4px;" href="<?= site_url('employee/register') ?>">+ Add Employee</a>
    </h3>
    <table><tr><th>Name</th><th>Department</th><th>Username</th><th>Status</th><th></th></tr>
    <?php foreach ($employees as $u): $to = $u['status'] === 'Active' ? 'Inactive' : 'Active'; ?>
        <tr>
            <td><?= esc($u['firstname'] . ' ' . $u['lastname']) ?></td>
            <td><?= esc($u['department']) ?></td>
            <td><?= esc($u['username']) ?></td>
            <td><?= esc($u['status']) ?></td>
            <td><a href="<?= site_url('employee?tab=users&edit=e' . $u['id']) ?>">Edit</a>
                <form method="POST" action="<?= $act ?>" class="inline">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="status">
                    <input type="hidden" name="id" value="<?= $u['id'] ?>">
                    <input type="hidden" name="status" value="<?= $to ?>">
                    <button class="btn"><?= $to === 'Inactive' ? 'Deactivate' : 'Activate' ?></button>
                </form></td>
        </tr>
    <?php endforeach; ?></table>

    <h3>Customers</h3>
    <table><tr><th>Name</th><th>Email</th><th>Phone</th><th>Status</th><th></th></tr>
    <?php foreach ($customers as $u): $to = $u['status'] === 'Active' ? 'Inactive' : 'Active'; ?>
        <tr>
            <td><?= esc($u['firstname'] . ' ' . $u['lastname']) ?></td>
            <td><?= esc($u['email']) ?></td>
            <td><?= esc($u['phone']) ?></td>
            <td><?= esc($u['status']) ?></td>
            <td><a href="<?= site_url('employee?tab=users&edit=c' . $u['id']) ?>">Edit</a>
                <form method="POST" action="<?= $act ?>" class="inline">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="cstatus">
                    <input type="hidden" name="id" value="<?= $u['id'] ?>">
                    <input type="hidden" name="status" value="<?= $to ?>">
                    <button class="btn"><?= $to === 'Inactive' ? 'Deactivate' : 'Activate' ?></button>
                </form></td>
        </tr>
    <?php endforeach; ?></table>

<?php elseif ($tab === 'schedule'): ?>
    <h3>Calendar &nbsp;
        <a href="<?= site_url('employee?tab=schedule&m=' . date('Y-m', strtotime("$m-01 -1 month"))) ?>">&laquo; Prev</a>
        &nbsp;<?= date('F Y', $first) ?>&nbsp;
        <a href="<?= site_url('employee?tab=schedule&m=' . date('Y-m', strtotime("$m-01 +1 month"))) ?>">Next &raquo;</a>
    </h3>
    <table class="cal">
        <tr><?php foreach (['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $d) echo "<th>$d</th>"; ?></tr>
        <tr>
        <?php
        $pad = (int) date('w', $first);
        for ($i = 0; $i < $pad; $i++) echo '<td></td>';
        for ($d = 1; $d <= (int) date('t', $first); $d++) {
            if (($pad + $d - 1) % 7 === 0 && $d > 1) echo '</tr><tr>';
            echo "<td><b>$d</b>";
            foreach ($byday[$d] ?? [] as $b) {
                echo "<div class='ev'>" . esc($b['name']) . ' - ' . esc($b['firstname']) . ' (' . esc($b['status']) . ')</div>';
            }
            echo '</td>';
        }
        ?>
        </tr>
    </table>

    <h3>All Bookings</h3>
    <table><tr><th>Date</th><th>Customer</th><th>Service</th><th>Technician</th><th>Status</th><th>Amount</th><th></th></tr>
    <?php foreach ($bookings as $b): $f = 'b' . $b['id']; ?>
        <tr>
            <td><input type="date" name="date" form="<?= $f ?>" value="<?= esc($b['schedule_date']) ?>"></td>
            <td><?= esc($b['firstname'] . ' ' . $b['lastname']) ?></td>
            <td><?= esc($b['name']) ?></td>
            <td><select name="tech" form="<?= $f ?>">
                <option value="">-- none --</option>
                <?php foreach ($techs as $t): ?>
                    <option value="<?= $t['id'] ?>"<?= $b['technician_id'] == $t['id'] ? ' selected' : '' ?>><?= esc($t['firstname'] . ' ' . $t['lastname']) ?></option>
                <?php endforeach; ?>
            </select></td>
            <td><select name="status" form="<?= $f ?>">
                <?php foreach (['Pending', 'Approved', 'Declined', 'Completed'] as $s): ?>
                    <option<?= $b['status'] === $s ? ' selected' : '' ?>><?= $s ?></option>
                <?php endforeach; ?>
            </select></td>
            <td><input type="number" step="0.01" name="amount" form="<?= $f ?>" value="<?= esc($b['amount']) ?>" style="width:90px"></td>
            <td>
                <form id="<?= $f ?>" method="POST" action="<?= $act ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="booking">
                    <input type="hidden" name="id" value="<?= $b['id'] ?>">
                </form>
                <button form="<?= $f ?>" class="btn">Save</button>
            </td>
        </tr>
    <?php endforeach; ?></table>

<?php elseif ($tab === 'reports'): ?>
    <div class="note">Total revenue: <b>₱<?= number_format((float) $rev, 2) ?></b> &nbsp;|&nbsp;
        Registered customers: <b><?= $custCount ?></b></div>
    <h3>Service Usage</h3><canvas id="c1" height="90"></canvas>
    <h3>Bookings by Status</h3><div style="max-width:300px"><canvas id="c2"></canvas></div>
    <h3>Monthly Revenue</h3><canvas id="c3" height="90"></canvas>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
    <script>
    var o = {plugins: {legend: {display: false}}};
    new Chart(c1, {type: 'bar', data: {labels: <?= json_encode(array_column($use, 'name')) ?>, datasets: [{data: <?= json_encode(array_map('intval', array_column($use, 'c'))) ?>, backgroundColor: '#ff9900'}]}, options: o});
    new Chart(c2, {type: 'doughnut', data: {labels: <?= json_encode(array_column($stat, 'status')) ?>, datasets: [{data: <?= json_encode(array_map('intval', array_column($stat, 'c'))) ?>, backgroundColor: ['#ff9900', '#28a745', '#dc3545', '#007bff']}]}});
    new Chart(c3, {type: 'line', data: {labels: <?= json_encode(array_column($mon, 'm')) ?>, datasets: [{data: <?= json_encode(array_map('floatval', array_column($mon, 't'))) ?>, borderColor: '#ff9900'}]}, options: o});
    </script>
    <h3>Customer Activity</h3>
    <table><tr><th>Customer</th><th>Bookings</th><th>Completed</th><th>Total Paid</th></tr>
    <?php foreach ($activity as $c): ?>
        <tr><td><?= esc($c['firstname'] . ' ' . $c['lastname']) ?></td><td><?= $c['n'] ?></td>
            <td><?= (int) $c['d'] ?></td><td>₱<?= number_format((float) $c['t'], 2) ?></td></tr>
    <?php endforeach; ?></table>

<?php elseif ($tab === 'notify'): ?>
    <h3>Send Notification to Customers</h3>
    <form method="POST" action="<?= $act ?>">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="notify">
        <div class="form-group" style="margin-bottom:10px"><label>Type:</label>
            <select name="type"><option>Reminder</option><option>Promotion</option><option>System Update</option></select></div>
        <textarea name="message" rows="3" required></textarea>
        <button type="submit">Send</button>
    </form>
    <h3>Sent</h3>
    <?php foreach ($notes as $n): ?>
        <div class="note"><b><?= esc($n['type']) ?>:</b> <?= esc($n['message']) ?> <small>(<?= esc($n['created_at']) ?>)</small></div>
    <?php endforeach; ?>

<?php elseif ($tab === 'settings'): ?>
    <h3>Service Categories, Pricing &amp; Availability</h3>
    <table><tr><th>Service</th><th>Price (₱)</th><th>Available days</th><th>Max/day</th><th>On</th><th></th></tr>
    <?php foreach ($services as $s): $f = 's' . $s['id']; $on = explode(',', $s['available_days']); ?>
        <tr>
            <td><?= esc($s['name']) ?></td>
            <td><input type="number" step="0.01" name="price" form="<?= $f ?>" value="<?= esc($s['price']) ?>" style="width:90px"></td>
            <td class="days">
                <?php foreach (['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $d): ?>
                    <label><input type="checkbox" name="days[]" form="<?= $f ?>" value="<?= $d ?>"<?= in_array($d, $on) ? ' checked' : '' ?>><?= $d ?></label>
                <?php endforeach; ?>
            </td>
            <td><input type="number" name="max" form="<?= $f ?>" value="<?= esc($s['max_per_day']) ?>" style="width:60px"></td>
            <td><input type="checkbox" name="active" form="<?= $f ?>" <?= $s['active'] ? 'checked' : '' ?> style="width:auto"></td>
            <td>
                <form id="<?= $f ?>" method="POST" action="<?= $act ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="service">
                    <input type="hidden" name="id" value="<?= $s['id'] ?>">
                </form>
                <button form="<?= $f ?>" class="btn">Save</button>
            </td>
        </tr>
    <?php endforeach; ?></table>

    <h3>Add Service</h3>
    <form method="POST" action="<?= $act ?>">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="newservice">
        <div class="form-grid">
            <div class="form-group"><label>Name:</label><input name="name" required></div>
            <div class="form-group"><label>Price:</label><input type="number" step="0.01" name="price" value="0"></div>
        </div>
        <button type="submit">Add</button>
    </form>

<?php elseif ($tab === 'tickets'): ?>
    <h3>Customer Tickets</h3>
    <?php foreach ($tickets as $t): ?>
        <div class="note"><b><?= esc($t['subject']) ?></b> — <?= esc($t['firstname'] . ' ' . $t['lastname']) ?><br><?= esc($t['message']) ?></div>
        <form method="POST" action="<?= $act ?>">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="reply">
            <input type="hidden" name="id" value="<?= $t['id'] ?>">
            <textarea name="reply" rows="2" placeholder="Reply"><?= esc($t['reply'] ?? '') ?></textarea>
            <button class="btn" style="margin:5px 0 15px">Send Reply</button>
        </form>
    <?php endforeach; ?>

<?php elseif ($tab === 'incidents'): ?>
    <h3>Reports from Employees</h3>
    <table><tr><th>Date</th><th>Employee</th><th>Details</th></tr>
    <?php foreach ($incidents as $r): ?>
        <tr><td><?= esc($r['created_at']) ?></td><td><?= esc($r['firstname'] . ' ' . $r['lastname']) ?></td><td><?= esc($r['details']) ?></td></tr>
    <?php endforeach; ?></table>

<?php elseif ($tab === 'attendance'): ?>
    <h3>Check-in Records</h3>
    <table><tr><th>Name</th><th>Time</th><th>Coordinates</th></tr>
    <?php foreach ($attendance as $c): ?>
        <tr><td><?= esc($c['name']) ?></td><td><?= esc($c['checkin_time']) ?></td><td><?= esc($c['latitude']) ?>, <?= esc($c['longitude']) ?></td></tr>
    <?php endforeach; ?></table>
<?php endif; ?>

</div>
</body>
</html>