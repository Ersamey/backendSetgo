<?php

namespace App\Models;

use CodeIgniter\Model;

class GuruModel extends Model
{
    protected $table = 'guru';
    protected $primaryKey = 'id_guru';
    protected $allowedFields = ['id_login', 'full_name', 'image'];
}
