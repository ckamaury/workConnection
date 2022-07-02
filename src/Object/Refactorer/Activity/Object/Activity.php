<?php


namespace CkAmaury\WorkConnection\Object\Refactorer\Activity\Object;

use CkAmaury\PhpDatetime\DateTime;

class Activity {

    private $id;
    private $fk_division;
    private $fk_base;
    private $fk_code;
    private $situation;
    private $situation_code;
    private $key;
    private $name;
    private $weight = 0;
    private $code;
    private $label;
    private $particularity;
    private $is_instructor;
    private $is_longtraining = false;
    private $is_crossedhaul = false;
    private $date_activity_start;
    private $date_activity_end;
    private $date_duty_start;
    private $date_duty_end;


    private ?ActivityFlight $flight_details = null;
    private ?ActivityGround $ground_details = null;

    public function getId(){
        return $this->id;
    }
    public function setId($id) : self{
        $this->id = intval($id);
        return $this;
    }

    public function getFkDivision(){
        return $this->fk_division;
    }
    public function setFkDivision($fk_division) : self{
        $this->fk_division = $fk_division;
        return $this;
    }

    public function getFkBase()
    {
        return $this->fk_base;
    }
    public function setFkBase($fk_base) : self
    {
        $this->fk_base = $fk_base;
        return $this;
    }

    public function getFkCode()
    {
        return $this->fk_code;
    }
    public function setFkCode($fk_code) : self
    {
        $this->fk_code = $fk_code;
        return $this;
    }

    public function getSituation()
    {
        return $this->situation;
    }
    public function setSituation($situation) : self{
        $this->situation = trim($situation,'*');
        return $this;
    }

    public function getSituationCode(){
        return $this->situation_code;
    }
    public function setSituationCode($situation_code) : self {
        $this->situation_code = trim($situation_code,'*');
        return $this;
    }

    public function getKey(){
        return $this->key;
    }
    public function setKey($key) : self
    {
        $this->key = $key;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }
    public function setName($name) : self
    {
        $this->name = $name;
        return $this;
    }

    public function getWeight(){
        return $this->weight;
    }
    public function setWeight($weight) : self{
        $this->weight = $weight ?? 0;
        return $this;
    }

    public function getCode()
    {
        return $this->code;
    }
    public function setCode($code) : self
    {
        $this->code = $code;
        return $this;
    }

    public function getLabel()
    {
        return $this->label;
    }
    public function setLabel($label) : self
    {
        $this->label = $label;
        return $this;
    }

    public function getParticularity()
    {
        return $this->particularity;
    }
    public function setParticularity($particularity) : self
    {
        $this->particularity = $particularity;
        return $this;
    }

    public function getIsInstructor()
    {
        return $this->is_instructor;
    }
    public function setIsInstructor($is_instructor) : self
    {
        $this->is_instructor = $is_instructor;
        return $this;
    }

    public function getIsLongtraining()
    {
        return $this->is_longtraining;
    }
    public function setIsLongtraining($is_longtraining) : self{
        $this->is_longtraining = boolval($is_longtraining);
        return $this;
    }

    public function getIsCrossedhaul(){
        return $this->is_crossedhaul;
    }
    public function setIsCrossedhaul($is_crossedhaul) : self{
        $this->is_crossedhaul = boolval($is_crossedhaul);
        return $this;
    }

    public function getDateActivityStart()
    {
        return $this->date_activity_start;
    }
    public function setDateActivityStart($date_activity_start) : self{
        $this->date_activity_start = (new DateTime())->setMicroTimestamp($date_activity_start);
        $this->date_duty_start = $this->date_activity_start->clone();
        return $this;
    }

    public function getDateActivityEnd() : ?DateTime{
        return $this->date_activity_end;
    }
    public function setDateActivityEnd($date_activity_end){
        $this->date_activity_end = (new DateTime())->setMicroTimestamp($date_activity_end);
        $this->date_duty_end = $this->date_activity_end->clone();
        return $this;
    }

    public function getDateDutyStart(){
        return $this->date_duty_start;
    }
    public function setDateDutyStart($date_duty_start) : self {
        if(!is_null($date_duty_start)){
            $this->date_duty_start = (new DateTime())->setMicroTimestamp($date_duty_start);
        }
        return $this;
    }

    public function getDateDutyEnd(){
        return $this->date_duty_end;
    }
    public function setDateDutyEnd($date_duty_end) : self {
        if(!is_null($date_duty_end)){
            $this->date_duty_end = (new DateTime())->setMicroTimestamp($date_duty_end);
        }

        return $this;
    }


    public function getFlightDetails() : ?ActivityFlight{
        return $this->flight_details;
    }
    public function setFlightDetails($flight_details) : self{
        $this->flight_details = $flight_details;
        return $this;
    }


    public function getGroundDetails(): ?ActivityGround{
        return $this->ground_details;
    }
    public function setGroundDetails($ground_details) : self{
        $this->ground_details = $ground_details;
        return $this;
    }





}