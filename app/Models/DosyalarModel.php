<?php

namespace App\Models;

use CodeIgniter\Model;

class DosyalarModel extends Model
{
    protected $table = 'dosyalar';
    protected $primaryKey = 'id';
    protected $allowedFields = ['dosya','dosya_adi','dosya_yolu','dosya_boyutu','dosya_uzantisi','personel_no','evrak_tip_id','status','created'];
}