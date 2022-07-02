<?php

namespace CkAmaury\WorkConnection\Object\Refactorer\Activity;

use CkAmaury\PhpMagicFunctions\ArrayUtils;
use CkAmaury\WorkConnection\Object\Refactorer\Activity\Object\ActivityFlight;
use CkAmaury\WorkConnection\Object\Refactorer\Activity\Object\Flight;

class ActivitiesFlightRefactorer {

    private array $array_refactored = array();

    public function refactor(array $data):array{
        foreach($data as $rotation){
            if(isset($rotation['flightDuty'])){
                $new = $this->createActivityFlight($rotation);
                $new->setFlights($this->refactorFlights($rotation['flightDuty']));
                if(!empty($new->getFlights())){
                    $this->addToArray($new);
                }
            }
        }
        return $this->array_refactored;
    }
    private function createActivityFlight($rotation) : ActivityFlight {
        $pairing = $rotation['pairingValue'][0];
        $new = new ActivityFlight();
        $new
            ->setId(ArrayUtils::get('activityId',$rotation))
            ->setIsInstruction(ArrayUtils::get('instructorActivity',$rotation))
            ->setIsLonghaul(ArrayUtils::get('haulType',$pairing))
            ->setDays(ArrayUtils::get('nbOnDays',$pairing));

        return $new;
    }



    private function refactorFlights(array $data) : array{
        $flights = array();
        foreach($data as $key_day => $day) {
            foreach ($day['dutyLegAssociation'] as $key_leg => $leg) {
                $flight = $this->createFlight($leg);
                $flight->setNumDay($key_day + 1);
                $flight->setNumLeg($key_leg + 1);
                $flights[$flight->getId()] = $flight;
            }
        }

        return $flights;
    }

    private function createFlight($leg) : Flight {

        $flight = $leg['legs'][0];

        $new = new Flight();
        $new
            ->setId(ArrayUtils::get('legId',$leg))
            ->setIsMep(ArrayUtils::get('deadHead',$leg))
            ->setNumPax(ArrayUtils::get('nbPax',$flight))
            ->setAircraftReg(ArrayUtils::get('aircraftRegistration',$flight))
            ->setAircraftCode(ArrayUtils::get('aircraftSubtypeCode',$flight))
            ->setDeparture(ArrayUtils::get('departureStationCode',$flight))
            ->setArrival(ArrayUtils::get('arrivalStationCode',$flight))
            ->setFlightNumber(ArrayUtils::get('company',$flight).ArrayUtils::get('flightNumber',$flight))
            ->setDateScheduledDeparture(ArrayUtils::get('scheduledDepartureDate',$flight))
            ->setDateScheduledArrival(ArrayUtils::get('scheduledArrivalDate',$flight))
            ->setDateDeparture(ArrayUtils::get('departureDate',$flight))
            ->setDateArrival(ArrayUtils::get('arrivalDate',$flight));
        return $new;
    }

    private function addToArray(ActivityFlight $activityFlight){
        $this->array_refactored[$activityFlight->getId()] = $activityFlight;
    }

}