<?php
/**
 * @Name Auth Controller
 * @Author Fatih ŞEN
 * @URL(https://github.com/FatihSahinSEN)
 * Authorization: Bearer BLA.BLA.BLA
 */

namespace App\Controllers;


use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\KullanicilarModel;

/**
 * Class Auth
 * @package App\Controllers
 */
class Auth extends ResourceController
{
    /**
     * @var string
     * Access Token Şifreleme anahtarı
     */
    private $AccessTokenSecret = 'ABCDEF';

    /**
     * @var string
     * Refresh Token Şifreleme anahtarı
     */
    private $RefreshTokenSecret = 'KLAMSD';

    /**
     * @var int
     * Access Token Geçerlilik süresi saniye
     */
    private $AccessTokenExpired = 500;

    /**
     * @var int
     * RefreshToken Geçerlilik süresi saniye
     */
    private $RefreshTokenExpired = 1200;

    use ResponseTrait;

    /**
     * @return \CodeIgniter\HTTP\Response
     * Login Metodu
     */
    public function login(){
        $data = $this->request->getJSON(true);
        if(!isset($data["request"])) return false;
        $data = $data["request"];
        $KullanicilarModel = new KullanicilarModel();
        $Control = $KullanicilarModel
            ->where("kullanici_adi",$data["kullanici_adi"])
            ->where("sifre",sha1("KLdAdsSW".$data["sifre"]."1AdsW85!x"))
            ->first();

        if($Control){
            unset($Control['sifre']);
            $Token = $this->createToken($Control);
                $data = array(
                    "status" => true,
                    "code" => 200,
                    "message" => "OK",
                    "access_token" => $Token["access_token"],
                    "refresh_token" => $Token["refresh_token"],
                    "user" => $Control
                );
        }else{
            $data = array(
                "status" => false,
                "code" => 200,
                "message" => "KULLANICI_ADI_SIFRE_GECERSIZ"
            );
        }
        return $this->respond($data,$data['code']);
    }

    /**
     * @return \CodeIgniter\HTTP\Response
     * Refresh Metodu
     * Refresh Token ile Yeni token almamızı sağlayacak
     */
    public function Refresh(){
        $Request = $this->request->getJSON(true);
        $RefreshToken = $Request["token"];
        $TokenControl = $this->TokenControl($RefreshToken,"Refresh");
        if($TokenControl["status"]){
            $getTokenData = $this->TokenData();
            $KullanicilarModel = new KullanicilarModel();
            $Control = $KullanicilarModel->where('id',$getTokenData['id'])->first();
            if($Control){
                unset($Control['sifre']);
                $Token = $this->createToken($Control);
                $data = array(
                    "status" => true,
                    "code" => 200,
                    "message" => "OK",
                    "access_token" => $Token["access_token"],
                    "refresh_token" => $Token["refresh_token"],
                    "user" => $Control
                );
            }else{
                $data = array(
                    "status" => false,
                    "code" => 200,
                    "message" => "KULLANICI_ADI_SIFRE_GECERSIZ"
                );
            }
            return $this->respond($data,$data['code']);
        }
    }

    /**
     * @param $jwt
     * @param string $tokenType
     * @return array
     * Token Doğrulama
     */
    private function TokenControl($jwt,$tokenType="Access"){
        if($tokenType=="Access"){
            $SecretKey = $this->AccessTokenSecret;
        }else{
            $SecretKey = $this->RefreshTokenSecret;
        }
        if(strstr($jwt, ".")){
            $tokenParts = explode('.', $jwt);
            $header = base64_decode($tokenParts[0]);
            $payload = base64_decode($tokenParts[1]);
            $signature_provided = $tokenParts[2];

            // check the expiration time - note this will cause an error if there is no 'exp' claim in the jwt
            $expiration = json_decode($payload)->exp;
            $is_token_expired = ($expiration - time()) < 0;

            // build a signature based on the header and payload using the secret
            $base64_url_header = $this->base64url_encode($header);
            $base64_url_payload = $this->base64url_encode($payload);
            $signature = hash_hmac('SHA256', $base64_url_header . "." . $base64_url_payload, $SecretKey, true);
            $base64_url_signature = $this->base64url_encode($signature);

            // verify it matches the signature provided in the jwt
            $is_signature_valid = ($base64_url_signature === $signature_provided);

            if ($is_token_expired) {
                return array("status"=> false,"message" => "Bearer token is expired");
            }elseif(!$is_signature_valid) {
                return array("status"=> false,"message" => "Bearer token is invalid");
            } else {
                return array("status"=> true,"message" => "Bearer token is valid");
            }
        }else{
            return array("status"=> false,"message" => "Bearer required");
        }

    }

