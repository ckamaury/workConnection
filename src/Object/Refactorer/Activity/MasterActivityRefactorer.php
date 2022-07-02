<?php

namespace CkAmaury\WorkConnection\Object\Refactorer\Activity;

use CkAmaury\PhpMagicFunctions\ArrayUtils;
use CkAmaury\WorkConnection\Object\Refactorer\Activity\Object\Activity;
use CkAmaury\WorkConnection\Object\Refactorer\Activity\Object\ActivityFlight;
use CkAmaury\WorkConnection\Object\Refactorer\Activity\Object\ActivityGround;
use CkAmaury\WorkConnection\Object\Refactorer\Activity\Object\Crew;

class MasterActivityRefactorer {

    /** @var Activity[] */
    private array $activities = array();

    /** @var ActivityGround[] */
    private array $activities_ground = array();

    /** @var ActivityFlight[] */
    private array $activities_flight = array();

    /** @var Crew[] */
    private array $activities_crew = array();

    public function refactor() : array{
        $this->refactorActivities();
        $this->refactorActivitiesGround();
        $this->refactorActivitiesFlight();
        $this->refactorActivitiesCrew();
        $this->linkData();

        return $this->activities;
    }
    public function linkData(){
        $this->linkCrewsToFlights();
        $this->linkFlightsToActivities();
        $this->linkGroundsToActivities();
    }

    public function refactorActivities(){
        $refactorer = new ActivitiesRefactorer();
        $this->activities = $refactorer->refactor($this->activities);
    }
    public function refactorActivitiesGround(){
        $refactorer = new ActivitiesGroundRefactorer();
        $this->activities_ground = $refactorer->refactor($this->activities_ground);
    }
    public function refactorActivitiesFlight(){
        $refactorer = new ActivitiesFlightRefactorer();
        $this->activities_flight = $refactorer->refactor($this->activities_flight);
    }
    public function refactorActivitiesCrew(){
        $refactorer = new ActivitiesFlightCrewRefactorer();
        $this->activities_crew = $refactorer->refactor($this->activities_crew);
    }

    private function linkCrewsToFlights(){
        foreach($this->activities_flight as &$rotation){
            foreach($rotation->getFlights() as $flight_id => &$flight){
                $crews = ArrayUtils::get($flight_id,$this->activities_crew);
                if(!is_null($crews)){
                    $flight->setCrew($crews);
                    unset($this->activities_crew[$flight_id]);
                }
            }
        }
    }
    private function linkFlightsToActivities(){
        foreach($this->activities_flight as $activity_id => $flight){
            if(isset($this->activities[$activity_id])){
                $activity = &$this->activities[$activity_id];
                $this->changeInstructionValue($activity,$flight);
                $activity->setFlightDetails($flight);
                unset($this->activities_flight[$activity_id]);
            }
        }
    }
    private function changeInstructionValue(Activity $activity,ActivityFlight  &$flight){
        if( $activity->getParticularity() == 'I'){
            $flight->setIsInstruction(true);
        }
    }
    private function linkGroundsToActivities(){
        foreach($this->activities_ground as $activity_id => $ground){
            if(isset($this->activities[$activity_id])){
                $activity = &$this->activities[$activity_id];
                $activity->setGroundDetails($ground);
                unset($this->activities_ground[$activity_id]);
            }
        }
    }

    public function getActivities(): array
    {
        return $this->activities;
    }
    public function setActivities(array $activities): self
    {
        $this->activities = $activities;
        return $this;
    }

    public function getActivitiesGround(): array
    {
        return $this->activities_ground;
    }
    public function setActivitiesGround(array $activities_ground): self
    {
        $this->activities_ground = $activities_ground;
        return $this;
    }

    public function getActivitiesFlight(): array
    {
        return $this->activities_flight;
    }
    public function setActivitiesFlight(array $activities_flight): self
    {
        $this->activities_flight = $activities_flight;
        return $this;
    }

    public function getActivitiesCrew(): array
    {
        return $this->activities_crew;
    }
    public function setActivitiesCrew(array $activities_crew): self
    {
        $this->activities_crew = $activities_crew;
        return $this;
    }




}