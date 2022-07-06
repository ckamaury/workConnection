<?php

namespace CkAmaury\WorkConnection\Object;

use CkAmaury\PhpHtml\FormAnalyzer;
use CkAmaury\PhpMagicFunctions\ArrayUtils;
use Error;

class ServerConnection{

    private string $login;
    private Curl $curl;
    private Cookie $cookie;

    private bool $is_connected = false;

    public function setLogin(string $login):self{
        $this->login = $login;
        return $this;
    }

    public function initialize(){
        $this->cookie = new Cookie();
        $this->cookie
            ->setLogin($this->login)
            ->loadLoginCookie();
        return $this;
    }

    public function tryConnection():bool{
        $this->curl = new Curl();
        $this->curl
            ->setUrl(ServerAdresses::IPN_WEB)
            ->setCookie($this->cookie)
            ->execute();
        return $this->checkConnection();
    }
    private function checkConnection():bool{
        $this->is_connected = str_contains($this->curl->getInfo()['url'],ServerAdresses::IPN_WEB);
        if($this->is_connected){
            $this->cookie->save();
        }
        return $this->is_connected;
    }

    public function getDevices() : ServerResponse{

        $this->tryConnection();

        $response = new ServerResponse();
        $response
            ->setIsConnected($this->is_connected)
            ->setIsAuthorized(true)
            ->setValues(array());

        $devices = array();

        if(str_contains($this->curl->getUrlFinal(),ServerAdresses::CONNECTION_IS_REQUEST)){
            $this->sendUser();

            $devices[] = $this->getEmptyDevice();
            $mfa_devices = $this->analyzeDevices($this->curl->getResponse());
            foreach($mfa_devices as $device){
                $value = $this->getEmptyDevice();
                $value['uuid'] = $device->uuid;
                $value['name'] = $device->displayName;

                switch($device->displayGaType){
                    case 'PINGID_APP' :
                        $value['type'] = 'PINGID_TOUCH';
                        $value['code'] = false;
                        $devices[] = $value;
                        $value['type'] = 'PINGID_CODE';
                        $value['code'] = true;
                        $devices[] = $value;
                        break;
                    case 'PINGID_DESKTOP' :
                        $value['type'] = 'PINGID_DESKTOP';
                        $devices[] = $value;
                        break;
                }
            }
            $response->setValues($devices);
        }
        else{
            $response->setMessageError('Problème lors de la récupération des devices PingID');
        }
        return $response;
    }
    public function getDevicesWithCode() : array{
        $return = array();
        foreach($this->getDevices()->getValues() as $value){
            if($value['code']) $return[] = $value;
        }
        return $return;
    }
    private function getEmptyDevice():array{
        $value = array();
        $value['type'] = 'TOKEN';
        $value['uuid'] = null;
        $value['name'] = null;
        $value['code'] = true;
        return $value;
    }
    private function analyzeDevices($response) : array{
        $analyze = new FormAnalyzer($response);
        foreach ($analyze->getForms() as $key => $value) {
            $id = ArrayUtils::get('id', $value);
            if($id == 'nuxForm'){
                $devices = ArrayUtils::get('pingIdUserDevices',$analyze->getInputsArray($key));
                if(!is_null($devices)){
                    $devices = str_replace('\'', '"', $devices);
                    $devices = json_decode($devices);
                    return $devices;
                }
            }
        }
        return array();
    }

    private function followNuxForm($post):bool{
        $analyze = new FormAnalyzer($this->curl->getResponse());
        $key = $this->getNuxFormKey();
        if(!is_null($key)){
            $this->sendNuxForm($analyze,$key,$post);
            return TRUE;
        }
        return FALSE;
    }
    private function getNuxFormKey():?string{
        $analyze = new FormAnalyzer($this->curl->getResponse());
        foreach ($analyze->getForms() as $key => $value) {
            $id = ArrayUtils::get('id', $value);
            if($id == 'nuxForm'){
                return $key;
            }
        }
        return null;
    }
    private function sendNuxForm($analyze,$key,$post){
        $value = $analyze->getForms()[$key];
        $this->createNewCurl(ServerAdresses::CONNECTION_WEB.$value['action']);
        $this->curl
            ->addPost($analyze->getInputsArray($key))
            ->addPost($post)
            ->execute();
    }

    private function createNewCurl($url){
        $this->curl = new Curl();
        $this->curl
            ->setUrl($url)
            ->setCookie($this->cookie);
    }

    public function request_FavoriteConnection(){
        $devices = $this->getDevices()->getValues();
        foreach($devices as $device){
            if($device['type'] == 'PINGID_TOUCH'){
                $this->send_PingIDTouch($device['uuid'],null);
                break;
            }
        }
        return $this->checkConnection();
    }

