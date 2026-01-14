<?php
namespace LibraryManagementSystem\Models;

class FacultyMember extends Member {
    public function getBorrowLimit(): int {
        return 10;
    }
    
    public function getLoanPeriod(): int {
        return 30; 
    }
    
    public function getLateFeePerDay(): float {
        return 0.25;
    }
}