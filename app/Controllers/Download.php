<?php


namespace App\Controllers;


use App\Models\DosyalarModel;
use CodeIgniter\API\ResponseTrait;
use ZipArchive;

class Download extends BaseController
{
    use ResponseTrait;

    public function AllFiles($id){
        $db = new DosyalarModel();

        $veri = $db->where('dosyalar.status',1)->where('dosyalar.personel_no', $id)->findAll();
        if($veri){
            $zipname = $id.'.zip';
            $zip = new ZipArchive;
            $dir = $veri[0]['dosya_yolu'];
            if(file_exists($dir.$zipname)){
                unlink($dir.$zipname);
            }
            $zip->open($dir.$zipname, ZipArchive::CREATE);
            if ($handle = opendir($dir)) {
                while (false !== ($entry = readdir($handle))) {
                    if ($entry != "." && $entry != ".." && !strstr($entry,'.zip')) {
                        $zip->addFile($dir.$entry, $entry);
                    }
                }
                closedir($handle);
            }
            $zip->close();

            $response = [
                'status' => true,
                'code' => 200,
                'result' => $id.'/'.$zipname,
                'message' => 'MSG_BASARILI',
            ];
            return $this->respond($response,$response['code']);
        }
    }
    public function Single($id){
        $db = new DosyalarModel();

        $veri = $db->find($id);
        if($veri) {
            $dir = $veri['dosya_yolu'];
            $file = $veri['dosya'];
            $filename = $veri['dosya_adi'];
            if (file_exists($dir.$file)) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header("Content-Length: " . filesize($dir.$file));
                header('Content-Disposition: attachment; filename="'.basename($filename).'"');

                readfile ($dir.$file);
                exit();
            }
        }
    }
}