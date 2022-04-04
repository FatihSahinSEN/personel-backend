<?php

namespace App\Controllers;

use App\Models\SehirlerModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;


class PostaKodlari extends ResourceController
{
    use ResponseTrait;

    /**
     * @var SehirlerModel
     */
    protected $model;
    /**
     * @var array[]
     */
    protected $valid = [
        "posta_kodu" => [
            "rules" => "required|min_length[4]",
            "errors" => [
                "required" => "ERR_GEREKLI",
                "min_length" => "ERR_COKKISA_4"
            ],
        ],
        "sehir" => [
            "rules" => "required|min_length[4]",
            "errors" => [
                "required" => "ERR_GEREKLI",
                "min_length" => "ERR_COKKISA_4"
            ],
        ],
    ];

    /**
     * Evrak Tipi constructor.
     */
    public function __construct()
    {
        $this->model = new SehirlerModel();
    }


    /**
     * @return \CodeIgniter\HTTP\Response|mixed
     */
    public function index(){
        $data = $this->model->orderBy('sehir','asc')->findAll();
        if($data){
            $response = [
                "status" => true,
                "code" => 200,
                "message" => "MSG_LISTELEME_BAŞARILI",
                "count" => count($data),
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
                'code' => 500,
                'message' => $this->validator->getErrors(),
            ];
            return $this->respond($response,$response["code"]);
        }
        $kontrol = $this->model->where('posta_kodu',$data["posta_kodu"])->first();
        if($kontrol){
            $response = [
                'status' => false,
                'code' => 200,
                'message' => 'ERR_POSTA_KODU_MEVCUT'
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
                'message' => 'MSG_SEHIR_OLUSTURULDU',
                'result' => $data
            ];
            return $this->respond($response,$response["code"]);
        } else {
            return $this->failServerError('ERR_GUNCELLEME_YAPILAMADI');
        }
    }

    /**
     * @return \CodeIgniter\HTTP\Response
     */
    public function filter($filter = false){
        if($filter){
            $data = $this->model->like('posta_kodu',$filter)->orderBy('sehir','asc')->findAll();
            if($data){
                $response = [
                    "status" => true,
                    "code" => 200,
                    "message" => "OK",
                    "count" => count($data),
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
                'message' => 'ERR_SEHIR_BULUNAMADI',
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
                'message' => [
                    'success' => 'Şehir güncellendi.'
                ]
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
       return $this->failNotFound('Şehir Silinemez.');
    }

}