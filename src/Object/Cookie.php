<?php

namespace CkAmaury\WorkConnection\Object;

use CkAmaury\Spreadsheet\File;
use CkAmaury\Symfony\APP;
use Exception;

class Cookie{

    private string $name;
    private string $login;
    private File $file;

    private const PATH_DIR = 'private/af_cookies/';
    private const FILE_FORMAT = '.txt';
    private const PATTERN_LOGIN = '/(M[0-9]{6}'.self::FILE_FORMAT.')/';


    public function __construct() {
        $this->file = new File();
    }

    public function setLogin(string $login) : self{
        $this->login = $login;
        return $this;
    }

    public function getCookieFilePath(){
        return $this->getCookiesDir().$this->name.self::FILE_FORMAT;
    }

    private function getCookiesDir(){
        return APP::getDir(1).self::PATH_DIR;
    }

    private function generateRandomCookieFile() {
        $this->name = 'tmp-'.APP::generateRandomString();
        $this->file->setPath($this->getCookieFilePath());
        if($this->file->isExisting()){
            $this->generateRandomCookieFile();
        }
    }

    public function loadLoginCookie() : bool {
        $this->name = $this->login;
        $this->file->setPath($this->getCookieFilePath());

        $exist = $this->file->isExisting();
        if(!$exist){
            $this->generateRandomCookieFile();
        }
        return $exist;
    }

    public function loadEmptyCookie(){
        $this->generateRandomCookieFile();
    }

    public function loadCustomCookie() : void{
        $this->cleanCookiesDir();
        foreach(scandir($this->getCookiesDir()) as $file){
            if(preg_match(self::PATTERN_LOGIN,$file)){
                $this->login = str_replace(self::FILE_FORMAT,'',$file);
                $this->loadLoginCookie();
                return;
            }
        }
        throw new Exception('Pas de cookies valide');
    }

    public function cleanCookiesDir(){
        foreach(scandir($this->getCookiesDir()) as $file){
            $fileName = $this->getCookiesDir().$file;
            if(is_file($fileName)){
                $time = (time() - filemtime($fileName)) / 60;
                if(preg_match(self::PATTERN_LOGIN,$fileName)){
                    if($time > 43200){//1 MOIS
                        unlink($fileName);
                    }
                }
                else{
                    if($time > 2){//2 MINUTES
                        unlink($fileName);
                    }
                }
            }
        }
    }

    private function createFile() : string{
        if(!$this->file->isExisting()){
            $this->file->create();
        }
        return $this->file->getPath();
    }

    public function delete() : void{
        $this->file->delete();
    }

    public function save(){
        $this->name = $this->login;
        $this->file->rename($this->getCookieFilePath());
    }

    public function getFilePath() : string{
        $this->createFile();
        return $this->getCookiesDir().$this->name.self::FILE_FORMAT;
    }

}