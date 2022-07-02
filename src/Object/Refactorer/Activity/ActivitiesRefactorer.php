<?php


namespace CkAmaury\WorkConnection\Object\Refactorer\Activity;


use CkAmaury\PhpMagicFunctions\ArrayUtils;
use CkAmaury\WorkConnection\Object\Refactorer\Activity\Object\Activity;

class ActivitiesRefactorer{

    private array $array_refactored = array();

    public function refactor(array $data) : array{

        foreach($data as $value){
            if(isset($value['activityKey'])){
                $activity = $this->createActivity($value);
                $this->transformActivityCode($activity);
                $this->addToArray($activity);
            }
        }
        return $this->array_refactored;
    }


    private function createActivity($activity) : Activity {

        $division = ArrayUtils::get('division',$activity) ?? ArrayUtils::get('activityDivision',$activity);

        $new = new Activity();
        $new
            ->setId(ArrayUtils::get('actId',$activity))
            ->setFkDivision($division)
            ->setFkBase(ArrayUtils::get('stationCode',$activity))
            ->setFkCode(ArrayUtils::get('fastActivityCode',$activity))
            ->setSituation(ArrayUtils::get('fastOnBoardSituation',$activity))
            ->setSituationCode(ArrayUtils::get('fastOnBoardSituationCode',$activity))
            ->setKey(ArrayUtils::get('activityKey',$activity))
            ->setName(ArrayUtils::get('activityNumber',$activity))
            ->setWeight(ArrayUtils::get('activityDisplayWeight',$activity))
            ->setCode(ArrayUtils::get('fastActivitySubcode',$activity))
            ->setLabel(ArrayUtils::get('fastLabel',$activity))
            ->setParticularity(ArrayUtils::get('fastParticularityCode',$activity))
            ->setIsInstructor(ArrayUtils::get('instructorActivity',$activity))
            ->setIsLongtraining(ArrayUtils::get('longTraining',$activity))
            ->setIsCrossedhaul(ArrayUtils::get('crossedHaul',$activity))
            ->setDateActivityStart(ArrayUtils::get('beginActivityDate',$activity))
            ->setDateActivityEnd(ArrayUtils::get('endActivityDate',$activity))
            ->setDateDutyStart(ArrayUtils::get('beginDutyDate',$activity))
            ->setDateDutyEnd(ArrayUtils::get('endDutyDate',$activity));

        return $new;
    }

    private function transformActivityCode(Activity &$activity){
        //JOURNEE SYNDICALE
        if($activity->getLabel() === 'REPRES DU PERSONNEL'){
            $activity->setFkCode("X-SYN");
            $activity->getDateDutyEnd()->subHours(12);
        }

        //LONGUE QT
        if($activity->getIsLongtraining()){
            $activity->setFkCode("X-LQT");
        }

        //VISITE MEDICALE D APTITUDE
        if($activity->getFkCode() == "MVM" && str_contains($activity->getLabel(),'APTI')){
            $activity->setFkCode("X-MVM");
        }
    }

    private function addToArray(Activity $activity){
        $this->array_refactored[$activity->getId()] = $activity;
    }




}