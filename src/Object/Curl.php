<?php

namespace CkAmaury\WorkConnection\Object;

use CkAmaury\PhpHtml\FormAnalyzer;

class Curl{
    private const SN = 'eu7Z8S6BzeY4Z34YAGzs';

    private \CkAmaury\PhpCurl\Curl $curl;
    private string $url;
    private Cookie $cookie;
    private bool $is_executed = false;

    private array $options = array();
    private array $options_post = array();
    private array $full_options = array();

    private bool $json_encode_post = false;



    public function setUrl(string $url) : self{
        $this->url = $url;
        return $this;
    }

    public function setCookie(Cookie $cookie) : self{
        $this->cookie = $cookie;
        return $this;
    }

    public function setOptions(array $options) : self{
        $this->options = $options;
        return $this;
    }

    public function reset(){
        $this->options = array();
        $this->options_post = array();
        $this->is_executed = FALSE;
    }

    public function initialize(){
        $this->fillOptions();
        $this->curl = new \CkAmaury\PhpCurl\Curl();
        $this->curl
            ->setUrl($this->url)
            ->setOptions($this->full_options)
            ->initialize();
    }

    public function execute():bool{
        if(!$this->is_executed){
            $this->initialize();
            $this->curl->execute();
            $this->checkAndPassForm();
            $this->is_executed = TRUE;
        }
        return $this->is_executed;
    }

    private function checkAndPassForm(){
        $response = $this->curl->getResponse();
        if(str_contains($response,'<title>Submit Form</title>')){
            $analyze = new FormAnalyzer($response);
            foreach($analyze->getForms() as $key => $value){
                if(array_key_exists('action',$value)){
                    $this->reset();
                    $this->url = $value['action'];
                    $this->addPost($analyze->getInputsArray($key));
                    $this->execute();
                }
            }
        }
        if(str_contains($response,'onLoad="navigate()"')){
            preg_match('/<a href="(.*)">/', $response, $url);
            $this->reset();
            $url = $url[1];
            $url = str_replace('&amp;','&',$url);
            $this->url = $url;
            $this->execute();
        }
    }

    private function fillOptions(){
        $this->full_options = array();
        $this->fillOptionsDefault();
        $this->fillOptionsPost();
        $this->checkAndFillOptionsCrewbidd();
        $this->full_options += $this->options;
    }

    public function addPost(array $post) : self{
        $this->options_post = array_merge($this->options_post,$post);
        return $this;
    }

    private function fillOptionsDefault() : void{
        $path = $this->cookie->getFilePath();
        $this->full_options += array(
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_HEADER          => false,
            CURLOPT_FOLLOWLOCATION  => true,
            CURLOPT_ENCODING        => "",
            CURLOPT_USERAGENT       => "MyConcorde Application",
            CURLOPT_SSL_VERIFYPEER  => false,
            CURLOPT_SSL_VERIFYHOST  => false,
            CURLOPT_AUTOREFERER     => true,
            CURLOPT_CONNECTTIMEOUT  => 120,
            CURLOPT_TIMEOUT         => 120,
            CURLOPT_MAXREDIRS       => 10,
            CURLOPT_COOKIEFILE      => realpath($path),
            CURLOPT_COOKIEJAR       => realpath($path)
        );
    }
    public function fillOptionsPost() : void{
        if(!empty($this->options_post)){
            $this->full_options[CURLOPT_POST] = 1;
            $this->full_options[CURLOPT_POSTFIELDS] = ($this->json_encode_post) ? json_encode($this->options_post) : http_build_query($this->options_post);
        }
    }

    private function checkAndFillOptionsCrewbidd() : void{
        if(str_contains($this->url,ServerAdresses::CREW_API)){
            $this->fillOptionsCrewBidd();
        }
    }
    private function fillOptionsCrewBidd() : void{
        $this->full_options += array(
            CURLOPT_HTTPHEADER => array(
                'Host: '.ServerAdresses::CREW_HOST,
                'Referer: https://'.ServerAdresses::CREW_HOST.'/cm/',
                'SN: '.self::SN,
                'Content-Type: application/json;charset=UTF-8',
                'Accept: application/json, text/plain',
                'Accept-Language: fr,fr-FR;q=0.8,en-US;q=0.5,en;q=0.3',
                'Accept-Encoding: gzip, deflate, br')
        );
    }


    public function getResponse() {
        return $this->curl->getResponse();
    }

    public function getResponseJson() {
        return json_decode($this->curl->getResponse(),true);
    }

    public function getInfo() {
        return $this->curl->getInfo();
    }

    public function getUrlFinal(){
        return $this->curl->getInfo()['url'];
    }

    public function getCurl(){
        return $this->curl;
    }


    public function executeAndReturnJson(){
        $this->execute();
        return $this->getResponseJson();
    }
    public function executeAndReturnResponse(){
        $this->execute();
        return $this->getResponse();
    }


    public function setJsonEncodePost(bool $json_encode_post): self {
        $this->json_encode_post = $json_encode_post;
        return $this;
    }

    public function getCookie(): Cookie {
        return $this->cookie;
    }

    public function resetExecuted(){
        $this->is_executed = false;
    }

    public function getPost(){
        return $this->options_post;
    }

}