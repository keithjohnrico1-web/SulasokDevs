<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\EmployeeModel;

class Auth extends BaseController
{
    public function login()
    {
        return view('login');
    }

    public function attempt()
    {
        $type  = $this->request->getPost('type') === 'employee' ? 'employee' : 'customer';
        $model = $type === 'employee' ? new EmployeeModel() : new UserModel();
        $pass  = (string) $this->request->getPost('password');

        $r  = $model->where('username', (string) $this->request->getPost('username'))->first();
        $ok = false;

        if ($r && $r['status'] === 'Active') {
            if (password_verify($pass, $r['password'])) {
                $ok = true;
            } elseif (hash_equals((string) $r['password'], $pass)) { // old plain-text seed rows
                $ok = true;
                $model->update($r['id'], ['password' => password_hash($pass, PASSWORD_DEFAULT)]);
            }
        }

        if (! $ok) {
            return redirect()->to('/login')->with('error', 'Invalid username or password.');
        }

        session()->regenerate();
        session()->set([
            'type' => $type,
            'id'   => $r['id'],
            'name' => $r['firstname'] . ' ' . $r['lastname'],
            'dept' => $r['department'] ?? '',
        ]);

        return redirect()->to($type === 'employee' ? '/employee' : '/customer');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }

    public function registerForm()
    {
        return view('register_customer');
    }

    public function register()
    {
        $rules = [
            'firstname' => 'required',
            'lastname'  => 'required',
            'birthdate' => 'required|valid_date',
            'gender'    => 'required|in_list[Male,Female,Other]',
            'phone'     => 'required',
            'email'     => 'required|valid_email',
            'address'   => 'required',
            'username'  => 'required|is_unique[users.username]',
            'password'  => 'required|min_length[8]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        $data = $this->request->getPost([
            'firstname', 'middlename', 'lastname', 'birthdate', 'gender',
            'phone', 'email', 'address', 'username',
        ]);
        $data['password'] = password_hash((string) $this->request->getPost('password'), PASSWORD_DEFAULT);
        (new UserModel())->insert($data);

        return redirect()->to('/login')->with('success', 'Successfully registered! You can log in now.');
    }
}