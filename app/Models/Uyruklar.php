<?php

namespace App\Models;

use CodeIgniter\Model;

class Uyruklar extends Model
{
    protected $table = 'uyruklar';
    protected $primaryKey = 'id';
    protected $allowedFields = ['uyruk','created'];
}