    public function request_connection($p_Type,$p_UID,$p_Code){
        $this->tryConnection();

        if($this->isPageConnection()){
            $this->sendUser();
            return $this->send_connection($p_Type,$p_UID,$p_Code);
        }

        return FALSE;
    }


    private function sendUser(){
        if(is_null($this->login)) throw new Error('Le login est NULL');
        $this->followNuxForm([
            'ok'        => 'IDENTIFICATION',
            'username'  => $this->login
        ]);
    }
    private function send_connection($p_Type,$p_UID,$p_Code) : bool{
        if($p_Code == FALSE) $p_Code = null;
        switch($p_Type){
            case 'TOKEN':
                return $this->send_Token($p_Code);
            case 'PINGID_TOUCH':
                return $this->send_PingIDTouch($p_UID,$p_Code);
            case 'PINGID_CODE':
                return $this->send_PingIDCode($p_UID,$p_Code);
            case 'PINGID_DESKTOP':
                return $this->send_PingIDDesktop($p_UID,$p_Code);
        }
        return FALSE;
    }
    private function send_Token($p_Password) : bool{

        $post = [
            'ok' => 'TOK',
            'currentAuthMethod' => 'TOK',
            'inputTokName' => $p_Password
        ];
        $this->followNuxForm($post);

        return $this->checkConnection();
    }
    public function send_PingIDTouch($p_UID,$p_Code = null) : bool{
        $post = [
            'ok' => 'PINGID_AUTHN_PUSH_NOTIF',
            'currentAuthMethod' => 'PINGID_AUTHN_PUSH_NOTIF',
            'otpNeed' => 'false',
            'currentUserDevice' => $p_UID,
            'inputTokName' => '$inputTokName'
        ];
        $this->followNuxForm($post);

        $post = [
            'ok' => 'PINGID_AUTHN_PUSH_NOTIF_PENDING',
            'currentAuthMethod' => 'PINGID_AUTHN_PUSH_NOTIF_PENDING',
            "inputPassName" => null,
            "inputTokName" => null,
            "newMethods" => null
        ];
        $this->followNuxForm($post);

        if(!is_null($p_Code) && !$this->checkConnection()){
            $post = [
                'ok' => 'PINGID_AUTHN_OTP',
                'currentAuthMethod' => 'PINGID_AUTHN_OTP',
                'mfaOtpStep' =>	"PINGID_AUTHN_OTP",

                "userSelectedTab" => "PINGID_AUTHN_PUSH_NOTIF_PENDING",
                "mfaWorkflowStatus" => "PINGID_AUTHN_OTP",

                'otp' => $p_Code,
                'otpAuth' => $p_Code,


                "displayFullUserName" => "",
                "inputPassName" => "",
                "inputTokName" => "",
            ];
            $this->followNuxForm($post);
        }


        return $this->checkConnection();
    }
    private function send_PingIDCode($p_UID,$p_Code) : bool{

        $post = [
            'ok' => 'PINGID_AUTHN_FORCE_OTP',
            'currentAuthMethod' => 'PINGID_AUTHN_FORCE_OTP',
            'currentUserDevice' => $p_UID
        ];
        $this->followNuxForm($post);
        $post = [
            'ok' => 'PINGID_AUTHN_OTP',
            'currentAuthMethod' => 'PINGID_AUTHN_OTP',
            'otp' => $p_Code,
            'mfaOtpStep' =>	"PINGID_AUTHN_OTP",
            'otpAuth' => $p_Code,
            "displayFullUserName" => "",
            "inputPassName" => null,
            "inputTokName" => null,
            "newMethods" => null,
        ];
        $this->followNuxForm($post);

        return $this->checkConnection();
    }
    private function send_PingIDDesktop($p_UID,$p_Code) : bool{

        $post = [
            'ok' => 'PINGID_AUTHN_DESKTOP',
            'currentAuthMethod' => 'PINGID_AUTHN_DESKTOP',
            "otpNeed" => "true",
            'currentUserDevice' => $p_UID
        ];
        $this->followNuxForm($post);

        $post = [
            'ok' => 'PINGID_AUTHN_OTP',
            'currentAuthMethod' => 'PINGID_AUTHN_OTP',
            'otp' => $p_Code,
            'mfaWorkflowStatus' =>	"PINGID_AUTHN_OTP",
            'mfaOtpStep' =>	"PINGID_AUTHN_OTP",
            'otpAuth' => $p_Code,
            "displayFullUserName" => "",
            "inputPassName" => null,
            "inputTokName" => null,
            "newMethods" => null
        ];
        $this->followNuxForm($post);

        return $this->checkConnection();
    }

    public function deleteCookie(){
        $this->cookie->delete();
    }

    private function isPageConnection() : bool{
        return (str_contains($this->curl->getUrlFinal(),ServerAdresses::CONNECTION_IS_REQUEST));
    }
}