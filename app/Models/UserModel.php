<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'firstname', 'middlename', 'lastname', 'birthdate', 'gender', 'phone',
        'email', 'address', 'username', 'password', 'status',
        'pref_reminders', 'pref_promos',
    ];
}