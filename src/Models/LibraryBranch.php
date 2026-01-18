<?php
namespace LibraryManagementSystem\Models;

class LibraryBranch
{
    private $branchId;
    private $branchName;
    private $location;
    private $operatingHours;
    private $contactPhone;
    private $booksInventory;

    public function __construct($branchName, $location)
    {
        $this->branchId = uniqid("BRANCH_");
        $this->branchName = $branchName;
        $this->location = $location;
        $this->booksInventory = [];
    }

    public function getBranchId()
    {
        return $this->branchId;
    }

    public function getBranchName()
    {
        return $this->branchName;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function getOperatingHours()
    {
        return $this->operatingHours;
    }

    public function setOperatingHours($hours)
    {
        $this->operatingHours = $hours;
    }

    public function getContactPhone()
    {
        return $this->contactPhone;
    }

    public function setContactPhone($phone)
    {
        $this->contactPhone = $phone;
    }

    public function addBookToInventory($bookIsbn, $copies)
    {
        $this->booksInventory[$bookIsbn] = $copies;
    }

    public function getBookCopies($bookIsbn)
    {
        return isset($this->booksInventory[$bookIsbn]) ? $this->booksInventory[$bookIsbn] : 0;
    }

    public function updateBookCopies($bookIsbn, $newCopies)
    {
        $this->booksInventory[$bookIsbn] = $newCopies;
    }
}