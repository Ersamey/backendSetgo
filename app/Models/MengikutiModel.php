<?php

namespace App\Models;

use CodeIgniter\Model;

class MengikutiModel extends Model
{
    protected $table = 'mengikuti';
    protected $allowedFields = ['id_siswa', 'id_kelas'];
}
