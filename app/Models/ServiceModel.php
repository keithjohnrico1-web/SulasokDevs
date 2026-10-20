<?php

namespace App\Models;

use CodeIgniter\Model;

class ServiceModel extends Model
{
    protected $table      = 'services';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'active', 'available_days', 'max_per_day'];
}