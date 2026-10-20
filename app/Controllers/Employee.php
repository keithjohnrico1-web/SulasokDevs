<?php

namespace App\Controllers;

use App\Models\EmployeeModel;

class Employee extends BaseController
{
    public function index()
    {
        $db    = db_connect();
        $eid   = session('id');
        $role  = role(session('dept'));
        $admin = $role === 'admin';
        $sched = $admin || $role === 'dispatch';
        $q     = fn (string $sql, array $b = []) => $db->query($sql, $b)->getResultArray();

        if ($admin) {
            $tabs = ['users' => 'Users', 'schedule' => 'Schedule', 'reports' => 'Reports', 'notify' => 'Notifications',
                     'settings' => 'Settings', 'tickets' => 'Tickets', 'incidents' => 'Incident Reports',
                     'attendance' => 'Attendance', 'announce' => 'Announcements'];
        } elseif ($sched) {
            $tabs = ['schedule' => 'Schedule', 'checkin' => 'Check-in', 'announce' => 'Announcements', 'report' => 'Report a Problem'];
        } else {
            $tabs = ['checkin' => 'Check-in', 'jobs' => 'My Jobs', 'announce' => 'Announcements', 'report' => 'Report a Problem'];
        }

        $tab = $this->request->getGet('tab') ?? array_key_first($tabs);
        if (! isset($tabs[$tab])) {
            $tab = array_key_first($tabs);
        }

        $data = ['tabs' => $tabs, 'tab' => $tab, 'admin' => $admin, 'msg' => session('msg')];
        $data['techs'] = $q("SELECT id, firstname, lastname FROM employees WHERE status='Active' AND department='Technician'");

        switch ($tab) {
            case 'checkin':
                $data['checkins'] = $q("SELECT * FROM checkins WHERE employee_id=? ORDER BY id DESC LIMIT 10", [$eid]);
                break;

            case 'jobs':
                $data['jobs'] = $q("SELECT b.schedule_date, s.name, u.firstname, u.lastname, u.address
                    FROM bookings b JOIN services s ON s.id=b.service_id JOIN users u ON u.id=b.user_id
                    WHERE b.technician_id=? AND b.status='Approved' ORDER BY b.schedule_date", [$eid]);
                break;

            case 'announce':
                $data['announcements'] = $q("SELECT * FROM announcements ORDER BY id DESC LIMIT 30");
                break;

            case 'users':
                $edit = (string) $this->request->getGet('edit');
                if ($edit !== '') {
                    $t    = $edit[0] === 'e' ? 'e' : 'c';
                    $rows = $q("SELECT * FROM " . ($t === 'e' ? 'employees' : 'users') . " WHERE id=?", [(int) substr($edit, 1)]);
                    $data['editing']  = true;
                    $data['editType'] = $t;
                    $data['u']        = $rows[0] ?? null;
                } else {
                    $data['employees'] = $q("SELECT * FROM employees");
                    $data['customers'] = $q("SELECT * FROM users");
                }
                break;

            case 'schedule':
                $m = (string) $this->request->getGet('m');
                if (! preg_match('/^\d{4}-\d{2}$/', $m)) {
                    $m = date('Y-m');
                }
                $byday = [];
                foreach ($q("SELECT b.schedule_date, b.status, s.name, u.firstname
                    FROM bookings b JOIN services s ON s.id=b.service_id JOIN users u ON u.id=b.user_id
                    WHERE DATE_FORMAT(b.schedule_date,'%Y-%m')=?", [$m]) as $b) {
                    $byday[(int) substr($b['schedule_date'], 8, 2)][] = $b;
                }
                $data['m']     = $m;
                $data['first'] = strtotime("$m-01");
                $data['byday'] = $byday;
                $data['bookings'] = $q("SELECT b.*, s.name, u.firstname, u.lastname
                    FROM bookings b JOIN services s ON s.id=b.service_id JOIN users u ON u.id=b.user_id
                    ORDER BY b.schedule_date");
                break;

            case 'reports':
                $data['rev']      = $q("SELECT COALESCE(SUM(amount),0) t FROM bookings WHERE status='Completed'")[0]['t'];
                $data['custCount'] = $q("SELECT COUNT(*) c FROM users")[0]['c'];
                $data['use']      = $q("SELECT s.name, COUNT(b.id) c FROM services s LEFT JOIN bookings b ON b.service_id=s.id GROUP BY s.id");
                $data['stat']     = $q("SELECT status, COUNT(*) c FROM bookings GROUP BY status");
                $data['mon']      = $q("SELECT DATE_FORMAT(schedule_date,'%Y-%m') m, SUM(amount) t FROM bookings WHERE status='Completed' GROUP BY m ORDER BY m");
                $data['activity'] = $q("SELECT u.firstname, u.lastname, COUNT(b.id) n, SUM(b.status='Completed') d, COALESCE(SUM(b.amount),0) t
                    FROM users u LEFT JOIN bookings b ON b.user_id=u.id GROUP BY u.id ORDER BY n DESC");
                break;

            case 'notify':
                $data['notes'] = $q("SELECT * FROM notifications ORDER BY id DESC LIMIT 20");
                break;

            case 'settings':
                $data['services'] = $q("SELECT * FROM services");
                break;

            case 'tickets':
                $data['tickets'] = $q("SELECT t.*, u.firstname, u.lastname FROM tickets t JOIN users u ON u.id=t.user_id ORDER BY t.id DESC");
                break;

            case 'incidents':
                $data['incidents'] = $q("SELECT r.*, e.firstname, e.lastname FROM reports r JOIN employees e ON e.id=r.employee_id ORDER BY r.id DESC");
                break;

            case 'attendance':
                $data['attendance'] = $q("SELECT * FROM checkins ORDER BY id DESC LIMIT 100");
                break;
        }

        return view('employee/dashboard', $data);
    }

    public function action()
    {
        $db    = db_connect();
        $eid   = session('id');
        $role  = role(session('dept'));
        $admin = $role === 'admin';
        $sched = $admin || $role === 'dispatch';
        $p     = $this->request->getPost();
        $a     = $p['action'] ?? '';
        $msg   = '';
        $run   = fn (string $sql, array $b) => $db->query($sql, $b);

        // which tab to return to after each action
        $tabFor = [
            'checkin' => 'checkin', 'report' => 'report', 'booking' => 'schedule',
            'status' => 'users', 'cstatus' => 'users', 'edituser' => 'users',
            'notify' => 'notify', 'announce' => 'announce',
            'service' => 'settings', 'newservice' => 'settings', 'reply' => 'tickets',
        ];

        if ($a === 'checkin') {
            $run("INSERT INTO checkins (employee_id, name, checkin_time, latitude, longitude) VALUES (?,?,NOW(),?,?)",
                [$eid, session('name'), $p['lat'], $p['lng']]);
            $msg = 'Checked in at ' . date('h:i A') . '.';
        } elseif ($a === 'report') {
            $run("INSERT INTO reports (employee_id, details) VALUES (?,?)", [$eid, $p['details']]);
            $msg = 'Report sent to admin.';
        } elseif ($a === 'booking' && $sched) {
            $amt = $p['status'] === 'Completed' ? (float) $p['amount'] : 0;
            $run("UPDATE bookings SET status=?, technician_id=?, amount=?, schedule_date=? WHERE id=?",
                [$p['status'], ($p['tech'] ?? '') ?: null, $amt, $p['date'], $p['id']]);
            $msg = 'Booking updated.';
        } elseif ($admin) {
            if ($a === 'status') {
                $run("UPDATE employees SET status=? WHERE id=? AND id<>?", [$p['status'], $p['id'], $eid]);
            }
            if ($a === 'cstatus') {
                $run("UPDATE users SET status=? WHERE id=?", [$p['status'], $p['id']]);
            }
            if ($a === 'edituser') {
                if ($p['t'] === 'e') {
                    $run("UPDATE employees SET firstname=?, middlename=?, lastname=?, birthdate=?, gender=?, department=?, phone=?, email=?, address=? WHERE id=?",
                        [$p['firstname'], $p['middlename'], $p['lastname'], $p['birthdate'], $p['gender'], $p['department'], $p['phone'], $p['email'], $p['address'], $p['id']]);
                } else {
                    $run("UPDATE users SET firstname=?, middlename=?, lastname=?, birthdate=?, gender=?, phone=?, email=?, address=? WHERE id=?",
                        [$p['firstname'], $p['middlename'], $p['lastname'], $p['birthdate'], $p['gender'], $p['phone'], $p['email'], $p['address'], $p['id']]);
                }
                $msg = 'User updated.';
            }
            if ($a === 'notify') {
                $run("INSERT INTO notifications (message, type) VALUES (?,?)", [$p['message'], $p['type']]);
                $msg = 'Sent to customers.';
            }
            if ($a === 'announce') {
                $run("INSERT INTO announcements (message) VALUES (?)", [$p['message']]);
                $msg = 'Announcement posted.';
            }
            if ($a === 'service') {
                $run("UPDATE services SET price=?, active=?, available_days=?, max_per_day=? WHERE id=?",
                    [$p['price'], isset($p['active']) ? 1 : 0, implode(',', $p['days'] ?? []), $p['max'], $p['id']]);
                $msg = 'Service saved.';
            }
            if ($a === 'newservice') {
                $run("INSERT INTO services (name, price) VALUES (?,?)", [$p['name'], $p['price']]);
                $msg = 'Service added.';
            }
            if ($a === 'reply') {
                $run("UPDATE tickets SET reply=? WHERE id=?", [$p['reply'], $p['id']]);
                $msg = 'Reply sent.';
            }
        }

        return redirect()->to('employee?tab=' . ($tabFor[$a] ?? 'checkin'))->with('msg', $msg);
    }

    public function registerForm()
    {
        if (! is_admin(session('dept'))) {
            return redirect()->to('/employee');
        }
        return view('register_employee');
    }

    public function register()
    {
        if (! is_admin(session('dept'))) {
            return redirect()->to('/employee');
        }

        $rules = [
            'firstname'  => 'required',
            'lastname'   => 'required',
            'birthdate'  => 'required|valid_date',
            'gender'     => 'required',
            'department' => 'required',
            'phone'      => 'required',
            'email'      => 'required|valid_email',
            'address'    => 'required',
            'username'   => 'required|is_unique[employees.username]',
            'password'   => 'required|min_length[8]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        $data = $this->request->getPost([
            'firstname', 'middlename', 'lastname', 'birthdate', 'gender',
            'department', 'phone', 'email', 'address', 'username',
        ]);
        $data['password'] = password_hash((string) $this->request->getPost('password'), PASSWORD_DEFAULT);
        (new EmployeeModel())->insert($data);

        return redirect()->to('/employee/register')->with('success', 'Employee registered.');
    }
}