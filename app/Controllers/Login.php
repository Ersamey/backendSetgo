<?php

namespace App\Controllers;

use App\Models\LoginModel;
use App\Models\SiswaModel;

class Login extends BaseController
{
    protected $loginModel;
    protected $siswaModel;

    public function __construct()
    {
        $this->loginModel = new LoginModel();
        $this->siswaModel = new SiswaModel();
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
                'message' => 'Password is incorrect',
                'isLoggedIn' => true
            ])->setStatusCode(401);
        }

        // Ambil data siswa berdasarkan id_login
        $siswa = $this->siswaModel->where('id_login', $user['id_login'])->first();

        if (!$siswa) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data siswa not found'
            ])->setStatusCode(404);
        }


        // Atur session
        session_start();
        $_SESSION['isLoggedIn'] = true;
        $_SESSION['id_login'] = $user['id_login'];

        // Login berhasil, kembalikan data termasuk role
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Login success',
            'data' => [
                'username' => $user['username'],
                'full_name' => $siswa['full_name'],
                'image' => $siswa['image'],
                'role' => $user['role']
            ]
        ]);
    }

    public function getUserData()
    {
        session_start();
        if (!isset($_SESSION['isLoggedIn']) || !$_SESSION['isLoggedIn']) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User not logged in'
            ])->setStatusCode(401);
        }

        // Ambil data siswa berdasarkan id_login
        $siswa = $this->siswaModel->where('id_login', $_SESSION['id_login'])->first();

        if (!$siswa) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data siswa not found'
            ])->setStatusCode(404);
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => [
                'full_name' => $siswa['full_name'],
                'image' => $siswa['image'],
            ],
        ]);
    }

    public function logout()
    {
        // Hapus session
        session()->destroy();

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Logout success'
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
