<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\KullanicilarModel;


class Kullanicilar extends ResourceController
{
    use ResponseTrait;

    /**
     * @var KullanicilarModel
     */
    protected $model;
    /**
     * @var array[]
     */
    protected $valid = [
          "kullanici_adi" => [
              "rules" => "required|min_length[3]",
              "errors" => [
                  "required" => "ERR_GEREKLI",
                  "min_length" => "ERR_COKKISA_3"
              ],
          ],
          "sifre" => [
              "rules" => "required|min_length[5]",
              "errors" => [
                  "required" => "ERR_GEREKLI",
                  "min_length" => "ERR_COKKISA_5"
                ],
              ],
          "isim" => [
              "rules" => "required|min_length[3]",
              "errors" => [
                  "required" => "ERR_GEREKLI",
                  "min_length" => "ERR_COKKISA_3"
              ],
          ],
          "soyisim" => [
              "rules" => "required|min_length[3]",
              "errors" => [
                  "required" => "ERR_GEREKLI",
                  "min_length" => "ERR_COKKISA_3"
              ],
          ],
    ];


    /**
     * Kullanicilar constructor.
     */
    public function __construct()
    {
        $this->model = new KullanicilarModel();
    }


    /**
     * @return \CodeIgniter\HTTP\Response|mixed
     */
    public function index(){
        $data = $this->model->where('status', 1)->findAll();
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
        $kontrol = $this->model->where('kullanici_adi',$data["kullanici_adi"])->first();
        if($kontrol){
            $response = [
                'status' => false,
                'code' => 200,
                'message' => 'ERR_KULLANICI_MEVCUT'
            ];
            return $this->respond($response,$response["code"]);
        }
        $data['sifre'] = sha1("KLdAdsSW".$data["sifre"]."1AdsW85!x");
        $Create = $this->model->insert($data);
        if ($Create) {
            $data['id'] = $this->model->getInsertID();
            $data['created'] = date('Y-m-d H:i:s');
            unset($data['sifre']);
            $response = [
                'status' => true,
                'code' => 201,
                'message' => 'MSG_KULLANICI_OLUSTURULDU',
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
                'message' => 'ERR_KULLANICI_BULUNAMADI',
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
        if($data['status']) {
            $data['status'] = 1;
        }else{
            $data['status'] = 0;
        }
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
                'message' => 'MSG_KULLANICI_SILINDI'
            ];
            return $this->respondDeleted($response);
        }else{
            return $this->failNotFound('ERR_KULLANICI_BULUNAMADI');
        }
    }


    public function login(){

    }
}