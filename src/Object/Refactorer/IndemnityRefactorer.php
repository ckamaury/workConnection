<?php

namespace CkAmaury\WorkConnection\Object\Refactorer;

use CkAmaury\WorkConnection\Object\Refactorer\Object\Indemnity;
use CkAmaury\PhpMagicFunctions\ArrayUtils;

class IndemnityRefactorer {

    /** @return Indemnity[] */
    public function refactor(array $data) : array{

        $indemnities = array();
        foreach($data as $item){
            $indemnity = new Indemnity();
            $indemnity
                ->setAirportIata(ArrayUtils::get('stationCode',$item))
                ->setAirportCity(ArrayUtils::get('stopover',$item))
                ->setCurrency(ArrayUtils::get('localCurrency',$item))
                ->setCurrencyRate(ArrayUtils::get('exchangeRate',$item))
                ->setMealCost(ArrayUtils::get('mealCosts',$item))
                ->setAllowanceCost(ArrayUtils::get('allowance',$item))
                ->setAddText(ArrayUtils::get('additionalCompensation',$item) ?? '');
            $indemnities[$indemnity->getAirportIata()] = $indemnity;
        }

        return $indemnities;

    }

}