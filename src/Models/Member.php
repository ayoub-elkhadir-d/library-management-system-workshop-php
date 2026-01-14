<?php

namespace LibraryManagementSystem\Models;

abstract class Member extends Person
{
    protected string $memberId;
    protected \DateTime $joinDate;
    protected \DateTime $expireDate;
    protected int $totalBorrowed;
    protected float $totalFines;
    protected array $borrowHistory = [];

    public function __construct(
        string $memberId,
        string $name,
        string $email,
        \DateTime $joinDate,
        \DateTime $expireDate,
        string $phone = ''
    ) {
        parent::__construct($name, $email, $phone);
        $this->memberId = $memberId;
        $this->joinDate = $joinDate;
        $this->expireDate = $expireDate;
        $this->totalBorrowed = 0;
        $this->totalFines = 0.0;
    }

    abstract public function getBorrowLimit(): int;
    abstract public function getLoanPeriod(): int;
    abstract public function getLateFeePerDay(): float;
    abstract public function getMemberType(): string;

    public function getMemberId(): string
    {
        return $this->memberId;
    }

    public function getJoinDate(): \DateTime
    {
        return $this->joinDate;
    }

    public function getExpireDate(): \DateTime
    {
        return $this->expireDate;
    }

    public function getTotalBorrowed(): int
    {
        return $this->totalBorrowed;
    }

    public function getTotalFines(): float
    {
        return $this->totalFines;
    }

    public function isMembershipValid(): bool
    {
        return new \DateTime() <= $this->expireDate;
    }

    public function canBorrow(): bool
    {
        return $this->isMembershipValid() && $this->totalFines <= 10.0;
    }

    public function addBorrowRecord(BorrowRecord $record): void
    {
        $this->borrowHistory[] = $record;
        $this->totalBorrowed++;
    }

    public function addFine(float $amount): void
    {
        $this->totalFines += $amount;
    }

    public function payFine(float $amount): void
    {
        if ($amount > $this->totalFines) {
            echo "Payment amount exceeds total fines";
            return;
        }
        $this->totalFines -= $amount;
    }

    public function renewMembership(\DateTime $newExpireDate): void
    {
        $this->expireDate = $newExpireDate;
    }
}