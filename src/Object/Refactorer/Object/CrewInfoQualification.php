<?php

namespace CkAmaury\WorkConnection\Object\Refactorer\Object;

use CkAmaury\PhpDatetime\DateTime;

class CrewInfoQualification {

    public const CAPTAIN = "CPT";
    public const FIRST_OFFICER = "FO";

    private int $id;
    private string $qualification;
    private DateTime $startDate;
    private ?DateTime $endDate = null;
    private string $function;

    public function getId(): int {
        return $this->id;
    }
    public function setId(int $id): self {
        $this->id = $id;
        return $this;
    }

    public function getQualification(): string {
        return $this->qualification;
    }
    public function setQualification(string $qualification): self {
        $this->qualification = $qualification;
        return $this;
    }

    public function getStartDate(): DateTime {
        return $this->startDate;
    }
    public function setStartDate(DateTime $startDate): self {
        $this->startDate = $startDate;
        return $this;
    }

    public function getEndDate(): ?DateTime {
        return $this->endDate;
    }
    public function setEndDate(?DateTime $endDate): self {
        $this->endDate = $endDate;
        return $this;
    }

    public function getFunction(): string {
        return $this->function;
    }
    public function setFunction(string $function): self {
        $this->function = $function;
        return $this;
    }
    public function isFirstOfficer():bool{
        return $this->function == self::FIRST_OFFICER;
    }
    public function isCaptain():bool{
        return $this->function == self::CAPTAIN;
    }



}