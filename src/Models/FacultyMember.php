<?php
namespace LibraryManagementSystem\Models;

class FacultyMember extends Member
{
    private $department;
    private $maxBooks = 10;
    private $loanPeriod = 30; // 30 يوم
    private $lateFeePerDay = 0.25;

    public function __construct($fullName, $email, $phone, $department)
    {
        parent::__construct($fullName, $email, $phone);
        $this->department = $department;
        // صلاحية العضوية لمدة 3 سنوات
        $this->setMembershipExpiryDate(date('Y-m-d', strtotime('+3 years')));
    }

    public function getDepartment()
    {
        return $this->department;
    }

    public function getMaxBooks()
    {
        return $this->maxBooks;
    }

    public function getLoanPeriod()
    {
        return $this->loanPeriod;
    }

    public function getLateFeePerDay()
    {
        return $this->lateFeePerDay;
    }

    public function canBorrowMoreBooks()
    {
        return count($this->borrowedBooks) < $this->maxBooks;
    }
}