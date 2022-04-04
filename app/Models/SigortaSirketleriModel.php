<?php

namespace App\Models;

use CodeIgniter\Model;

class SigortaSirketleriModel extends Model
{
    protected $table = 'sigorta_sirketleri';
    protected $primaryKey = 'id';
    protected $allowedFields = ['isim','created'];
}