<?php
namespace LibraryManagementSystem\Models;

class Author
{
    private $id;
    private $name;
    private $biography;
    private $nationality;
    private $birthDate;
    private $deathDate;
    private $primaryGenre;

    public function __construct($name, $nationality, $primaryGenre)
    {
        $this->id = uniqid();
        $this->name = $name;
        $this->nationality = $nationality;
        $this->primaryGenre = $primaryGenre;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getBiography()
    {
        return $this->biography;
    }

    public function setBiography($biography)
    {
        $this->biography = $biography;
    }

    public function getNationality()
    {
        return $this->nationality;
    }

    public function getBirthDate()
    {
        return $this->birthDate;
    }

    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;
    }

    public function getDeathDate()
    {
        return $this->deathDate;
    }

    public function setDeathDate($deathDate)
    {
        $this->deathDate = $deathDate;
    }

    public function getPrimaryGenre()
    {
        return $this->primaryGenre;
    }
}