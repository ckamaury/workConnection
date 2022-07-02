<?php

namespace CkAmaury\WorkConnection\Object\Refactorer\Activity;

use CkAmaury\PhpMagicFunctions\ArrayUtils;
use CkAmaury\WorkConnection\Object\Refactorer\Activity\Object\Crew;

class ActivitiesFlightCrewRefactorer {

    private array $array_refactored = array();

    public function refactor(array $data){
        foreach($data as $rotation){
            if(!is_null($rotation)){
                foreach($rotation as $flight){
                    $crews = ArrayUtils::get('flightCrew',$flight);
                    $id = ArrayUtils::get('legId',$flight);
                    if(is_array($crews)){
                        $this->array_refactored[$id] = $this->refactorCrew($crews);
                    }
                }
            }
        }
        return $this->array_refactored;
    }

    private function refactorCrew(array $data) : array{
        $crews = array();
        foreach($data as $crew){
            $matricule = ArrayUtils::get('fcNumber',$crew);
            $pn_type = ArrayUtils::get('populationType',$crew);
            if(intval($matricule) > 0 && $pn_type == 2){
                $crews[] = $this->createCrew($crew);
            }
        }
        return $crews;
    }
    private function createCrew($crew) : Crew {
        return (new Crew())
            ->setMatricule(ArrayUtils::get('fcNumber',$crew))
            ->setType(ArrayUtils::get('populationType',$crew))
            ->setFunction(ArrayUtils::get('onBoardFunction',$crew))
            ->setCode(ArrayUtils::get('fastParticularityCode',$crew))
            ->setCodeFast(ArrayUtils::get('fastOnBoardSituation',$crew))
            ->setIsMep(ArrayUtils::get('deadhead',$crew));
    }

}