<?php

namespace CkAmaury\WorkConnection\Object\Refactorer\Object;

class Indemnity {

    private string $airportIata;
    private string $airportCity;
    private string $currency;
    private float $currencyRate;
    private float $mealCost;
    private float $allowanceCost;
    private string $addText;

    public function getAirportIata(): string {
        return $this->airportIata;
    }
    public function setAirportIata(string $airportIata): self {
        $this->airportIata = $airportIata;
        return $this;
    }

    public function getAirportCity(): string {
        return $this->airportCity;
    }
    public function setAirportCity(string $airportCity): self {
        $this->airportCity = $airportCity;
        return $this;
    }

    public function getCurrency(): string {
        return $this->currency;
    }
    public function setCurrency(string $currency): self {
        $this->currency = $currency;
        return $this;
    }

    public function getCurrencyRate(): float {
        return $this->currencyRate;
    }
    public function setCurrencyRate(float $currencyRate): self {
        $this->currencyRate = $currencyRate;
        return $this;
    }

    public function getMealCost(): float {
        return $this->mealCost;
    }
    public function setMealCost(float $mealCost): self {
        $this->mealCost = $mealCost;
        return $this;
    }

    public function getAllowanceCost(): float {
        return $this->allowanceCost;
    }
    public function setAllowanceCost(float $allowanceCost): self {
        $this->allowanceCost = $allowanceCost;
        return $this;
    }

    public function getAddText(): string {
        return $this->addText;
    }
    public function setAddText(string $addText): self {
        $this->addText = $addText;
        return $this;
    }



}