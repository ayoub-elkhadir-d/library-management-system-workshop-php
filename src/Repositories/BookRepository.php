<?php
namespace LibraryManagementSystem\Repositories;

use LibraryManagementSystem\Models\Book;

class BookRepository {
    private \PDO $db;
    
    public function __construct() {
        $this->db = DatabaseConnection::getInstance()->getConnection();
    }
    
    public function findById(int $bookId): ?Book {
        $stmt = $this->db->prepare("SELECT * FROM books WHERE book_id = ?");
        $stmt->execute([$bookId]);
        $data = $stmt->fetch();
        
        if (!$data) return null;
        
        return new Book(
            $data['book_id'],
            $data['isbn'],
            $data['title'],
            $data['publication_year'],
            $data['category_id'],
            $data['status']
        );
    }
    
    public function findByIsbn(string $isbn): ?Book {
        $stmt = $this->db->prepare("SELECT * FROM books WHERE isbn = ?");
        $stmt->execute([$isbn]);
        $data = $stmt->fetch();
        
        if (!$data) return null;
        
        return new Book(
            $data['book_id'],
            $data['isbn'],
            $data['title'],
            $data['publication_year'],
            $data['category_id'],
            $data['status']
        );
    }
    
    public function searchByTitle(string $title): array {
        $stmt = $this->db->prepare("SELECT * FROM books WHERE title LIKE ?");
        $stmt->execute(["%$title%"]);
        $books = [];
        
        while ($data = $stmt->fetch()) {
            $books[] = new Book(
                $data['book_id'],
                $data['isbn'],
                $data['title'],
                $data['publication_year'],
                $data['category_id'],
                $data['status']
            );
        }
        
        return $books;
    }
    
    public function updateBookStatus(int $bookId, string $status): bool {
        $stmt = $this->db->prepare("UPDATE books SET status = ? WHERE book_id = ?");
        return $stmt->execute([$status, $bookId]);
    }
    
    public function getAvailableCopies(int $bookId, int $branchId): int {
        $stmt = $this->db->prepare("SELECT available_copies FROM book_copies WHERE book_id = ? AND branch_id = ?");
        $stmt->execute([$bookId, $branchId]);
        $data = $stmt->fetch();
        
        return $data ? $data['available_copies'] : 0;
    }
    
    public function decreaseAvailableCopies(int $bookId, int $branchId): bool {
        $stmt = $this->db->prepare("UPDATE book_copies SET available_copies = available_copies - 1 WHERE book_id = ? AND branch_id = ? AND available_copies > 0");
        return $stmt->execute([$bookId, $branchId]);
    }
    
    public function increaseAvailableCopies(int $bookId, int $branchId): bool {
        $stmt = $this->db->prepare("UPDATE book_copies SET available_copies = available_copies + 1 WHERE book_id = ? AND branch_id = ?");
        return $stmt->execute([$bookId, $branchId]);
    }
}