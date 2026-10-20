<?php
namespace App\Controllers;
use App\Models\UserModel;
use App\Models\BookingModel;
use App\Models\ServiceModel;
use App\Models\TicketModel;
use App\Models\NotificationModel;

class Customer extends BaseController
{
    private const TABS = ['schedule' => 'My Schedule', 'notifications' => 'Notifications',
                          'profile' => 'Profile', 'history' => 'History', 'support' => 'Support'];

    public function index()
    {
        $uid = session('id');
        $tab = $this->request->getGet('tab') ?? 'schedule';
        if (! isset(self::TABS[$tab])) $tab = 'schedule';

        $me   = (new UserModel())->find($uid);
        $data = ['me' => $me, 'tab' => $tab, 'tabs' => self::TABS, 'msg' => session('msg')];

        switch ($tab) {
            case 'schedule':
                $data['services'] = (new ServiceModel())->where('active', 1)->findAll();
                $data['bookings'] = (new BookingModel())->forUser($uid, false);
                break;
            case 'notifications':
                $types = ['System Update'];
                if ($me['pref_reminders']) $types[] = 'Reminder';
                if ($me['pref_promos'])    $types[] = 'Promotion';
                $data['notes'] = (new NotificationModel())->whereIn('type', $types)->orderBy('id', 'DESC')->findAll(30);
                $data['updates'] = (new BookingModel())->select('bookings.status, bookings.schedule_date, services.name')
                    ->join('services', 'services.id = bookings.service_id')
                    ->where('bookings.user_id', $uid)->whereIn('bookings.status', ['Approved', 'Declined'])
                    ->orderBy('bookings.id', 'DESC')->findAll();
                break;
            case 'history':
                $data['bookings'] = (new BookingModel())->forUser($uid, true);
                break;
            case 'support':
                $data['tickets'] = (new TicketModel())->where('user_id', $uid)->orderBy('id', 'DESC')->findAll();
                break;
        }
        return view('customer/' . $tab, $data);
    }

    public function action()
    {
        $uid  = session('id');
        $a    = $this->request->getPost('action');
        $tab  = $this->request->getPost('tab') ?? 'schedule';
        $post = $this->request->getPost();
        $bk   = new BookingModel();
        $users = new UserModel();
        $msg  = '';

        switch ($a) {
            case 'book':
                $err = $bk->slotOk($post['service_id'], $post['date']);
                if ($err) { $msg = $err; break; }
                $bk->insert(['user_id' => $uid, 'service_id' => $post['service_id'],
                             'schedule_date' => $post['date'], 'status' => 'Pending']);
                $msg = 'Request sent! Waiting for approval.';
                break;

            case 'reschedule':
                $b = $bk->where('id', $post['id'])->where('user_id', $uid)
                        ->whereIn('status', ['Pending', 'Approved'])->first();
                $err = $b ? $bk->slotOk($b['service_id'], $post['date'], $post['id']) : 'Booking not found.';
                if ($err) { $msg = $err; break; }
                $bk->update($post['id'], ['schedule_date' => $post['date'], 'status' => 'Pending']);
                $msg = 'Rescheduled. Waiting for approval again.';
                break;

            case 'cancel':
                $bk->where('id', $post['id'])->where('user_id', $uid)->where('status', 'Pending')->delete();
                $msg = 'Booking cancelled.';
                break;

            case 'profile':
                $users->update($uid, ['phone' => $post['phone'], 'email' => $post['email'], 'address' => $post['address']]);
                $msg = 'Profile updated.';
                break;

            case 'prefs':
                $users->update($uid, ['pref_reminders' => isset($post['rem']) ? 1 : 0,
                                      'pref_promos'    => isset($post['promo']) ? 1 : 0]);
                $msg = 'Preferences saved.';
                break;

            case 'password':
                $u = $users->find($uid);
                if (password_verify($post['old'], $u['password'])) {
                    $users->update($uid, ['password' => password_hash($post['new'], PASSWORD_DEFAULT)]);
                    $msg = 'Password changed.';
                } else { $msg = 'Current password is wrong.'; }
                break;

            case 'ticket':
                (new TicketModel())->insert(['user_id' => $uid, 'subject' => $post['subject'], 'message' => $post['message']]);
                $msg = "Ticket submitted. We'll reply soon.";
                break;
        }
        return redirect()->to('customer?tab=' . $tab)->with('msg', $msg);
    }
}