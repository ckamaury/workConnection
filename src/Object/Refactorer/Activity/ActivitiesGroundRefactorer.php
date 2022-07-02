<?php

namespace CkAmaury\WorkConnection\Object\Refactorer\Activity;

use CkAmaury\PhpMagicFunctions\ArrayUtils;
use CkAmaury\WorkConnection\Object\Refactorer\Activity\Object\ActivityGround;
use CkAmaury\WorkConnection\Object\Refactorer\Activity\Object\Trainee;

class ActivitiesGroundRefactorer{

    private array $array_refactored = array();

    public function refactor(array $data) : array{

        foreach($data as $value){
            $ground_activity = $this->createActivityGround($value);
            $ground_activity->setPilots($this->importTrainees($value));
            $this->checkRoom($ground_activity);
            $this->addToArrayIfNotEmpty($ground_activity);
        }

        return $this->array_refactored;
    }

    private function createActivityGround($ground_activity) : ActivityGround {
        $new = new ActivityGround();
        $new
            ->setId(ArrayUtils::get('actId',$ground_activity))
            ->setFkBase(ArrayUtils::get('stationCode',$ground_activity))
            ->setFkRoom(ArrayUtils::get('room',$ground_activity))
            ->setIsQt(ArrayUtils::get('isQualificationTraining',$ground_activity));
        return $new;
    }

    private function importTrainees($ground_activity) : array{
        $trainees = ArrayUtils::get('trainees',$ground_activity) ?? array();
        return $this->refactorTrainees($trainees);
    }
    private function refactorTrainees($trainees) : array{
        $array = array();
        foreach($trainees as $trainee){
            $pn_type = ArrayUtils::get('populationType',$trainee);
            if($pn_type == 2){
                $new = $this->createTrainee($trainee);
                $array[$new->getMatricule()] = $new;
            }
        }
        return $array;
    }
    private function createTrainee(array $trainee):Trainee{
        return (new Trainee())
            ->setMatricule(ArrayUtils::get('fcNumber',$trainee))
            ->setIsCdb(ArrayUtils::get('onBoardFunction',$trainee))
            ->setFkSituation(ArrayUtils::get('fastOnBoardSituation',$trainee));
    }

    private function checkRoom(ActivityGround &$ground_activity){
        if($ground_activity->getFkRoom() == $ground_activity->getFkBase()){
            $ground_activity->setFkRoom(NULL);
        }
    }

    private function addToArrayIfNotEmpty(ActivityGround $ground_activity){
        if(!$this->checkIsEmpty($ground_activity)){
            $this->addToArray($ground_activity);
        }
    }
    private function checkIsEmpty(ActivityGround $ground_activity):bool{
        return
                is_null($ground_activity->getFkRoom())
            &&  !$ground_activity->getIsQt()
            &&  empty($ground_activity->getPilots());
    }
    private function addToArray(ActivityGround $ground_activity){
        $this->array_refactored[$ground_activity->getId()] = $ground_activity;
    }

}