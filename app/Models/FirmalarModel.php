<?php

namespace App\Models;

use CodeIgniter\Model;

class FirmalarModel extends Model
{
    protected $table = 'firma';
    protected $primaryKey = 'id';
    protected $allowedFields = ['adi','cadde','postakodu_sehir'];
}