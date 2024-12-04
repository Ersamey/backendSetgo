<?php

namespace App\Controllers;


class Guru extends BaseController
{
    protected $kelasModel;
    protected $guruModel;
    protected $siswaModel;
    protected $mengikutModel;

    public function __construct()
    {
        $this->kelasModel = new \App\Models\KelasModel();
        $this->guruModel = new \App\Models\GuruModel();
        $this->siswaModel = new \App\Models\SiswaModel();
        $this->mengikutModel = new \App\Models\MengikutiModel();
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

    public function siswaKu()
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

        // Get siswa data dari id_siswa yang mengikuti id_kelas (pada tabel mengikuti) yang diajar oleh guru
        $siswa =  $this->siswaModel
            ->select('siswa.*')
            ->join('mengikuti', 'mengikuti.id_siswa = siswa.id_siswa')
            ->join('kelas', 'kelas.id_kelas = mengikuti.id_kelas')
            ->where('kelas.id_guru', $guru['id_guru'])
            ->findAll();

        // Return JSON response
        return $this->response->setJSON([
            'success' => true,
            'data' => $siswa,
        ]);
    }

    public function buatKelas()
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

        // Get request data
        $data = $this->request->getJSON();
        $namaKelas = $data->namaKelas;
        // Tabel kelas : id_kelas, id_guru, nama_kelas, kode_kelas
        // Insert kelas data
        $kelas = [
            'id_guru' => $guru['id_guru'],
            'nama_kelas' => $namaKelas,
            'kode_kelas' => strtoupper(substr(uniqid(), 0, 6)),
        ];
        $this->kelasModel->insert($kelas);

        // Return JSON response
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Kelas berhasil dibuat',
        ]);
    }
}
