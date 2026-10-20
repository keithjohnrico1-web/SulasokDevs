<?php

namespace App\Models;

use CodeIgniter\Model;

class BookingModel extends Model
{
    protected $table      = 'bookings';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'service_id', 'schedule_date', 'status', 'amount'];

    // Returns an error message, or "" if the service can be booked on that date
    public function slotOk($serviceId, $date, $excludeId = 0): string
    {
        $s = (new ServiceModel())->find($serviceId);
        if (! $s) {
            return 'Service not found.';
        }
        if (! in_array(date('D', strtotime($date)), explode(',', $s['available_days']))) {
            return 'That service is not available on that day.';
        }
        $c = $this->where('service_id', $serviceId)
                  ->where('schedule_date', $date)
                  ->whereIn('status', ['Pending', 'Approved'])
                  ->where('id !=', $excludeId)
                  ->countAllResults();
        return $c >= $s['max_per_day'] ? 'That date is fully booked.' : '';
    }

    // A customer's bookings with the service name
    public function forUser($uid, bool $completed)
    {
        $b = $this->select('bookings.*, services.name')
                  ->join('services', 'services.id = bookings.service_id')
                  ->where('bookings.user_id', $uid);

        return $completed
            ? $b->where('bookings.status', 'Completed')->orderBy('schedule_date', 'DESC')->findAll()
            : $b->where('bookings.status !=', 'Completed')->orderBy('schedule_date')->findAll();
    }
}