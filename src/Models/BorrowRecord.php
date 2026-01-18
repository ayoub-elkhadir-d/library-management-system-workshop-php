<?php
namespace LibraryManagementSystem\Models;

class BorrowRecord
{
    private $recordId;
    private $memberId;
    private $bookIsbn;
    private $borrowDate;
    private $dueDate;
    private $returnDate;
    private $lateFee;

    public function __construct($memberId, $bookIsbn, $loanPeriodDays)
    {
        $this->recordId = uniqid("BR_");
        $this->memberId = $memberId;
        $this->bookIsbn = $bookIsbn;
        $this->borrowDate = date('Y-m-d');
        $this->dueDate = date('Y-m-d', strtotime("+$loanPeriodDays days"));
        $this->lateFee = 0;
    }

    public function getRecordId()
    {
        return $this->recordId;
    }

    public function getMemberId()
    {
        return $this->memberId;
    }

    public function getBookIsbn()
    {
        return $this->bookIsbn;
    }

    public function getBorrowDate()
    {
        return $this->borrowDate;
    }

    public function getDueDate()
    {
        return $this->dueDate;
    }

    public function getReturnDate()
    {
        return $this->returnDate;
    }

    public function setReturnDate($date)
    {
        $this->returnDate = $date;
    }

    public function getLateFee()
    {
        return $this->lateFee;
    }

    public function setLateFee($fee)
    {
        $this->lateFee = $fee;
    }

    public function isOverdue()
    {
        if ($this->returnDate) {
            return strtotime($this->returnDate) > strtotime($this->dueDate);
        }
        return strtotime(date('Y-m-d')) > strtotime($this->dueDate);
    }

    public function calculateLateFee($feePerDay)
    {
        if ($this->returnDate) {
            $daysLate = (strtotime($this->returnDate) - strtotime($this->dueDate)) / (60 * 60 * 24);
        } else {
            $daysLate = (time() - strtotime($this->dueDate)) / (60 * 60 * 24);
        }

        if ($daysLate > 0) {
            return round($daysLate * $feePerDay, 2);
        }
        return 0;
    }
}