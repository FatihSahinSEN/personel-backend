<?php

namespace App\Controllers;

use App\Models\SozlesmelerModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;


class Sozlesmeler extends ResourceController
{
    use ResponseTrait;

    /**
     * @var SozlesmelerModel
     */
    protected $model;
    /**
     * @var array[]
     */
    protected $valid = [
        "firma_id" => [
            "rules" => "required",
            "errors" => [
                "required" => "ERR_GEREKLI",
            ],
        ],
        "personel_id" => [
            "rules" => "required",
            "errors" => [
                "required" => "ERR_GEREKLI",
            ],
        ],
        "baslama_tarihi" => [
            "rules" => "required",
            "errors" => [
                "required" => "ERR_GEREKLI",
            ],
        ],
        "ucret_grubu" => [
            "rules" => "required",
            "errors" => [
                "required" => "ERR_GEREKLI",
            ],
        ],
        "saat_ucreti" => [
            "rules" => "required",
            "errors" => [
                "required" => "ERR_GEREKLI",
            ],
        ],
        "ek_ucret" => [
            "rules" => "required",
            "errors" => [
                "required" => "ERR_GEREKLI",
            ],
        ],
        "imza_tarihi" => [
            "rules" => "required",
            "errors" => [
                "required" => "ERR_GEREKLI",
            ],
        ],
    ];

    /**
     * Evrak Tipi constructor.
     */
    public function __construct()
    {
        $this->model = new SozlesmelerModel();
    }


