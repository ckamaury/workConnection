<?php


namespace CkAmaury\WorkConnection\Object\Refactorer\Activity\Object;


class Crew {

    private $matricule;
    private $type;
    private $function;
    private $code;
    private $code_fast;
    private $is_mep;

    /**
     * @return mixed
     */
    public function getMatricule()
    {
        return $this->matricule;
    }

    /**
     * @param mixed $matricule
     * @return Crew
     */
    public function setMatricule($matricule)
    {
        $this->matricule = $matricule;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     * @return Crew
     */
    public function setType($type){
        $this->type = ($type == 2) ? 'PNT' : 'PNC';
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFunction()
    {
        return $this->function;
    }

    /**
     * @param mixed $function
     * @return Crew
     */
    public function setFunction($function)
    {
        $this->function = $function;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     * @return Crew
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCodeFast()
    {
        return $this->code_fast;
    }

    /**
     * @param mixed $code_fast
     * @return Crew
     */
    public function setCodeFast($code_fast)
    {
        $this->code_fast = $code_fast;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsMep()
    {
        return $this->is_mep;
    }

    /**
     * @param mixed $is_mep
     * @return Crew
     */
    public function setIsMep($is_mep)
    {
        $this->is_mep = $is_mep;
        return $this;
    }




}