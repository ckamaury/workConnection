<?php

namespace CkAmaury\WorkConnection\Object\Refactorer;

use CkAmaury\WorkConnection\Object\Refactorer\Object\CrewInfo;
use CkAmaury\WorkConnection\Object\Refactorer\Object\CrewInfoAssignment;
use CkAmaury\WorkConnection\Object\Refactorer\Object\CrewInfoExperience;
use CkAmaury\WorkConnection\Object\Refactorer\Object\CrewInfoQualification;
use CkAmaury\PhpDatetime\DateTime;
use CkAmaury\PhpMagicFunctions\ArrayUtils;

class CrewInfoRefactorer {

    private CrewInfo $info;

    public function refactor(array $data):CrewInfo{

        $this->info = new CrewInfo();

        $this->info
            ->setLastName(ArrayUtils::get('lastName',$data))
            ->setFirstName(ArrayUtils::get('firstName',$data))
            ->setMatricule(ArrayUtils::get('fcNumber',$data))
            ->setIsMale(ArrayUtils::get('sex',$data) == 'M')
            ->setBirthDate($this->convertDate(ArrayUtils::get('birthDate',$data)))
            ->setEntryDate($this->convertDate(ArrayUtils::get('entryDate',$data)))
            ->setIsInstructor(ArrayUtils::get('instructor',$data))
            ->setAtplDate($this->findATPL(ArrayUtils::get('licenses',$data)));


        $this->refactorQualifications(ArrayUtils::get('qualificationsList',$data));
        $this->refactorAssignments(array_merge(
            ArrayUtils::get('opeAssignments',$data),
            ArrayUtils::get('futureOpeAssignments',$data) ?? array())
        );
        $this->refactorExperiences(ArrayUtils::get('recentExperiences',$data));

        return $this->info;
    }

    private function refactorQualifications(?array $qualifications){
        $array = array();
        foreach($qualifications as $qualificationItem){
            $qualification = new CrewInfoQualification();
            $qualification
                ->setId(ArrayUtils::get('quaId',$qualificationItem))
                ->setQualification(ArrayUtils::get('aircraftTypeCode',$qualificationItem))
                ->setFunction(ArrayUtils::get('specialityCode',$qualificationItem))
                ->setStartDate($this->convertDate((ArrayUtils::get('beginQualifDate',$qualificationItem))))
                ->setEndDate($this->convertDate((ArrayUtils::get('endQualifDate',$qualificationItem))));

            $array[] = $qualification;
        }

        $this->sortArray($array);
        $this->info->setQualifications($array);
    }
    private function refactorAssignments(?array $assignments){
        $array = array();
        foreach($assignments as $assignmentItem){
            $assignment = new CrewInfoAssignment();
            $assignment
                ->setId(ArrayUtils::get('opaId',$assignmentItem))
                ->setBase(ArrayUtils::get('baseCode',$assignmentItem))
                ->setFunction(ArrayUtils::get('statusCode',$assignmentItem))
                ->setStartDate($this->convertDate((ArrayUtils::get('beginAssignmentDate',$assignmentItem))))
                ->setEndDate($this->convertDate((ArrayUtils::get('endAssignmentDate',$assignmentItem))));

            $array[] = $assignment;
        }
        $this->sortArray($array);
        $this->info->setAssignments($array);
    }
    private function refactorExperiences(?array $experiences){
        $array = array();
        foreach($experiences as $experienceItem){
            $experience = new CrewInfoExperience();
            $experience
                ->setIsFlight(ArrayUtils::get('activityType',$experienceItem) == 'VOL')
                ->setCode(ArrayUtils::get('company',$experienceItem).ArrayUtils::get('lineNumber',$experienceItem))
                ->setTakeoff(ArrayUtils::get('nbTakeOff',$experienceItem))
                ->setLanding(ArrayUtils::get('nbLand',$experienceItem))
                ->setStartDate($this->convertDate((ArrayUtils::get('experienceDate',$experienceItem))));

            $array[] = $experience;
        }
        $this->sortArray($array);
        $this->info->setExperiences($array);
    }

    private function findATPL(?array $licenses){

        foreach($licenses as $license){
            if(ArrayUtils::get('licenseType',$license) == 'TL'){
                return $this->convertDate(ArrayUtils::get('beginValidityDate',$license));
            }

        }

        return NULL;
    }

    private function convertDate($pDate) : ?DateTime{
        if($pDate == 253370764800000){
            return null;
        }
        return (new DateTime())->setMicroTimestamp($pDate)->eraseTime();
    }
    private function sortArray(&$array){
        usort($array, function ($item1,$item2) {
            $time1 = $item1->getStartDate();
            $time2 = $item2->getStartDate();
            if ($time1 > $time2)
                return 1;
            elseif ($time1 < $time2)
                return -1;
            else
                return 0;
        });
    }
}