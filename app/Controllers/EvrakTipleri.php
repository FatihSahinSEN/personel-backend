<?php

namespace App\Controllers;

use App\Models\EvrakTipleriModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;


class EvrakTipleri extends ResourceController
{
    use ResponseTrait;

    /**
     * @var EvrakTipleriModel
     */
    protected $model;
    /**
     * @var array[]
     */
    protected $valid = [
        "isim" => [
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
        $this->model = new EvrakTipleriModel();
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
        $kontrol = $this->model->where('isim',$data["isim"])->first();
        if($kontrol){
            $response = [
                'status' => false,
                'code' => 200,
                'message' => 'ERR_EVRAK_TIPI_MEVCUT'
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
                'message' => 'ERR_EVRAK_TIPI_OLUSTURULDU',
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
                'message' => 'ERR_EVRAK_TIPI_BULUNAMADI',
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
                'message' => 'ERR_EVRAK_TIPI_SILINDI'
            ];
            return $this->respondDeleted($response);
        }else{
            return $this->failNotFound('ERR_EVRAK_TIPI_SILINEMEDI');
        }
    }

}