    /**
     * @return bool
     * Middleware Control
     */
    public function isLogged(){
        $token = $this->getBearerToken();
        $TokenControl = $this->TokenControl($token);
        return $TokenControl;
    }

    /**
     * @param $headers
     * @param $payload
     * @return string
     * Access Token Oluşturma Methodu
     */
    private function GenerateAccessToken($headers, $payload) {
        $headers_encoded = $this->base64url_encode(json_encode($headers));
        $payload_encoded = $this->base64url_encode(json_encode($payload));
        $signature = hash_hmac('SHA256', "$headers_encoded.$payload_encoded", $this->AccessTokenSecret, true);
        $signature_encoded = $this->base64url_encode($signature);
        $jwt = "$headers_encoded.$payload_encoded.$signature_encoded";
        return $jwt;
    }

    /**
     * @param $str
     * @return string
     * Token Oluşturma için kullanılan base64 şifreleme
     */
    private function base64url_encode($str) {
        return rtrim(strtr(base64_encode($str), '+/', '-_'), '=');
    }

    /**
     * @param $headers
     * @param $payload
     * @return string
     * Refresh Token oluşturma Methodu
     */
    private function GenerateRefreshToken($headers, $payload) {
        $headers_encoded = $this->base64url_encode(json_encode($headers));
        $payload_encoded = $this->base64url_encode(json_encode($payload));
        $signature = hash_hmac('SHA256', "$headers_encoded.$payload_encoded", $this->RefreshTokenSecret, true);
        $signature_encoded = $this->base64url_encode($signature);
        $jwt = "$headers_encoded.$payload_encoded.$signature_encoded";
        return $jwt;
    }

    /**
     * @param $userId
     * @return array
     * @GenerateAccessToken ve @GenerateRefreshToken Methodlarını kullanarak
     * Token bilgisini geri döndüren method
     */
    private function createToken($userId){
        $headers = array('alg'=>'HS256','typ'=>'JWT');
        $payload = array(
            "iss" => $_SERVER["HTTP_HOST"],
            "aud" => $_SERVER["HTTP_HOST"],
            "exp" =>(time() + $this->AccessTokenExpired),
            "user" => $userId
        );
        $AccessToken = $this->GenerateAccessToken($headers, $payload);
        $payload["exp"]=(time()+$this->RefreshTokenExpired);
        $RefreshToken = $this->GenerateRefreshToken($headers, $payload);
        $data = array(
            "access_token" => $AccessToken,
            "refresh_token" => $RefreshToken
        );
        return $data;
    }

    /**
     * @return string|null
     * Http Header üzerinden gelen @Authorization:
     * Bilgisini almak için kullanılan method.
     */
    private function getAuthorizationHeader(){
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        }
        else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            //print_r($requestHeaders);
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }

    /**
     * @return mixed|null
     * @getAuthorizationHeader Methodunu kullanarak
     * Tarayıcı üzerinden gelen Token bilgisini alacaktır.
     */
    private function getBearerToken() {
        $headers = $this->getAuthorizationHeader();
        // HEADER: Get the access token from the header
        if (!empty($headers)) {
            if (preg_match('/Bearer\s((.*)\.(.*)\.(.*))/', $headers, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }

    /**
     * @return bool|mixed
     * Herkese açık token bilgisini almak için kullanılan method.
     */
    public function TokenData(){
        $token = $this->getBearerToken();
        $tokenExplode = explode(".", $token);
        if(!isset($tokenExplode[1])){
            return false;
        }

        $data = json_decode(base64_decode($tokenExplode[1]), true);

        if(!isset($data["user"])){
            return false;
        }

        return $data["user"];
    }
}