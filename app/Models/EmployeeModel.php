<?php

namespace App\Models;

use CodeIgniter\Model;

class EmployeeModel extends Model
{
    protected $table      = 'employees';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'firstname', 'middlename', 'lastname', 'birthdate', 'gender', 'department',
        'phone', 'email', 'address', 'username', 'password', 'status',
    ];
}