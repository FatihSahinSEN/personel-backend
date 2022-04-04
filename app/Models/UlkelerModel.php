<?php

namespace App\Models;

use CodeIgniter\Model;

class UlkelerModel extends Model
{
    protected $table = 'ulkeler';
    protected $primaryKey = 'id';
    protected $allowedFields = ['code','de','en'];
}