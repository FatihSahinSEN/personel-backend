<?php

namespace App\Models;

use CodeIgniter\Model;

class PersonelModel extends Model
{
    protected $table = 'personal';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'personel_no',
        'isim',
        'soyisim',
        'dogum_tarihi',
        'dogum_yeri',
        'posta_kodu_id',
        'ulke_id',
        'cadde',
        'meslek_id',
        'ise_giris_tarihi',
        'sigorta_sirketi_id',
        'kimlik_no',
        'sosyal_guvenlik_no',
        'uyruk_id',
        'telefon',
        'kimlik_seri_no',
        'kimlik_gecerlilik_tarihi',
        'pasaport_no',
        'pasaport_gecerlilik_tarihi',
        'oturum_izin_no',
        'oturum_izin_tarihi',
        'eposta',
        'guvenlik_belgesi',
        'created'
    ];

}