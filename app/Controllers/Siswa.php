<?php

namespace App\Controllers;


class Siswa extends BaseController
{
    protected $kelasModel;
    protected $siswaModel;
    protected $mengikutiModel;

    public function __construct()
    {
        $this->kelasModel = new \App\Models\KelasModel();
        $this->siswaModel = new \App\Models\SiswaModel();
        $this->mengikutiModel = new \App\Models\MengikutiModel();
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

        // ambil id siswa dari session lalu cari data kelas yang diikuti oleh siswa tersebut
        $siswa = $this->siswaModel->where('id_login', $_SESSION['id_login'])->first();
        $mengikuti = $this->mengikutiModel->where('id_siswa', $siswa['id_siswa'])->findAll();
        // ambil data kelas berdasarkan id_kelas yang diikuti oleh siswa
        $kelas = [];
        foreach ($mengikuti as $m) {
            $kelas[] = $this->kelasModel->find($m['id_kelas']);
        }
        // kembalikan data kelas
        return $this->response->setJSON([
            'success' => true,
            'data' => $kelas
        ]);
    }
}
