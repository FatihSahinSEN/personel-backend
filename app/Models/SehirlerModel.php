<?php

namespace App\Models;

use CodeIgniter\Model;

class SehirlerModel extends Model
{
    protected $table = 'posta_kodlari';
    protected $primaryKey = 'id';
    protected $allowedFields = ['posta_kodu','sehir'];
}