<?php
namespace LibraryManagementSystem\Models;

class BorrowRecord {
    private int $borrowId;
    private int $memberId;
    private int $bookId;
    private int $branchId;
    private string $borrowDate;
    private string $dueDate;
    private ?string $returnDate;
    private float $lateFee;
    private bool $isRenewed;
    
    public function __construct(
        int $borrowId,
        int $memberId,
        int $bookId,
        int $branchId,
        string $borrowDate,
        string $dueDate,
        ?string $returnDate = null,
        float $lateFee = 0.0,
        bool $isRenewed = false
    ) {
        $this->borrowId = $borrowId;
        $this->memberId = $memberId;
        $this->bookId = $bookId;
        $this->branchId = $branchId;
        $this->borrowDate = $borrowDate;
        $this->dueDate = $dueDate;
        $this->returnDate = $returnDate;
        $this->lateFee = $lateFee;
        $this->isRenewed = $isRenewed;
    }
    
    public function getBorrowId(): int { return $this->borrowId; }
    public function getMemberId(): int { return $this->memberId; }
    public function getBookId(): int { return $this->bookId; }
    public function getBranchId(): int { return $this->branchId; }
    public function getBorrowDate(): string { return $this->borrowDate; }
    public function getDueDate(): string { return $this->dueDate; }
    public function getReturnDate(): ?string { return $this->returnDate; }
    public function getLateFee(): float { return $this->lateFee; }
    public function isRenewed(): bool { return $this->isRenewed; }
    
    public function setReturnDate(string $date): void {
        $this->returnDate = $date;
    }
    
    public function setLateFee(float $fee): void {
        $this->lateFee = $fee;
    }
    
    public function markAsRenewed(): void {
        $this->isRenewed = true;
    }
}