<?php

namespace App\Models;

use CodeIgniter\Model;

class SozlesmelerModel extends Model
{
    protected $table = 'sozlesmeler';
    protected $primaryKey = 'id';
    protected $allowedFields = ['firma_id','personel_id','baslama_tarihi','ucret_grubu','saat_ucreti','ek_ucret','imza_tarihi','created'];
}