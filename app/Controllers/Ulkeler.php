<?php

namespace App\Controllers;

use App\Models\UlkelerModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;


class Ulkeler extends ResourceController
{
    use ResponseTrait;

    /**
     * @var UlkelerModel
     */
    protected $model;
    /**
     * @var array[]
     */
    protected $valid = [
        "code" => [
            "rules" => "required|min_length[1]",
            "errors" => [
                "required" => "ERR_GEREKLI",
                "min_length" => "ERR_COKKISA_2"
            ],
        ],
        "de" => [
            "rules" => "required|min_length[3]",
            "errors" => [
                "required" => "ERR_GEREKLI",
                "min_length" => "ERR_COKKISA_3"
            ],
        ],
    ];

    /**
     * Evrak Tipi constructor.
     */
    public function __construct()
    {
        $this->model = new UlkelerModel();
    }


    /**
     * @return \CodeIgniter\HTTP\Response|mixed
     */
    public function index(){
        $data = $this->model->findAll();
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
                'code' => 500,
                'message' => $this->validator->getErrors(),
            ];
            return $this->respond($response,$response["code"]);
        }
        $kontrol = $this->model->where('code',$data["code"])->first();
        if($kontrol){
            $response = [
                'status' => false,
                'code' => 200,
                'message' => 'ERR_ULKE_MEVCUT'
            ];
            return $this->respond($response,$response["code"]);
        }
        $Create = $this->model->insert($data);
        if ($Create) {
            $data['created'] = date('Y-m-d H:i:s');
            $data['id'] = $this->model->getInsertID();

            $response = [
                'status' => true,
                'code' => 201,
                'message' => 'MSG_ULKE_OLUSTURULDU',
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
                'message' => 'ERR_ULKE_BULUNAMADI',
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
                'message' => 'MSG_GUNCELLENDI',
                'result' => $data
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
       return $this->failNotFound('ERR_ULKE_SILINEMEZ');
    }

}