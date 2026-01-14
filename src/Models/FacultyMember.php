<?php

namespace LibraryManagementSystem\Models;

class FacultyMember extends Member
{
    private string $employeeId;
    private string $department;
    private string $position;

    public function __construct(
        string $memberId,
        string $employeeId,
        string $name,
        string $email,
        \DateTime $joinDate,
        \DateTime $expireDate,
        string $department,
        string $position,
        string $phone = ''
    ) {
        parent::__construct($memberId, $name, $email, $joinDate, $expireDate, $phone);
        $this->employeeId = $employeeId;
        $this->department = $department;
        $this->position = $position;
    }

    public function getBorrowLimit(): int
    {
        return 10;
    }

    public function getLoanPeriod(): int
    {
        return 30; 
    }

    public function getLateFeePerDay(): float
    {
        return 0.25;
    }

    public function getMemberType(): string
    {
        return 'faculty';
    }

    public function getEmployeeId(): string
    {
        return $this->employeeId;
    }

    public function getDepartment(): string
    {
        return $this->department;
    }

    public function getPosition(): string
    {
        return $this->position;
    }
}