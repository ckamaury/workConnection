<?php


namespace CkAmaury\WorkConnection\Object\Refactorer\Activity\Object;

class ActivityGround {

    private $id;
    private $fk_base;
    private $fk_room;
    private $is_qt = false;

    private array $pilots = array();

    public function getId(){
        return $this->id;
    }
    public function setId($id){
        $this->id = $id;
        return $this;
    }

    public function getFkBase(){
        return $this->fk_base;
    }
    public function setFkBase($fk_base){
        $this->fk_base = $fk_base;
        return $this;
    }

    public function getFkRoom(){
        return $this->fk_room;
    }
    public function setFkRoom($fk_room){
        $this->fk_room = $fk_room;
        return $this;
    }

    public function getIsQt(){
        return $this->is_qt;
    }
    public function setIsQt($is_qt){
        $this->is_qt = $is_qt;
        return $this;
    }

    public function getPilots(){
        return $this->pilots;
    }
    public function setPilots($pilots){
        $this->pilots = $pilots;
        return $this;
    }



}