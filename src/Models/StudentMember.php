<?php
namespace LibraryManagementSystem\Models;

class StudentMember extends Member
{
    private $studentType; // undergraduate أو graduate
    private $maxBooks = 3;
    private $loanPeriod = 14; // 14 يوم
    private $lateFeePerDay = 0.50;

    public function __construct($fullName, $email, $phone, $studentType)
    {
        parent::__construct($fullName, $email, $phone);
        $this->studentType = $studentType;
        // صلاحية العضوية لمدة سنة دراسية (نضيف سنة واحدة من اليوم)
        $this->setMembershipExpiryDate(date('Y-m-d', strtotime('+1 year')));
    }

    public function getStudentType()
    {
        return $this->studentType;
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