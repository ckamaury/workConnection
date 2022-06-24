<?php

namespace CkAmaury\WorkConnection\Object\Refactorer\Object;

use CkAmaury\PhpDatetime\DateTime;

class IcartInfo{

    private string $lastName;
    private string $firstName;
    private string $matricule;
    private string $function;
    private array $qualifications;
    private bool $isAirFrancePilot;
    private bool $isTransaviaPilot;
    private bool $isTrainee;
    private bool $isExecutive;
    private DateTime $birthDate;
    private DateTime $entryDate;
    private ?DateTime $firstOfficerDate;
    private ?DateTime $captainDate;
    private ?DateTime $atplDate;
    private int $class;
    private ?DateTime $classDate;
    private int $grade;
    private ?DateTime $gradeDate;
    private int $qualificationNumber;
    private ?DateTime $amortissementDate;
    private ?int $lcpRank;
    private int $tta;

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

    public function getFunction(): string {
        return $this->function;
    }
    public function setFunction(string $function): self {
        $this->function = $function;
        return $this;
    }

    public function getQualifications(): array {
        return $this->qualifications;
    }
    public function setQualifications(array $qualifications): self {
        $this->qualifications = $qualifications;
        return $this;
    }

    public function isAirFrancePilot(): bool {
        return $this->isAirFrancePilot;
    }
    public function setIsAirFrancePilot(bool $isAirFrancePilot): self {
        $this->isAirFrancePilot = $isAirFrancePilot;
        return $this;
    }

    public function isTransaviaPilot(): bool {
        return $this->isTransaviaPilot;
    }
    public function setIsTransaviaPilot(bool $isTransaviaPilot): self {
        $this->isTransaviaPilot = $isTransaviaPilot;
        return $this;
    }

    public function isTrainee(): bool {
        return $this->isTrainee;
    }
    public function setIsTrainee(bool $isTrainee): self {
        $this->isTrainee = $isTrainee;
        return $this;
    }

    public function isExecutive(): bool {
        return $this->isExecutive;
    }
    public function setIsExecutive(bool $isExecutive): self {
        $this->isExecutive = $isExecutive;
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

    public function getFirstOfficerDate(): ?DateTime {
        return $this->firstOfficerDate;
    }
    public function setFirstOfficerDate(?DateTime $firstOfficerDate): self {
        $this->firstOfficerDate = $firstOfficerDate;
        return $this;
    }

    public function getCaptainDate(): ?DateTime {
        return $this->captainDate;
    }
    public function setCaptainDate(?DateTime $captainDate): self {
        $this->captainDate = $captainDate;
        return $this;
    }

    public function getAtplDate(): ?DateTime {
        return $this->atplDate;
    }
    public function setAtplDate(?DateTime $atplDate): self {
        $this->atplDate = $atplDate;
        return $this;
    }

    public function getClass(): int {
        return $this->class;
    }
    public function setClass(int $class): self {
        $this->class = $class;
        return $this;
    }

    public function getClassDate(): ?DateTime {
        return $this->classDate;
    }
    public function setClassDate(?DateTime $classDate): self {
        $this->classDate = $classDate;
        return $this;
    }

    public function getGrade(): int {
        return $this->grade;
    }
    public function setGrade(int $grade): self {
        $this->grade = $grade;
        return $this;
    }

    public function getGradeDate(): ?DateTime {
        return $this->gradeDate;
    }
    public function setGradeDate(?DateTime $gradeDate): self {
        $this->gradeDate = $gradeDate;
        return $this;
    }

    public function getQualificationNumber(): int {
        return $this->qualificationNumber;
    }
    public function setQualificationNumber(int $qualificationNumber): self {
        $this->qualificationNumber = $qualificationNumber;
        return $this;
    }

    public function getAmortissementDate(): ?DateTime {
        return $this->amortissementDate;
    }
    public function setAmortissementDate(?DateTime $amortissementDate): self {
        $this->amortissementDate = $amortissementDate;
        return $this;
    }

    public function getLcpRank(): ?int {
        return $this->lcpRank;
    }
    public function setLcpRank(?int $lcpRank): self {
        $this->lcpRank = $lcpRank;
        return $this;
    }

    public function getTta(): int {
        return $this->tta;
    }
    public function setTta(int $tta): self {
        $this->tta = $tta;
        return $this;
    }



}