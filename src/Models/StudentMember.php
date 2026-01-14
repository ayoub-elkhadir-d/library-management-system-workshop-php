<?php

namespace LibraryManagementSystem\Models;

class StudentMember extends Member
{
    private string $studentId;
    private string $yearLevel;
    private string $major;
    private string $academicYear;

    public function __construct(
        string $memberId,
        string $studentId,
        string $name,
        string $email,
        \DateTime $joinDate,
        \DateTime $expireDate,
        string $yearLevel,
        string $major,
        string $academicYear,
        string $phone = ''
    ) {
        parent::__construct($memberId, $name, $email, $joinDate, $expireDate, $phone);
        $this->studentId = $studentId;
        $this->yearLevel = $yearLevel;
        $this->major = $major;
        $this->academicYear = $academicYear;
    }

    public function getBorrowLimit(): int
    {
        return 3;
    }

    public function getLoanPeriod(): int
    {
        return 14; 
    }

    public function getLateFeePerDay(): float
    {
        return 0.50;
    }

    public function getMemberType(): string
    {
        return 'student';
    }

    public function getStudentId(): string
    {
        return $this->studentId;
    }

    public function getYearLevel(): string
    {
        return $this->yearLevel;
    }

    public function getMajor(): string
    {
        return $this->major;
    }

    public function getAcademicYear(): string
    {
        return $this->academicYear;
    }
}