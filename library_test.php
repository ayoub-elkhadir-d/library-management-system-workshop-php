<?php
require_once 'src/Models/Book.php';
require_once 'src/Models/Author.php';
require_once 'src/Models/Member.php';
require_once 'src/Models/StudentMember.php';
require_once 'src/Models/FacultyMember.php';
require_once 'src/Models/BorrowRecord.php';
require_once 'src/Models/LibraryBranch.php';

require_once 'src/Repositories/DatabaseConnection.php';
require_once 'src/Repositories/BookRepository.php';
require_once 'src/Repositories/MemberRepository.php';
require_once 'src/Repositories/BorrowRepository.php';

require_once 'src/Services/LibraryService.php';

use LibraryManagementSystem\Services\LibraryService;

echo "=== Library Management System Test ===\n\n";

$library = new LibraryService();

echo "Test 1: Register Student Member\n";
try {
    $studentId = $library->registerStudentMember("Ahmed Mohamed", "ahmed@tjest.com", "010124345678", "undergraduate");
    echo "Student registered. ID: $studentId\n\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n\n";
}

echo "Test 2: Register Faculty Member\n";
try {
    $facultyId = $library->registerFacultyMember("Dr. Sara Ali", "sara@univkersity.edu", "017123456789", "Computer Science");
    echo "Faculty registered. ID: $facultyId\n\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n\n";
}

echo "Test 3: Search Books\n";
try {
    $books = $library->searchBooks("PHP");
    echo "Found " . count($books) . " book(s) about PHP\n\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n\n";
}

echo "Test 4: Check Book Availability\n";
try {
    $availability = $library->getBookAvailability("9789776540902");
    echo "Book available in " . count($availability) . " branch(es)\n\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n\n";
}

echo "Test 5: Check Borrow Eligibility\n";
try {
    $eligibility = $library->checkBorrowEligibility($studentId);
    echo "Student eligibility: $eligibility\n\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n\n";
}

echo "Test 6: Borrow Book (Student)\n";
try {
    $recordId = $library->borrowBook($studentId, "9789776540902");
    echo "Book borrowed. Record ID: $recordId\n\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n\n";
}

echo "Test 7: Borrow Book (Faculty)\n";
try {
    $recordId2 = $library->borrowBook($facultyId, "9789773190407");
    echo "Book borrowed. Record ID: $recordId2\n\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n\n";
}

echo "Test 8: Try Borrow Same Book Again\n";
try {
    $recordId3 = $library->borrowBook($studentId, "9789776540902");
    echo "Book borrowed. Record ID: $recordId3\n\n";
} catch (Exception $e) {
    echo "Expected Error: " . $e->getMessage() . "\n\n";
}

echo "Test 9: Get Member Info\n";
try {
    $member = $library->getMemberInfo($studentId);
    echo "Member Name: " . $member->getFullName() . "\n";
    echo "Member Email: " . $member->getEmail() . "\n";
    echo "Membership Valid: " . ($member->isMembershipValid() ? "Yes" : "No") . "\n\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n\n";
}

echo "Test 10: Return Book\n";
try {
    $lateFee = $library->returnBook($recordId);
    echo "Book returned. Late fee: $$lateFee\n\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n\n";
}

echo "Test 11: Get Borrow History\n";
try {
    $history = $library->getMemberBorrowHistory($studentId);
    echo "Borrow history count: " . count($history) . "\n\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n\n";
}

echo "Test 12: Check Overdue Books\n";
try {
    $overdue = $library->getOverdueBooks();
    echo "Total overdue books: " . count($overdue) . "\n\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n\n";
}

echo "Test 13: Renew Membership\n";
try {
    $newExpiry = $library->renewMembership($studentId);
    echo "Membership renewed until: $newExpiry\n\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n\n";
}



echo "=== Test Complete ===\n";