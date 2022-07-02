<?php

namespace CkAmaury\WorkConnection\Object\Refactorer\Object;

use CkAmaury\PhpDatetime\DateTime;

class CrewInfoExperience {

    private bool $isFlight;
    private string $airport;
    private string $code;
    private DateTime $startDate;
    private int $takeoff;
    private int $landing;
    private string $aircraftSimulator;

    public function isFlight(): bool {
        return $this->isFlight;
    }
    public function setIsFlight(bool $isFlight): self {
        $this->isFlight = $isFlight;
        return $this;
    }

    public function getAirport(): string {
        return $this->airport;
    }
    public function setAirport(?string $airport): self {
        $this->airport = $airport ?? '';
        return $this;
    }

    public function getCode(): string {
        return $this->code;
    }
    public function setCode(?string $code): self {
        $this->code = $code ?? '';
        return $this;
    }

    public function getStartDate(): DateTime {
        return $this->startDate;
    }
    public function setStartDate(DateTime $startDate): self {
        $this->startDate = $startDate;
        return $this;
    }

    public function getTakeoff(): int {
        return $this->takeoff;
    }
    public function setTakeoff(int $takeoff): self {
        $this->takeoff = $takeoff;
        return $this;
    }

    public function getLanding(): int {
        return $this->landing;
    }
    public function setLanding(int $landing): self {
        $this->landing = $landing;
        return $this;
    }

    public function getAircraftSimulator(): string {
        return $this->aircraftSimulator;
    }
    public function setAircraftSimulator(?string $aircraftSimulator): self {
        $this->aircraftSimulator = $aircraftSimulator ?? '';
        return $this;
    }



}