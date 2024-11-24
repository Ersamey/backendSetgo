<?php

namespace App\Controllers;

use App\Models\LoginModel;

class Login extends BaseController
{
    protected $loginModel;

    public function __construct()
    {
        $this->loginModel = new LoginModel();
    }

    public function index()
    {
        // Validasi input
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        if (empty($username) || empty($password)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Username dan password harus diisi!'
            ])->setStatusCode(400);
        }

        // Cari user berdasarkan username
        $user = $this->loginModel->where('username', $username)->first();

        // Cek jika user tidak ditemukan
        if (!$user) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User not found'
            ])->setStatusCode(404);
        }

        // Cek password
        if ($password !== $user['password']) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Password is incorrect'
            ])->setStatusCode(401);
        }

        // Login berhasil, kembalikan data termasuk role
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Login success',
            'username' => $user['username'],
            'role' => $user['role']  // Kembalikan role
        ]);
    }

    public function create()
    {
        // Validasi input
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');
        $role = $this->request->getVar('role');

        if (empty($username) || empty($password) || empty($role)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Username, password, dan role harus diisi!'
            ])->setStatusCode(400);
        }

        // Data untuk disimpan
        $data = [
            'username' => $username,
            'password' => $password, // Menyimpan password dalam bentuk plaintext (tanpa enkripsi)
            'role' => $role
        ];

        // Cek jika data berhasil disimpan
        if ($this->loginModel->insert($data)) {
            return $this->response->setStatusCode(201)->setJSON([
                'success' => true,
                'message' => 'User created'
            ]);
        } else {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Failed to create user'
            ]);
        }
    }
}
