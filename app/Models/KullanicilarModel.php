<?php

namespace App\Models;

use CodeIgniter\Model;

class KullanicilarModel extends Model
{
    protected $table = 'kullanicilar';
    protected $primaryKey = 'id';
    protected $allowedFields = ['kullanici_adi','sifre','isim','soyisim','yetki','created','status'];

}