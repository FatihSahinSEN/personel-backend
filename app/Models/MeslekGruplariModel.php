<?php

namespace App\Models;

use CodeIgniter\Model;

class MeslekGruplariModel extends Model
{
    protected $table = 'meslekler';
    protected $primaryKey = 'id';
    protected $allowedFields = ['meslek','aciklama'];
}