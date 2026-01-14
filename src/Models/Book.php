<?php
namespace LibraryManagementSystem\Models;

class Book {
    private int $bookId;
    private string $isbn;
    private string $title;
    private int $publicationYear;
    private int $categoryId;
    private string $status;
    
    public function __construct(int $bookId, string $isbn, string $title, int $publicationYear, int $categoryId, string $status) {
        $this->bookId = $bookId;
        $this->isbn = $isbn;
        $this->title = $title;
        $this->publicationYear = $publicationYear;
        $this->categoryId = $categoryId;
        $this->status = $status;
    }
    
    public function getBookId(): int {
        
    return $this->bookId; 
    }
    public function getIsbn(): string {
        
    return $this->isbn; 
    }
    public function getTitle(): string {
        
    return $this->title; 
    }
    public function getPublicationYear(): int {
        
    return $this->publicationYear; 
    }
    public function getCategoryId(): int {
        
    return $this->categoryId; 
    }
    public function getStatus(): string {
        
    return $this->status; 
    }
    
    public function setStatus(string $status): void {


        $this->status = $status;
    }
}