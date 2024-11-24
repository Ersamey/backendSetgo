<?php

namespace App\Models;

use CodeIgniter\Model;

class loginModel extends Model
{
    protected $table = 'login';
    protected $primaryKey = 'id_login';
    protected $allowedFields = ['username', 'password', 'role'];
    // protected $validationRules = [
    //     'username' => 'required|alpha_numeric_space|min_length[3]|max_length[255]',
    //     'password' => 'required|min_length[8]|max_length[255]',
    //     'role' => 'required|in_list[admin,member]'
    // ];
    // protected $validationMessages = [
    //     'username' => [
    //         'required' => 'Username
    //         tidak boleh kosong',
    //         'alpha_numeric_space' => 'Username hanya boleh berisi huruf, angka, dan spasi',
    //         'min_length' => 'Username minimal terdiri dari 3 karakter',
    //         'max_length' => 'Username maksimal terdiri dari 255 karakter'
    //     ],
    //     'password' => [
    //         'required' => 'Password tidak boleh kosong',
    //         'min_length' => 'Password minimal terdiri dari 8 karakter',
    //         'max_length' => 'Password maksimal terdiri dari 255 karakter'
    //     ],
    // ];
    // protected $skipValidation = false;
    // buat fungsi untuk insert data login

}
