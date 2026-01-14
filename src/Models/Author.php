<?php

namespace LibraryManagementSystem\Models;

class Author extends Person
{
    private ?string $biography;
    private ?string $nationality;
    private ?\DateTime $birthDate;
    private ?\DateTime $deathDate;
    private ?string $primaryGenre;

    public function __construct(
        string $name,
        string $email = '',
        string $biography = null,
        string $nationality = null,
        \DateTime $birthDate = null,
        \DateTime $deathDate = null,
        string $primaryGenre = null,
        string $phone = ''
    ) {
        parent::__construct($name, $email, $phone);
        $this->biography = $biography;
        $this->nationality = $nationality;
        $this->birthDate = $birthDate;
        $this->deathDate = $deathDate;
        $this->primaryGenre = $primaryGenre;
    }

    public function getBiography(): ?string
    {
        return $this->biography;
    }

    public function getNationality(): ?string
    {
        return $this->nationality;
    }

    public function getBirthDate(): ?\DateTime
    {
        return $this->birthDate;
    }

    public function getDeathDate(): ?\DateTime
    {
        return $this->deathDate;
    }

    public function getPrimaryGenre(): ?string
    {
        return $this->primaryGenre;
    }

    public function getAge(): ?int
    {
        if (!$this->birthDate) {
            return null;
        }

        $endDate = $this->deathDate ?? new \DateTime();
        return $endDate->diff($this->birthDate)->y;
    }

    public function isAlive(): bool
    {
        return $this->deathDate === null;
    }
}