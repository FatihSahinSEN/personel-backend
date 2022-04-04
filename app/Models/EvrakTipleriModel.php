<?php

namespace App\Models;

use CodeIgniter\Model;

class EvrakTipleriModel extends Model
{
    protected $table = 'evrak_tipleri';
    protected $primaryKey = 'id';
    protected $allowedFields = ['isim','aciklama','created'];
}