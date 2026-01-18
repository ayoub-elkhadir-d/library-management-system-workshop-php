<?php
namespace LibraryManagementSystem\Repositories;

use LibraryManagementSystem\Models\BorrowRecord;

class BorrowRepository
{
    private $db;

    public function __construct()
    {
        $dbConnection = DatabaseConnection::getInstance();
        $this->db = $dbConnection->getConnection();
    }

    public function addBorrowRecord(BorrowRecord $record)
    {
        $sql = "INSERT INTO borrow_records (record_id, member_id, book_isbn, borrow_date, due_date, return_date, late_fee) 
                VALUES (:id, :member_id, :isbn, :borrow_date, :due_date, :return_date, :late_fee)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id' => $record->getRecordId(),
            ':member_id' => $record->getMemberId(),
            ':isbn' => $record->getBookIsbn(),
            ':borrow_date' => $record->getBorrowDate(),
            ':due_date' => $record->getDueDate(),
            ':return_date' => $record->getReturnDate(),
            ':late_fee' => $record->getLateFee()
        ]);
    }

    public function findActiveBorrows($memberId)
    {
        $sql = "SELECT * FROM borrow_records 
                WHERE member_id = :member_id AND return_date IS NULL";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':member_id' => $memberId]);
        
        $records = [];
        while ($data = $stmt->fetch()) {
            $record = new BorrowRecord($data['member_id'], $data['book_isbn'], 0);
            $record->setReturnDate($data['return_date']);
            $record->setLateFee($data['late_fee']);
            $records[] = $record;
        }
        
        return $records;
    }

    public function getBorrowRecord($recordId)
    {
        $sql = "SELECT * FROM borrow_records WHERE record_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $recordId]);
        
        $data = $stmt->fetch();
        if (!$data) return null;

        $record = new BorrowRecord($data['member_id'], $data['book_isbn'], 0);
        $record->setReturnDate($data['return_date']);
        $record->setLateFee($data['late_fee']);
        
        return $record;
    }

    public function returnBook($recordId, $returnDate, $lateFee)
    {
        $sql = "UPDATE borrow_records SET return_date = :return_date, late_fee = :late_fee 
                WHERE record_id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':return_date' => $returnDate,
            ':late_fee' => $lateFee,
            ':id' => $recordId
        ]);
    }

    public function getOverdueBooks()
    {
        $sql = "SELECT br.*, m.full_name, b.title 
                FROM borrow_records br 
                JOIN members m ON br.member_id = m.member_id 
                JOIN books b ON br.book_isbn = b.isbn 
                WHERE br.return_date IS NULL AND br.due_date < CURDATE()";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function getMemberOverdueBooks($memberId)
    {
        $sql = "SELECT br.*, b.title 
                FROM borrow_records br 
                JOIN books b ON br.book_isbn = b.isbn 
                WHERE br.member_id = :member_id 
                AND br.return_date IS NULL 
                AND br.due_date < CURDATE()";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':member_id' => $memberId]);
        
        return $stmt->fetchAll();
    }

    public function getTotalLateFees($memberId)
    {
        $sql = "SELECT SUM(late_fee) as total FROM borrow_records 
                WHERE member_id = :member_id AND late_fee > 0";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':member_id' => $memberId]);
        
        $data = $stmt->fetch();
        return $data['total'] ? $data['total'] : 0;
    }

    public function canBorrowBook($memberId, $bookIsbn)
    {
        $sql = "SELECT COUNT(*) as count FROM borrow_records 
                WHERE member_id = :member_id AND book_isbn = :isbn AND return_date IS NULL";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':member_id' => $memberId, ':isbn' => $bookIsbn]);
        
        $data = $stmt->fetch();
        return $data['count'] == 0;
    }
}