<?php
namespace LibraryManagementSystem\Models;

class Author {
    private int $authorId;
    private string $name;
    private ?string $biography;
    private ?string $nationality;
    private ?string $birthDate;
    private ?string $deathDate;
    private ?string $primaryGenre;
    
    public function __construct(int $authorId, string $name, ?string $biography, ?string $nationality, ?string $birthDate, ?string $deathDate, ?string $primaryGenre) {
        $this->authorId = $authorId;
        $this->name = $name;
        $this->biography = $biography;
        $this->nationality = $nationality;
        $this->birthDate = $birthDate;
        $this->deathDate = $deathDate;
        $this->primaryGenre = $primaryGenre;
    }
    
    public function getAuthorId(): int { return $this->authorId; }
    public function getName(): string { return $this->name; }
    public function getBiography(): ?string { return $this->biography; }
    public function getNationality(): ?string { return $this->nationality; }
    public function getBirthDate(): ?string { return $this->birthDate; }
    public function getDeathDate(): ?string { return $this->deathDate; }
    public function getPrimaryGenre(): ?string { return $this->primaryGenre; }
}