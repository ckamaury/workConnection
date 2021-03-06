<?php

namespace CkAmaury\WorkConnection\Object\Refactorer\Object;

use CkAmaury\PhpDatetime\DateTime;

class CrewInfoAssignment {

    private int $id;
    private string $base;
    private string $function;
    private bool $isInstructor = false;
    private bool $isTrainee = false;
    private DateTime $startDate;
    private ?DateTime $endDate = null;

    public function getId(): int {
        return $this->id;
    }
    public function setId(int $id): self {
        $this->id = $id;
        return $this;
    }

    public function getBase(): string {
        return $this->base;
    }
    public function setBase(string $base): self {
        $this->base = $base;
        return $this;
    }

    public function getFunction(): string {
        return $this->function;
    }
    public function setFunction(string $function): self {
        $this->function = $function;
        return $this;
    }

    public function isInstructor(): bool {
        return $this->isInstructor;
    }
    public function setIsInstructor(bool $isInstructor): self {
        $this->isInstructor = $isInstructor;
        return $this;
    }

    public function isTrainee(): bool {
        return $this->isTrainee;
    }
    public function setIsTrainee(bool $isTrainee): self {
        $this->isTrainee = $isTrainee;
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


}