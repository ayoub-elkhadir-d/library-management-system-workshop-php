<?php
namespace LibraryManagementSystem\Models;

abstract class Member {
    protected int $memberId;
    protected string $memberNumber;
    protected string $fullName;
    protected string $email;
    protected ?string $phone;
    protected string $membershipType;
    protected string $membershipStartDate;
    protected string $membershipEndDate;
    protected int $totalBorrowedCount;
    protected float $unpaidFees;
    
    public function __construct(
        int $memberId,
        string $memberNumber,
        string $fullName,
        string $email,
        ?string $phone,
        string $membershipType,
        string $membershipStartDate,
        string $membershipEndDate,
        int $totalBorrowedCount = 0,
        float $unpaidFees = 0.0
    ) {
        $this->memberId = $memberId;
        $this->memberNumber = $memberNumber;
        $this->fullName = $fullName;
        $this->email = $email;
        $this->phone = $phone;
        $this->membershipType = $membershipType;
        $this->membershipStartDate = $membershipStartDate;
        $this->membershipEndDate = $membershipEndDate;
        $this->totalBorrowedCount = $totalBorrowedCount;
        $this->unpaidFees = $unpaidFees;
    }
    
    public function getMemberId(): int { return $this->memberId; }
    public function getMemberNumber(): string { return $this->memberNumber; }
    public function getFullName(): string { return $this->fullName; }
    public function getEmail(): string { return $this->email; }
    public function getPhone(): ?string { return $this->phone; }
    public function getMembershipType(): string { return $this->membershipType; }
    public function getMembershipStartDate(): string { return $this->membershipStartDate; }
    public function getMembershipEndDate(): string { return $this->membershipEndDate; }
    public function getTotalBorrowedCount(): int { return $this->totalBorrowedCount; }
    public function getUnpaidFees(): float { return $this->unpaidFees; }
    
    abstract public function getBorrowLimit(): int;
    abstract public function getLoanPeriod(): int;
    abstract public function getLateFeePerDay(): float;
    
    public function addUnpaidFee(float $amount): void {
        $this->unpaidFees += $amount;
    }
    
    public function payFee(float $amount): void {
        $this->unpaidFees -= $amount;
        if ($this->unpaidFees < 0) {
            $this->unpaidFees = 0;
        }
    }
    
    public function incrementBorrowCount(): void {
        $this->totalBorrowedCount++;
    }
    
    public function decrementBorrowCount(): void {
        $this->totalBorrowedCount--;
    }
}