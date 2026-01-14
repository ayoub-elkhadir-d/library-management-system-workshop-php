<?php

require_once __DIR__ . '/../vendor/autoload.php';

use LibraryManagementSystem\Models\StudentMember;
use LibraryManagementSystem\Models\FacultyMember;
use LibraryManagementSystem\Services\LibraryService;

// Display errors for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== Library Management System Test ===\n\n";

try {
    // Initialize service with all dependencies
    $libraryService = new LibraryService();

    echo "Test 1: Searching for books\n";
    echo "---------------------------\n";
    
    // Search for books
    $books = $libraryService->searchBooks(['title' => 'Harry Potter']);
    
    if (empty($books)) {
        echo "No books found. Make sure you have run the database setup scripts.\n";
        echo "Run these commands:\n";
        echo "mysql -u root -p < database/schema.sql\n";
        echo "mysql -u root -p < database/sample_data.sql\n";
    } else {
        echo "Found " . count($books) . " books:\n";
        foreach ($books as $book) {
            echo "- " . $book->getTitle() . " (ISBN: " . $book->getIsbn() . ")\n";
        }
    }
    
    echo "\n";
    
    echo "Test 2: Create a test student member\n";
    echo "-----------------------------------\n";
    
    // Create a test student
    $student = new StudentMember(
        'TEST001',
        'T123456',
        'Test Student',
        'test.student@university.edu',
        new DateTime('2024-01-01'),
        new DateTime('2024-12-31'),
        'Sophomore',
        'Computer Science',
        '2024-2025',
        '555-9999'
    );
    
    echo "Created student: " . $student->getName() . "\n";
    echo "Student ID: " . $student->getStudentId() . "\n";
    echo "Borrow limit: " . $student->getBorrowLimit() . " books\n";
    echo "Loan period: " . $student->getLoanPeriod() . " days\n";
    
    echo "\n";
    
    echo "Test 3: Create a test faculty member\n";
    echo "-----------------------------------\n";
    
    $faculty = new FacultyMember(
        'TEST002',
        'T987654',
        'Test Professor',
        'test.professor@university.edu',
        new DateTime('2024-01-01'),
        new DateTime('2027-12-31'),
        'Computer Science',
        'Professor',
        '555-8888'
    );
    
    echo "Created faculty: " . $faculty->getName() . "\n";
    echo "Employee ID: " . $faculty->getEmployeeId() . "\n";
    echo "Borrow limit: " . $faculty->getBorrowLimit() . " books\n";
    echo "Loan period: " . $faculty->getLoanPeriod() . " days\n";
    
    echo "\n";
    echo "All tests completed successfully!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    
    if ($e->getPrevious()) {
        echo "Previous error: " . $e->getPrevious()->getMessage() . "\n";
    }
}