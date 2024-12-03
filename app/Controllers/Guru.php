<?php

namespace App\Controllers;


class Guru extends BaseController
{
    protected $kelasModel;
    protected $guruModel;

    public function __construct()
    {
        $this->kelasModel = new \App\Models\KelasModel();
        $this->guruModel = new \App\Models\GuruModel();
    }

    public function index()
    {
        return view('guru');
    }

    public function kelas()
    {
        session_start();
        if (!isset($_SESSION['isLoggedIn']) || !$_SESSION['isLoggedIn']) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User not logged in'
            ])->setStatusCode(401);
        }

        // Get guru data
        $guru = $this->guruModel->where('id_login', $_SESSION['id_login'])->first();
        if (!$guru) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Guru not found',
            ])->setStatusCode(404);
        }

        // Get kelas data
        $kelas = $this->kelasModel->where('id_guru', $guru['id_guru'])->findAll();

        // Return JSON response
        return $this->response->setJSON([
            'success' => true,
            'data' => $kelas,
        ]);
    }
}
