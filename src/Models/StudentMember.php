<?php
namespace LibraryManagementSystem\Models;

class StudentMember extends Member {
    public function getBorrowLimit(): int {
        return 3;
    }
    
    public function getLoanPeriod(): int {
        return 14;
    }
    
    public function getLateFeePerDay(): float {
        return 0.50;
    }
}