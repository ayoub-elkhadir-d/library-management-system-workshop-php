<?php
namespace LibraryManagementSystem\Services;

use LibraryManagementSystem\Repositories\BookRepository;
use LibraryManagementSystem\Repositories\MemberRepository;
use LibraryManagementSystem\Repositories\BorrowRepository;
use LibraryManagementSystem\Models\StudentMember;
use LibraryManagementSystem\Models\FacultyMember;
use LibraryManagementSystem\Models\BorrowRecord;
use LibraryManagementSystem\Exceptions\LibraryException;

class LibraryService
{
    private $bookRepo;
    private $memberRepo;
    private $borrowRepo;

    public function __construct()
    {
        $this->bookRepo = new BookRepository();
        $this->memberRepo = new MemberRepository();
        $this->borrowRepo = new BorrowRepository();
    }

    public function registerStudentMember($fullName, $email, $phone, $studentType)
    {
        $existing = $this->memberRepo->findMemberByEmail($email);
        if ($existing) {
            //// throw new LibraryException("Email already registered");
        }

        $member = new StudentMember($fullName, $email, $phone, $studentType);
        $this->memberRepo->addMember($member);
        
        return $member->getMemberId();
    }

    public function registerFacultyMember($fullName, $email, $phone, $department)
    {
        $existing = $this->memberRepo->findMemberByEmail($email);
        if ($existing) {
            //// throw new LibraryException("Email already registered");
        }

        $member = new FacultyMember($fullName, $email, $phone, $department);
        $this->memberRepo->addMember($member);
        
        return $member->getMemberId();
    }

    public function borrowBook($memberId, $bookIsbn)
    {
        $member = $this->memberRepo->findMemberById($memberId);
        if (!$member) {
           // throw new LibraryException("Member not found");
        }

        if (!$member->isMembershipValid()) {
           // throw new LibraryException("Membership expired");
        }

        $book = $this->bookRepo->findBookByIsbn($bookIsbn);
        if (!$book) {
           // throw new LibraryException("Book not found");
        }

        if ($book->getAvailableCopies() <= 0) {
           // throw new LibraryException("Book not available");
        }

        $currentBorrows = $this->borrowRepo->findActiveBorrows($memberId);
        if (count($currentBorrows) >= $member->getMaxBooks()) {
           // throw new LibraryException("Borrow limit reached");
        }

        if (!$this->borrowRepo->canBorrowBook($memberId, $bookIsbn)) {
           // throw new LibraryException("Already borrowed this book");
        }

        $totalFees = $this->borrowRepo->getTotalLateFees($memberId);
        if ($totalFees > 10) {
           // throw new LibraryException("Unpaid fees exceed limit");
        }

        $record = new BorrowRecord($memberId, $bookIsbn, $member->getLoanPeriod());
        $this->borrowRepo->addBorrowRecord($record);

        $newCopies = $book->getAvailableCopies() - 1;
        $this->bookRepo->updateBookCopies($bookIsbn, $newCopies);

        if ($newCopies == 0) {
            $this->bookRepo->updateBookStatus($bookIsbn, "Checked Out");
        }

        $member->addBorrowedBook($book);
        $newCount = $member->getTotalBorrowedHistory();
        $this->memberRepo->updateBorrowHistoryCount($memberId, $newCount);

        return $record->getRecordId();
    }

    public function returnBook($recordId)
    {
        $record = $this->borrowRepo->getBorrowRecord($recordId);
        if (!$record) {
           // throw new LibraryException("Borrow record not found");
        }

        if ($record->getReturnDate()) {
           // throw new LibraryException("Book already returned");
        }

        $member = $this->memberRepo->findMemberById($record->getMemberId());
        $book = $this->bookRepo->findBookByIsbn($record->getBookIsbn());

        $lateFee = $record->calculateLateFee($member->getLateFeePerDay());
        $returnDate = date('Y-m-d');

        $record->setReturnDate($returnDate);
        $record->setLateFee($lateFee);

        $this->borrowRepo->returnBook($recordId, $returnDate, $lateFee);

        $newCopies = $book->getAvailableCopies() + 1;
        $this->bookRepo->updateBookCopies($record->getBookIsbn(), $newCopies);

        if ($book->getStatus() == "Checked Out" && $newCopies > 0) {
            $this->bookRepo->updateBookStatus($record->getBookIsbn(), "Available");
        }

        $member->removeBorrowedBook($record->getBookIsbn());

        return $lateFee;
    }

    public function searchBooks($searchTerm)
    {
        return $this->bookRepo->searchBooks($searchTerm);
    }

    public function getBookAvailability($isbn)
    {
        return $this->bookRepo->getBookAvailability($isbn);
    }

    public function getMemberInfo($memberId)
    {
        return $this->memberRepo->findMemberById($memberId);
    }

    public function getMemberBorrowHistory($memberId)
    {
        return $this->memberRepo->getMemberBorrowHistory($memberId);
    }

    public function getOverdueBooks()
    {
        return $this->borrowRepo->getOverdueBooks();
    }

    public function renewMembership($memberId)
    {
        $member = $this->memberRepo->findMemberById($memberId);
        if (!$member) {
           // throw new LibraryException("Member not found");
        }

        $totalFees = $this->borrowRepo->getTotalLateFees($memberId);
        if ($totalFees > 0) {
           // throw new LibraryException("Cannot renew with unpaid fees");
        }

        if ($member instanceof StudentMember) {
            $newExpiry = date('Y-m-d', strtotime('+1 year'));
        } else {
            $newExpiry = date('Y-m-d', strtotime('+3 years'));
        }

        $this->memberRepo->renewMembership($memberId, $newExpiry);
        $member->setMembershipExpiryDate($newExpiry);

        return $newExpiry;
    }

    public function checkBorrowEligibility($memberId)
    {
        $member = $this->memberRepo->findMemberById($memberId);
        if (!$member) return "Member not found";

        if (!$member->isMembershipValid()) return "Membership expired";

        $currentBorrows = $this->borrowRepo->findActiveBorrows($memberId);
        if (count($currentBorrows) >= $member->getMaxBooks()) {
            return "Borrow limit reached";
        }

        $totalFees = $this->borrowRepo->getTotalLateFees($memberId);
        if ($totalFees > 10) return "Unpaid fees exceed limit";

        $overdue = $this->borrowRepo->getMemberOverdueBooks($memberId);
        if (count($overdue) > 0) return "Has overdue books";

        return "Eligible to borrow";
    }
}