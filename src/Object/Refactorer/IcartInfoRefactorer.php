<?php

namespace CkAmaury\WorkConnection\Object\Refactorer;

use CkAmaury\WorkConnection\Object\Refactorer\Object\IcartInfo;
use CkAmaury\WorkConnection\Object\Refactorer\Object\IcartInfoQualification;
use CkAmaury\PhpDatetime\DateTime;

class IcartInfoRefactorer{


    public function refactor(array $data):IcartInfo{

        $info = new IcartInfo();

        $piloteBean = $data['piloteBean'];
        $career = $data['carriere'];

        $info
            ->setLastName(rtrim($piloteBean['nom']))
            ->setFirstName(rtrim($piloteBean['prenom']))
            ->setMatricule($piloteBean['matricule'])
            ->setFunction($piloteBean['specialite'])
            ->setIsAirFrancePilot($piloteBean['afPilot'])
            ->setIsTransaviaPilot($piloteBean['toPilot'])
            ->setIsTrainee($piloteBean['stagiaireEQLA'])
            ->setIsExecutive($piloteBean['cadre'])
            ->setBirthDate($this->convertDate($piloteBean['dateNaissance']))
            ->setEntryDate($this->convertDate($data['datEntCie']))
            ->setFirstOfficerDate($this->convertDate($data['datLchOpl']))
            ->setCaptainDate($this->convertDate($data['datLchCdb']))
            ->setClass($career['classe'])
            ->setClassDate($this->convertDate($career['dateClasse']))
            ->setGrade($career['echelon'])
            ->setGradeDate($this->convertDate($career['dateEchelon']))
            ->setAmortissementDate($this->convertDate($career['dateAmortissement']))
            ->setQualificationNumber($career['nbQualifs'])
            ->setLcpRank($career['seniorite'])
            ->setTta($career['ttaBean']['tauxTTA']);

        $array = array();
        foreach($data['listeQualifs'] as $qualifItem){
            $qualification = new IcartInfoQualification();
            $qualification
                ->setQualification($qualifItem['typeAvion'])
                ->setFunction($qualifItem['specialite'])
                ->setStartDate($this->convertDate($qualifItem['dateDebut']))
                ->setEndDate($this->convertDate($qualifItem['dateFin']));

            $array[] = $qualification;
        }
        $info->setQualifications($array);

        foreach($data['listeCbvs'] as $qualifItem){
            if($qualifItem['codeBrevet'] == 'ELP'){
                $info->setAtplDate($this->convertDate($qualifItem['dateDebut']));
            }
        }

        return $info;
    }

    private function convertDate($p_Date) : ?DateTime{
        if(in_array($p_Date,array('31/12/9999',null))){
            return null;
        }
        return (new DateTime())->initByFrenchFormat($p_Date)->eraseTime();
    }


}