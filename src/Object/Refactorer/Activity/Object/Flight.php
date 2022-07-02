<?php

namespace CkAmaury\WorkConnection\Object\Refactorer\Activity\Object;

use CkAmaury\PhpDatetime\DateTime;

class Flight {

    private $id;
    private $is_mep;
    private $num_day;
    private $num_leg;
    private $num_pax = 0;
    private $aircraft_reg;
    private $aircraft_code;
    private $departure;
    private $arrival;
    private $flight_number;
    private $date_scheduled_departure;
    private $date_departure;
    private $date_scheduled_arrival;
    private $date_arrival;

    /** @var Crew[] */
    private array $crew = array();

    public function getId()
    {
        return $this->id;
    }


    public function setId($id){
        $this->id = $id;
        return $this;
    }


    public function getIsMep()
    {
        return $this->is_mep;
    }
    public function setIsMep($is_mep){
        $this->is_mep = ($is_mep == 1);
        return $this;
    }


    public function getNumDay()
    {
        return $this->num_day;
    }
    public function setNumDay($num_day)
    {
        $this->num_day = $num_day;
        return $this;
    }


    public function getNumLeg()
    {
        return $this->num_leg;
    }
    public function setNumLeg($num_leg)
    {
        $this->num_leg = $num_leg;
        return $this;
    }


    public function getNumPax()
    {
        return $this->num_pax;
    }
    public function setNumPax($num_pax)
    {
        $this->num_pax = $num_pax ?? 0;
        return $this;
    }

    public function getAircraftReg()
    {
        return $this->aircraft_reg;
    }
    public function setAircraftReg($aircraft_reg)
    {
        $this->aircraft_reg = $aircraft_reg;
        return $this;
    }

    public function getAircraftCode()
    {
        return $this->aircraft_code;
    }
    public function setAircraftCode($aircraft_code)
    {
        $this->aircraft_code = $aircraft_code;
        return $this;
    }

    public function getDeparture()
    {
        return $this->departure;
    }
    public function setDeparture($departure)
    {
        $this->departure = $departure;
        return $this;
    }

    public function getArrival()
    {
        return $this->arrival;
    }
    public function setArrival($arrival)
    {
        $this->arrival = $arrival;
        return $this;
    }

    public function getFlightNumber()
    {
        return $this->flight_number;
    }
    public function setFlightNumber($flight_number)
    {
        $this->flight_number = $flight_number;
        return $this;
    }

    public function getDateScheduledDeparture()
    {
        return $this->date_scheduled_departure;
    }
    public function setDateScheduledDeparture($date_scheduled_departure)
    {
        $this->date_scheduled_departure = (new DateTime())->setMicroTimestamp($date_scheduled_departure);
        return $this;
    }

    public function getDateDeparture()
    {
        return $this->date_departure;
    }
    public function setDateDeparture($date_departure){
        if(!is_null($date_departure)){
            $this->date_departure = (new DateTime())->setMicroTimestamp($date_departure);
        }
        return $this;
    }

    public function getDateScheduledArrival()
    {
        return $this->date_scheduled_arrival;
    }
    public function setDateScheduledArrival($date_scheduled_arrival)
    {
        $this->date_scheduled_arrival = (new DateTime())->setMicroTimestamp($date_scheduled_arrival);
        return $this;
    }

    public function getDateArrival()
    {
        return $this->date_arrival;
    }
    public function setDateArrival($date_arrival){
        if(!is_null($date_arrival)){
            $this->date_arrival = (new DateTime())->setMicroTimestamp($date_arrival);
        }
        return $this;
    }

    public function getCrew(): array {
        return $this->crew;
    }
    public function setCrew(array $crew): Flight
    {
        $this->crew = $crew;
        return $this;
    }
}