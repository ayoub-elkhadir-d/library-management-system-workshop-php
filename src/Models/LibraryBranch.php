<?php
namespace LibraryManagementSystem\Models;

class LibraryBranch {
    private int $branchId;
    private string $branchName;
    private string $location;
    private ?string $phone;
    private ?string $operatingHours;
    
    public function __construct(int $branchId, string $branchName, string $location, ?string $phone, ?string $operatingHours) {
        $this->branchId = $branchId;
        $this->branchName = $branchName;
        $this->location = $location;
        $this->phone = $phone;
        $this->operatingHours = $operatingHours;
    }
    
    public function getBranchId(): int {
        
    return $this->branchId; 
    
    }
    public function getBranchName(): string {
        
    return $this->branchName; 
    
    }
    public function getLocation(): string {
        
    return $this->location; 
    
    }
    public function getPhone(): ?string {
        
    return $this->phone; 
    
    }
    public function getOperatingHours(): ?string {
        
    return $this->operatingHours; 
    
    }
}