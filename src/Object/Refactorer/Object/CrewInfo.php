<?php

namespace CkAmaury\WorkConnection\Object\Refactorer\Object;

use CkAmaury\PhpDatetime\DateTime;

class CrewInfo {

    private string $lastName;
    private string $firstName;
    private string $matricule;
    private bool $isMale;
    private DateTime $birthDate;
    private DateTime $entryDate;
    private bool $isInstructor;
    private ?DateTime $atplDate;

    private array $qualifications = array();
    private array $assignments = array();
    private array $experiences = array();

    public function getLastName(): string {
        return $this->lastName;
    }
    public function setLastName(string $lastName): self {
        $this->lastName = $lastName;
        return $this;
    }

    public function getFirstName(): string {
        return $this->firstName;
    }
    public function setFirstName(string $firstName): self {
        $this->firstName = $firstName;
        return $this;
    }

    public function getMatricule(): string {
        return $this->matricule;
    }
    public function setMatricule(string $matricule): self {
        $this->matricule = $matricule;
        return $this;
    }

    public function isMale(): bool {
        return $this->isMale;
    }
    public function setIsMale(bool $isMale): self {
        $this->isMale = $isMale;
        return $this;
    }

    public function getBirthDate(): DateTime {
        return $this->birthDate;
    }
    public function setBirthDate(DateTime $birthDate): self {
        $this->birthDate = $birthDate;
        return $this;
    }

    public function getEntryDate(): DateTime {
        return $this->entryDate;
    }
    public function setEntryDate(DateTime $entryDate): self {
        $this->entryDate = $entryDate;
        return $this;
    }

    public function isInstructor(): bool {
        return $this->isInstructor;
    }
    public function setIsInstructor(bool $isInstructor): self {
        $this->isInstructor = $isInstructor;
        return $this;
    }

    public function getAtplDate(): ?DateTime {
        return $this->atplDate;
    }
    public function setAtplDate(?DateTime $atplDate): self {
        $this->atplDate = $atplDate;
        return $this;
    }

    public function getQualifications(): array {
        return $this->qualifications;
    }
    public function setQualifications(array $qualifications): self {
        $this->qualifications = $qualifications;
        return $this;
    }

    public function getAssignments(): array {
        return $this->assignments;
    }
    public function setAssignments(array $assignments): self {
        $this->assignments = $assignments;
        return $this;
    }

    public function getExperiences(): array {
        return $this->experiences;
    }
    public function setExperiences(array $experiences): self {
        $this->experiences = $experiences;
        return $this;
    }

}