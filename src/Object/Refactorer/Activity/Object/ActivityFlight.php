<?php

namespace CkAmaury\WorkConnection\Object\Refactorer\Activity\Object;

class ActivityFlight {

    private $id;
    private $is_instruction;
    private $is_longhaul;
    private $days;

    /** @var Flight[] */
    private array $flights = array();


    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return ActivityFlight
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }



    public function getIsInstruction()
    {
        return $this->is_instruction;
    }


    public function setIsInstruction($is_instruction)
    {
        $this->is_instruction = $is_instruction;
        return $this;
    }


    public function getIsLonghaul()
    {
        return $this->is_longhaul;
    }
    public function setIsLonghaul($is_longhaul)
    {
        $this->is_longhaul = ($is_longhaul == 'L');
        return $this;
    }

    public function getDays()
    {
        return $this->days;
    }
    public function setDays($days)
    {
        $this->days = $days;
        return $this;
    }

    public function getFlights() : array{
        return $this->flights;
    }
    public function setFlights($flights)
    {
        $this->flights = $flights;
        return $this;
    }

}