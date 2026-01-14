<?php
namespace LibraryManagementSystem\Repositories;

use LibraryManagementSystem\Models\BorrowRecord;

class BorrowRepository {
    private \PDO $db;
    
    public function __construct() {
        $this->db = DatabaseConnection::getInstance()->getConnection();
    }
    
    public function create(BorrowRecord $record): int {
        $stmt = $this->db->prepare("
            INSERT INTO borrow_records (member_id, book_id, branch_id, borrow_date, due_date, return_date, late_fee, is_renewed)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $record->getMemberId(),
            $record->getBookId(),
            $record->getBranchId(),
            $record->getBorrowDate(),
            $record->getDueDate(),
            $record->getReturnDate(),
            $record->getLateFee(),
            $record->isRenewed() ? 1 : 0
        ]);
        
        return (int)$this->db->lastInsertId();
    }
    
    public function findActiveBorrow(int $memberId, int $bookId): ?BorrowRecord {
        $stmt = $this->db->prepare("
            SELECT * FROM borrow_records 
            WHERE member_id = ? AND book_id = ? AND return_date IS NULL
        ");
        $stmt->execute([$memberId, $bookId]);
        $data = $stmt->fetch();
        
        if (!$data) return null;
        
        return new BorrowRecord(
            $data['borrow_id'],
            $data['member_id'],
            $data['book_id'],
            $data['branch_id'],
            $data['borrow_date'],
            $data['due_date'],
            $data['return_date'],
            $data['late_fee'],
            (bool)$data['is_renewed']
        );
    }
    
    public function returnBook(int $borrowId, string $returnDate, float $lateFee): bool {
        $stmt = $this->db->prepare("
            UPDATE borrow_records 
            SET return_date = ?, late_fee = ?
            WHERE borrow_id = ?
        ");
        return $stmt->execute([$returnDate, $lateFee, $borrowId]);
    }
    
    public function renewBook(int $borrowId, string $newDueDate): bool {
        $stmt = $this->db->prepare("
            UPDATE borrow_records 
            SET due_date = ?, is_renewed = TRUE
            WHERE borrow_id = ?
        ");
        return $stmt->execute([$newDueDate, $borrowId]);
    }
    
    public function getOverdueBorrows(): array {
        $stmt = $this->db->prepare("
            SELECT br.*, m.full_name, m.email, b.title, lb.branch_name
            FROM borrow_records br
            JOIN members m ON br.member_id = m.member_id
            JOIN books b ON br.book_id = b.book_id
            JOIN library_branches lb ON br.branch_id = lb.branch_id
            WHERE br.return_date IS NULL AND br.due_date < CURDATE()
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function getMemberBorrowHistory(int $memberId): array {
        $stmt = $this->db->prepare("
            SELECT br.*, b.title, b.isbn, lb.branch_name
            FROM borrow_records br
            JOIN books b ON br.book_id = b.book_id
            JOIN library_branches lb ON br.branch_id = lb.branch_id
            WHERE br.member_id = ?
            ORDER BY br.borrow_date DESC
        ");
        $stmt->execute([$memberId]);
        return $stmt->fetchAll();
    }
}