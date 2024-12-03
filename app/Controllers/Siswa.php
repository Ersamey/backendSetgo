<?php

namespace App\Controllers;


class Siswa extends BaseController
{
    protected $kelasModel;
    protected $siswaModel;
    protected $mengikutiModel;
    protected $guruModel;

    public function __construct()
    {
        $this->kelasModel = new \App\Models\KelasModel();
        $this->siswaModel = new \App\Models\SiswaModel();
        $this->mengikutiModel = new \App\Models\MengikutiModel();
        $this->guruModel = new \App\Models\GuruModel();
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

        // Get siswa data
        $siswa = $this->siswaModel->where('id_login', $_SESSION['id_login'])->first();
        if (!$siswa) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Siswa not found',
            ])->setStatusCode(404);
        }

        // Get kelas data
        $mengikuti = $this->mengikutiModel->where('id_siswa', $siswa['id_siswa'])->findAll();
        $kelas = [];
        foreach ($mengikuti as $m) {
            $kelasData = $this->kelasModel->find($m['id_kelas']);
            if ($kelasData) {
                $guru = $this->guruModel->find($kelasData['id_guru']);
                $kelas[] = [
                    'id_kelas' => $kelasData['id_kelas'],
                    'nama_kelas' => $kelasData['nama_kelas'],
                    'kode_kelas' => $kelasData['kode_kelas'],
                    'guru' => $guru['full_name'] ?? 'Guru tidak ditemukan',
                ];
            }
        }

        // Return JSON response
        return $this->response->setJSON([
            'success' => true,
            'data' => $kelas,
        ]);
    }

    public function join()
    {
        return view('join');
    }

    public function addClass()
    {
        session_start();
        if (!isset($_SESSION['isLoggedIn']) || !$_SESSION['isLoggedIn']) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User not logged in'
            ])->setStatusCode(401);
        }

        // Get siswa data
        $siswa = $this->siswaModel->where('id_login', $_SESSION['id_login'])->first();
        if (!$siswa) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Siswa not found',
            ])->setStatusCode(404);
        }

        // Get POST data
        $kode_kelas = $this->request->getVar('kode_kelas');


        if (!$kode_kelas) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Kode kelas tidak boleh kosong',
            ])->setStatusCode(400);
        }

        // Get kelas data
        $kelas = $this->kelasModel->where('kode_kelas', $kode_kelas)->first();
        if (!$kelas) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Kelas not found',
            ])->setStatusCode(404);
        }

        // Check if siswa already joined the class
        $mengikuti = $this->mengikutiModel->where('id_siswa', $siswa['id_siswa'])->where('id_kelas', $kelas['id_kelas'])->first();
        if ($mengikuti) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Siswa already joined the class',
            ])->setStatusCode(400);
        }

        // Add siswa to class
        $this->mengikutiModel->save([
            'id_siswa' => $siswa['id_siswa'],
            'id_kelas' => $kelas['id_kelas'],
        ]);

        // Return JSON response
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Siswa berhasil bergabung ke kelas',
        ]);
    }
}