    /**
     * @return \CodeIgniter\HTTP\Response|mixed
     */
    public function index(){
        $data = $this->model->select("
        sozlesmeler.id as id,
        sozlesmeler.baslama_tarihi as baslama_tarihi,
        sozlesmeler.ucret_grubu as ucret_grubu,
        sozlesmeler.saat_ucreti as saat_ucreti,
        sozlesmeler.ek_ucret as ek_ucret,
        sozlesmeler.imza_tarihi as imza_tarihi,
        sozlesmeler.firma_id as firma_id,
        sozlesmeler.personel_id as personel_id,
        personal.isim as isim,
        personal.soyisim as soyisim,
        personal.cadde as personel_cadde,
        meslekler.meslek as meslek,
        firma.adi as firma,
        firma.cadde as firma_cadde,
        firma.postakodu_sehir as firma_sehir,
        posta_kodlari.posta_kodu as personel_postakodu,
        posta_kodlari.sehir as personel_sehir,
        DATE_FORMAT(sozlesmeler.baslama_tarihi, '%d.%m.%Y') as baslama_tarihi,
        DATE_FORMAT(sozlesmeler.imza_tarihi, '%d.%m.%Y') as imza_tarihi,
        DATE_FORMAT(sozlesmeler.created, '%d.%m.%Y %H.%i.%s') as created")
            ->join('personal', 'sozlesmeler.personel_id = personal.id', 'left outer')
            ->join('firma', 'sozlesmeler.firma_id = firma.id', 'left outer')
            ->join('posta_kodlari', 'personal.posta_kodu_id = posta_kodlari.id', 'left outer')
            ->join('meslekler', 'personal.meslek_id = meslekler.id', 'left outer')
            ->findAll();
        if($data){
            $response = [
                "status" => true,
                "code" => 200,
                "message" => "MSG_LISTELEME_BAŞARILI",
                "result" => $data
            ];
        }else{
            $response = [
                "status" => false,
                "code" => 200,
                "message" => "ERR_SUNUCU_HATASI",
            ];
        }
        return $this->respond($response,$response["code"]);
    }

    /**
     * @return \CodeIgniter\HTTP\Response|mixed
     * @throws \ReflectionException
     */
    public function create()
    {
        $data = $this->request->getJSON(true);
        $_POST = $data;
        $_REQUEST = $data;
        if (!$this->validate($this->valid)) {
            $response = [
                'status' => false,
                'code' => 200,
                'message' => $this->validator->getErrors(),
            ];
            return $this->respond($response,$response["code"]);
        }
        $Create = $this->model->insert($data);
        if ($Create) {
            $id = $this->model->getInsertID();
            $veri = $this->model->select("
        sozlesmeler.id as id,
        sozlesmeler.baslama_tarihi as baslama_tarihi,
        sozlesmeler.ucret_grubu as ucret_grubu,
        sozlesmeler.saat_ucreti as saat_ucreti,
        sozlesmeler.ek_ucret as ek_ucret,
        sozlesmeler.imza_tarihi as imza_tarihi,
        sozlesmeler.firma_id as firma_id,
        sozlesmeler.personel_id as personel_id,
        personal.isim as isim,
        personal.soyisim as soyisim,
        personal.cadde as personel_cadde,
        meslekler.meslek as meslek,
        firma.adi as firma,
        firma.cadde as firma_cadde,
        firma.postakodu_sehir as firma_sehir,
        posta_kodlari.posta_kodu as personel_postakodu,
        posta_kodlari.sehir as personel_sehir,
        DATE_FORMAT(sozlesmeler.baslama_tarihi, '%d.%m.%Y') as baslama_tarihi,
        DATE_FORMAT(sozlesmeler.imza_tarihi, '%d.%m.%Y') as imza_tarihi,
        DATE_FORMAT(sozlesmeler.created, '%d.%m.%Y %H.%i.%s') as created")
                ->join('personal', 'sozlesmeler.personel_id = personal.id', 'left outer')
                ->join('firma', 'sozlesmeler.firma_id = firma.id', 'left outer')
                ->join('posta_kodlari', 'personal.posta_kodu_id = posta_kodlari.id', 'left outer')
                ->join('meslekler', 'personal.meslek_id = meslekler.id', 'left outer')->find($id);
            $response = [
                'status' => true,
                'code' => 201,
                'message' => 'MSG_SOZLESME_OLUSTURULDU',
                'result' => $veri
            ];
            return $this->respond($response,$response["code"]);
        } else {
            return $this->failServerError('ERR_GUNCELLEME_YAPILAMADI');
        }
    }

    /**
     * @param null $id
     * @return \CodeIgniter\HTTP\Response|mixed
     */
    public function show($id = null)
    {
        $data = $this->model->select("
        sozlesmeler.id as id,
        sozlesmeler.baslama_tarihi as baslama_tarihi,
        sozlesmeler.ucret_grubu as ucret_grubu,
        sozlesmeler.saat_ucreti as saat_ucreti,
        sozlesmeler.ek_ucret as ek_ucret,
        sozlesmeler.imza_tarihi as imza_tarihi,
        sozlesmeler.firma_id as firma_id,
        sozlesmeler.personel_id as personel_id,
        personal.isim as isim,
        personal.soyisim as soyisim,
        personal.cadde as personel_cadde,
        meslekler.meslek as meslek,
        firma.adi as firma,
        firma.cadde as firma_cadde,
        firma.postakodu_sehir as firma_sehir,
        posta_kodlari.posta_kodu as personel_postakodu,
        posta_kodlari.sehir as personel_sehir,
        DATE_FORMAT(sozlesmeler.baslama_tarihi, '%d.%m.%Y') as baslama_tarihi,
        DATE_FORMAT(sozlesmeler.imza_tarihi, '%d.%m.%Y') as imza_tarihi,
        DATE_FORMAT(sozlesmeler.created, '%d.%m.%Y %H.%i.%s') as created")
            ->join('personal', 'sozlesmeler.personel_id = personal.id', 'left outer')
            ->join('firma', 'sozlesmeler.firma_id = firma.id', 'left outer')
            ->join('posta_kodlari', 'personal.posta_kodu_id = posta_kodlari.id', 'left outer')
            ->join('meslekler', 'personal.meslek_id = meslekler.id', 'left outer')->find($id);
        if($data){
            $response = [
                'status' => true,
                'code'   => 200,
                'message' => 'OK',
                'result' => $data
            ];
            return $this->respond($response);
        }else{
            $response = [
                'status' => false,
                'code'   => 200,
                'message' => 'ERR_SOZLESME_BULUNAMADI',
            ];
            return $this->respond($response,$response['code']);
        }
    }

    /**
     * @param null $id
     * @return \CodeIgniter\HTTP\Response|mixed
     * @throws \ReflectionException
     */
    public function update($id = null)
    {
        $data = $this->request->getJSON(true);
        $Update  = $this->model->update($id, $data);
        if($Update){
            $response = [
                'status' => true,
                'code'   => 200,
                'message' => 'MSG_GUNCELLENDI'
            ];
            return $this->respond($response);
        }else{
            return $this->failServerError('ERR_GUNCELLEME_YAPILAMADI');
        }

    }

    /**
     * @param null $id
     * @return \CodeIgniter\HTTP\Response|mixed
     */
    public function delete($id = null)
    {
        $data = $this->model->where('id', $id)->delete($id);
        if($data){
            $this->model->delete($id);
            $response = [
                'status'   => 200,
                'error'    => null,
                'message' => 'ERR_SOZLESME_SILINDI'
            ];
            return $this->respondDeleted($response);
        }else{
            return $this->failNotFound('ERR_SOZLESME_BULUNAMADI');
        }
    }
}