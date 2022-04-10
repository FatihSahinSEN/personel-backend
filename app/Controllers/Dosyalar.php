<?php

namespace App\Controllers;

use App\Models\DosyalarModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;


class Dosyalar extends ResourceController
{
    use ResponseTrait;

    /**
     * @var DosyalarModel
     */
    protected $model;

    protected $uploadDir = 'upload';

    protected $file;

    protected $allowedExtension = ["jpeg","jpg","png","pdf","xlsx","docx","doc"];

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
        "evrak_tip_id" => [
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
        $this->model = new DosyalarModel();
    }

    /**
     * @return \CodeIgniter\HTTP\Response|mixed
     */
    public function index(){
        $data = $this->model
            ->select("
                dosyalar.*,
                evrak_tipleri.isim as evrak,
                evrak_tipleri.grup as evrak_grup,
                personal.isim as personel_isim,
                personal.soyisim as personel_soyisim,
                DATE_FORMAT(dosyalar.bitis_tarihi, '%d.%m.%Y') as bitis_tarihi,
                DATE_FORMAT(dosyalar.created, '%d.%m.%Y %H.%i.%s') as created")
            ->join('evrak_tipleri', 'evrak_tipleri.id = dosyalar.evrak_tip_id', 'left outer')
            ->join('personal', 'personal.personel_no = dosyalar.personel_no', 'left outer')
            ->where('dosyalar.status',1)->findAll();
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
                "message" => "ERR_BURALAR_HENUZ_COK_ISSIZ",
            ];
        }
        return $this->respond($response,$response["code"]);
    }

    public function upload(){
        if(!isset($_POST['personel_no']) && !isset($_POST['evrak_tip_id'])) {
            $response = [
                "status" => false,
                "message" => 'ERR_PERSONEL_NO_GEREKLI',
                "code" => 200,
            ];
            return $response;
        }
        $personel_no = $_POST['personel_no'];
        $evrak_tip_id = $_POST['evrak_tip_id'];
        $bitis_tarihi = $_POST['bitis_tarihi'];

        $uploadFolder = realpath('.').DIRECTORY_SEPARATOR.$this->uploadDir.DIRECTORY_SEPARATOR; // Klasörlerin oluşturulacağı Upload klasörü
        $uploadPath = $uploadFolder.$personel_no.DIRECTORY_SEPARATOR; // Personel'e ait klasör
        if(!file_exists($uploadFolder)){  // Upload Klasörü varmı kontrol ediyoruz.
            $UploadFolderCreate = mkdir($uploadFolder); // Upload klasörünü oluşturuyoruz
            if(!$UploadFolderCreate){ // Upload klasörü oluşturma sırasında sorun çıktımı kontrol ediyoruz.
                $response = [
                    "status" => false,
                    "message" => 'ERR_UPLOAD_KLASORU_YOK',
                    "code" => 200,
                ];
                return $response;
            }
        }
        if(!file_exists($uploadPath)){ // Personel klasörü varmı diye kontrol ediyoruz.
            $UploadPathCreate = mkdir($uploadPath); // Personel klasörü yok ise oluşturuyoruz.
            if(!$UploadPathCreate){  // Personel Klasörü oluşturuldumu diye kontrol ediyoruz.
                $response = [
                    "status" => false,
                    "message" => 'ERR_PERSONEL_KLASOR_OLUSTURULAMIYOR',
                    "code" => 200,
                ];
                return $response;
            }
        }

        $randname = $this->randstr()."_".$this->randstr()."_".time();
        if(isset($_FILES['files'])){ // files adında bir input varmı kontrol ediyoruz

            foreach ($_FILES as $key => $file){ // Dosyaları döngüye sokuyoruz.
                $dosya_adi      =   $file['name'];
                $dosya_boyutu   =   $file['size'];
                $gecici_yol     =   $file['tmp_name'];
                $dosta_tipi     =   $file['type'];
                $array = explode('.', $dosya_adi);
                $uzanti         =   strtolower(end($array));
            }
                if(in_array($uzanti,$this->allowedExtension)=== false){
                    $response = [
                        "status" => false,
                        "message" => 'ERR_DOSYA_UZANTISI_GECERSIZ',
                        "code" => 200,
                    ];
                    return $response;
                }

                if($dosya_boyutu > 2097152){
                    $response = [
                        "status" => false,
                        "message" => 'ERR_DOSYA_BOYUTU_COK_FAZLA',
                        "code" => 200,
                    ];
                    return $response;
                }
                $filename = $randname.'.'.$uzanti;
                $upload = move_uploaded_file($gecici_yol,$uploadPath.$filename);
                if($upload){
                 $response = [
                        'status' => true,
                        'dosya_adi' => $dosya_adi,
                        'dosya_boyutu' => $dosya_boyutu,
                        'dosya_uzantisi' => $uzanti,
                        'dosya_yolu' => $uploadPath,
                        'dosya' => $filename,
                        'personel_no' => $personel_no,
                        'evrak_tip_id' => $evrak_tip_id,
                        'bitis_tarihi' => $bitis_tarihi,
                     ];
                 return $response;
                }else{
                    $response = [
                        "status" => false,
                        "message" => 'ERR_DOSYA_TASIMA_HATASI',
                        "code" => 200,
                    ];
                    return $response;
                }

        }
    }

    /**
     * @return \CodeIgniter\HTTP\Response|mixed
     * @throws \ReflectionException
     */
    public function create()
    {
        $upload = $this->upload();
        if($upload['status']) {
            $data = $upload;
            if (!$this->validate($this->valid)) {
                $response = [
                    'status' => false,
                    'code' => 500,
                    'message' => $this->validator->getErrors(),
                ];
                return $this->respond($response, $response["code"]);
            }

            $Create = $this->model->insert($data);
            if ($Create) {
                $id = $this->model->getInsertID();
                $newData = $this->model->select('evrak_tipleri.isim as evrak,
                        personal.isim as personel_isim,
                        personal.soyisim as personel_soyisim,
                        dosyalar.*')
                    ->join('evrak_tipleri', 'evrak_tipleri.id = dosyalar.evrak_tip_id', 'left outer')
                    ->join('personal', 'personal.personel_no = dosyalar.personel_no', 'left outer')
                    ->find($id);
                $response = [
                    'status' => true,
                    'code' => 201,
                    'message' => 'MSG_DOSYA_YUKLEME_BASARILI',
                    'result' => $newData
                ];
                return $this->respond($response, $response["code"]);
            } else {
                return $this->failServerError('ERR_GUNCELLEME_YAPILAMADI');
            }
        }else{
            $response = $upload;
            return $this->respond($response, $response["code"]);
        }
    }

    /**
     * @param null $id
     * @return \CodeIgniter\HTTP\Response|mixed
     */
    public function show($id = null)
    {
        $data = $this->model
            ->select("
                dosyalar.*,
                evrak_tipleri.isim as evrak,
                evrak_tipleri.grup as evrak_grup,
                personal.isim as personel_isim,
                personal.soyisim as personel_soyisim,
                DATE_FORMAT(dosyalar.bitis_tarihi, '%d.%m.%Y') as bitis_tarihi,
                DATE_FORMAT(dosyalar.created, '%d.%m.%Y %H.%i.%s') as created")
            ->join('evrak_tipleri', 'evrak_tipleri.id = dosyalar.evrak_tip_id', 'left outer')
            ->join('personal', 'personal.personel_no = dosyalar.personel_no', 'left outer')
            ->where('dosyalar.status',1)->where('dosyalar.personel_no', $id)->findAll();
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
                "status" => false,
                "code" => 200,
                "message" => "ERR_BURALAR_HENUZ_COK_ISSIZ",
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
        $file = $this->model->where('id',$id)->first();
        $dosya = $file['dosya_yolu'].$file['dosya'];
        if(file_exists($dosya)){
            $sil = unlink($dosya);
            if($sil){
                $dbRemove = $this->model->delete($id);
                if($dbRemove){
                    $this->model->delete($id);
                    $response = [
                        'status'   => true,
                        'code'      => 200,
                        'error'    => null,
                        'message' => 'ERR_DOSYA_SILINDI'
                    ];
                    return $this->respond($response,$response['code']);
                }else{
                    $response = [
                        'status'   => false,
                        'code'      => 200,
                        'error'    => null,
                        'message' => 'ERR_DOSYA_SILINDI_DATA_SILINEMEDI'
                    ];
                    return $this->respond($response,$response['code']);
                }
            }else{
                $response = [
                    'status'   => false,
                    'code'      => 200,
                    'error'    => null,
                    'message' => 'ERR_DOSYA_SILINEMEDI'
                ];
                return $this->respond($response,$response['code']);
            }
        }else{
            $dbRemove = $this->model->delete($id);
            if($dbRemove) {
                $this->model->delete($id);
                $response = [
                    'status' => true,
                    'code' => 200,
                    'error' => null,
                    'message' => 'ERR_DOSYA_SILINDI'
                ];
                return $this->respond($response, $response['code']);
            }else {
                $response = [
                    'status' => false,
                    'code' => 200,
                    'error' => null,
                    'message' => 'ERR_DOSYA_BULUNAMADI'
                ];
                return $this->respond($response, $response['code']);
            }
        }
    }

    private function randstr($len=10, $abc="aAbBcCdDeEfFgGhHiIjJkKlLmMnNoOpPqQrRsStTuUvVwWxXyYzZ") {
            $letters = str_split($abc);
            $str = "";
            for ($i=0; $i<=$len; $i++) {
                $str .= $letters[rand(0, count($letters)-1)];
            };
        return $str;
    }

}