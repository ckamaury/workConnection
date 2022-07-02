<?php


namespace CkAmaury\WorkConnection\Object\Refactorer\Activity\Object;


class Trainee {

    private $matricule;
    private $is_cdb;
    private $fk_situation;

    public function getMatricule()
    {
        return $this->matricule;
    }
    public function setMatricule($matricule)
    {
        $this->matricule = $matricule;
        return $this;
    }

    public function getIsCdb()
    {
        return $this->is_cdb;
    }
    public function setIsCdb($is_cdb){
        if(!is_bool($is_cdb)){
            $is_cdb = ($is_cdb == 'CPT');
        }
        $this->is_cdb = $is_cdb;
        return $this;
    }


    public function getFkSituation()
    {
        return $this->fk_situation;
    }
    public function setFkSituation($fk_situation){
        $this->fk_situation = trim($fk_situation,'*');
        return $this;
    }
}