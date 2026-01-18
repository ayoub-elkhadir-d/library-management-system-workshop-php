<?php
namespace LibraryManagementSystem\Models;

class Member
{
    protected $memberId;
    protected $fullName;
    protected $email;
    protected $phone;
    protected $membershipExpiryDate;
    protected $borrowedBooks;
    protected $totalBorrowedHistory;

    public function __construct($fullName, $email, $phone)
    {
        $this->memberId = uniqid("MEM_");
        $this->fullName = $fullName;
        $this->email = $email;
        $this->phone = $phone;
        $this->borrowedBooks = [];
        $this->totalBorrowedHistory = 0;
    }

    public function getMemberId()
    {
        return $this->memberId;
    }

    public function getFullName()
    {
        return $this->fullName;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function getMembershipExpiryDate()
    {
        return $this->membershipExpiryDate;
    }

    public function setMembershipExpiryDate($date)
    {
        $this->membershipExpiryDate = $date;
    }

    public function getBorrowedBooks()
    {
        return $this->borrowedBooks;
    }

    public function addBorrowedBook($book)
    {
        $this->borrowedBooks[] = $book;
        $this->totalBorrowedHistory++;
    }

    public function removeBorrowedBook($bookIsbn)
    {
        foreach ($this->borrowedBooks as $key => $book) {
            if ($book->getIsbn() === $bookIsbn) {
                unset($this->borrowedBooks[$key]);
                break;
            }
        }
    }

    public function getTotalBorrowedHistory()
    {
        return $this->totalBorrowedHistory;
    }

    public function isMembershipValid()
    {
        if (!$this->membershipExpiryDate) {
            return false;
        }
        return strtotime($this->membershipExpiryDate) >= time();
    }
}