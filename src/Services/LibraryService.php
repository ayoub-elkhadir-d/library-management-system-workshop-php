<?php
namespace LibraryManagementSystem\Services;

use LibraryManagementSystem\Models\BorrowRecord;
use LibraryManagementSystem\Repositories\BookRepository;
use LibraryManagementSystem\Repositories\MemberRepository;

class LibraryService {
    private \PDO $db;
    private BookRepository $bookRepository;
    private MemberRepository $memberRepository;
    private BorrowRepository $borrowRepository;
    
    public function __construct() {
      
        $this->db = DatabaseConnection::getInstance()->getConnection();
        
        $this->bookRepository = new BookRepository();
        $this->memberRepository = new MemberRepository();
        $this->borrowRepository = new BorrowRepository();
    }
    
    public function borrowBook(int $memberId, int $bookId, int $branchId): BorrowRecord {
        $this->db->beginTransaction();
        
        try {
      
            $member = $this->memberRepository->findById($memberId);
            if (!$member) {
                throw new \Exception("Member not found");
            }
            
 
            if (strtotime($member->getMembershipEndDate()) < time()) {
                throw new \Exception("Membership has expired");
            }
           
            if ($member->getUnpaidFees() > 10) {
                throw new LateFeeException("Unpaid fees exceed $10");
            }
            
          
            $currentBorrowCount = $this->memberRepository->getCurrentBorrowCount($memberId);
            if ($currentBorrowCount >= $member->getBorrowLimit()) {
                throw new MemberLimitExceededException();
            }
            
         
            $book = $this->bookRepository->findById($bookId);
            if (!$book) {
                throw new \Exception("Book not found");
            }
            
            $availableCopies = $this->bookRepository->getAvailableCopies($bookId, $branchId);
            if ($availableCopies <= 0) {
                throw new BookUnavailableException();
            }
            
       
            $borrowDate = date('Y-m-d');
            $dueDate = date('Y-m-d', strtotime("+{$member->getLoanPeriod()} days"));
            
            $borrowRecord = new BorrowRecord(
                0,
                $memberId,
                $bookId,
                $branchId,
                $borrowDate,
                $dueDate
            );
            
            $borrowId = $this->borrowRepository->create($borrowRecord);
            $borrowRecord = new BorrowRecord(
                $borrowId,
                $memberId,
                $bookId,
                $branchId,
                $borrowDate,
                $dueDate
            );
            
          
            $this->bookRepository->decreaseAvailableCopies($bookId, $branchId);

            $this->bookRepository->updateBookStatus($bookId, 'Checked Out');
            
          
            $this->memberRepository->incrementBorrowCount($memberId);
            
            $this->db->commit();
            return $borrowRecord;
            
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    
   
}