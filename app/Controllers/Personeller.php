<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;


class Personeller extends ResourceController
{
    use ResponseTrait;

    /**
     * @var \App\Models\PersonelModel
     */
    protected $model;
    /**
     * @var array[]
     */

    protected $valid = [
        "personel_no" => [
            "rules" => "required",
            "errors" => [
                    "required" => "ERR_GEREKLI",
                ],
            ],
        "isim" => [
            "rules" => "required",
            "errors" => [
                    "required" => "ERR_GEREKLI",
                ],
            ],
        "soyisim" => [
            "rules" => "required",
            "errors" => [
                    "required" => "ERR_GEREKLI",
                ],
            ],
        "dogum_tarihi" => [
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
        $this->model = new \App\Models\PersonelModel();
    }


    /**
     * @return \CodeIgniter\HTTP\Response|mixed
     */
    public function index(){
        $data = $this->model
            ->select("
                        personal.*,
                        ulkeler.de as ulke_de,
                        ulkeler.en as ulke_en,
                        uyruklar.uyruk as uyruk,
                        sigorta_sirketleri.isim as sigorta_sirketi,
                        posta_kodlari.posta_kodu as posta_kodu,
                        posta_kodlari.sehir as sehir,
                        meslekler.meslek as meslek,
                        DATE_FORMAT(personal.dogum_tarihi, '%d.%m.%Y') as dogum_tarihi,
                        DATE_FORMAT(personal.ise_giris_tarihi, '%d.%m.%Y') as ise_giris_tarihi,
                        DATE_FORMAT(personal.kimlik_gecerlilik_tarihi, '%d.%m.%Y') as kimlik_gecerlilik_tarihi,
                        DATE_FORMAT(personal.pasaport_gecerlilik_tarihi, '%d.%m.%Y') as pasaport_gecerlilik_tarihi,
                        DATE_FORMAT(personal.oturum_izin_tarihi, '%d.%m.%Y') as oturum_izin_tarihi,
                        DATE_FORMAT(personal.created, '%d.%m.%Y %H.%i.%s') as created
                        ")
            ->join('meslekler', 'meslekler.id = personal.meslek_id', 'left outer')
            ->join('posta_kodlari', 'posta_kodlari.id = personal.posta_kodu_id', 'left outer')
            ->join('sigorta_sirketleri', 'sigorta_sirketleri.id = personal.sigorta_sirketi_id', 'left outer')
            ->join('uyruklar', 'uyruklar.id = personal.uyruk_id', 'left outer')
            ->join('ulkeler', 'ulkeler.id = personal.ulke_id', 'left outer')
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
                "code" => 500,
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
        $kontrol = $this->model->where('personel_no',$data["personel_no"])->first();
        if($kontrol){
            $response = [
                'status' => false,
                'code' => 200,
                'message' => 'ERR_PERSONEL_MEVCUT'
            ];
            return $this->respond($response,$response["code"]);
        }
        $Create = $this->model->insert($data);
        if ($Create) {
            $data['id'] = $this->model->getInsertID();
            $data['created'] = date('Y-m-d H:i:s');
            $response = [
                'status' => true,
                'code' => 201,
                'message' => 'MSG_PERSONEL_OLUSTURULDU',
                'result' => $data
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
        $data = $this->model->where('id',$id)->first();
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
                'message' => 'ERR_PERSONEL_BULUNAMADI',
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
                'message' => 'MSG_PERSONEL_SILINDI'
            ];
            return $this->respondDeleted($response);
        }else{
            return $this->failNotFound('ERR_PERSONEL_BULUNAMADI');
        }
    }
}