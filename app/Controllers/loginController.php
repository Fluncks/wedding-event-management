<?php

namespace App\Controllers;

class LoginController extends BaseController
{
    public function index()
    {
        if (session()->get('user_id')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/login', ['title' => 'Login']);
    }

    public function login()
    {
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        if (! $email || ! $password) {
            return redirect()->back()->withInput()->with('error', 'Email and password are required.');
        }

        $userModel = model(\App\Models\UserModel::class);
        $user      = $userModel->where('email', $email)->first();

        if (! $user || ! password_verify($password, $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Invalid email or password.');
        }

        session()->set([
            'user_id'    => $user['id'],
            'user_name'  => $user['name'],
            'user_email' => $user['email'],
            'user_role'  => $user['role'],
        ]);

        return redirect()->to('/dashboard')->with('message', 'Welcome back, ' . $user['name'] . '!');
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('/login')->with('message', 'You have been logged out.');
    }
}
