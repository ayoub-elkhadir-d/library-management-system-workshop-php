<?php
namespace LibraryManagementSystem\Repositories;

use LibraryManagementSystem\Models\Book;

class BookRepository
{
    private $db;

    public function __construct()
    {
        $dbConnection = DatabaseConnection::getInstance();
        $this->db = $dbConnection->getConnection();
    }

    public function addBook(Book $book)
    {
        $sql = "INSERT INTO books (isbn, title, publication_year, category, available_copies, status) 
                VALUES (:isbn, :title, :year, :category, :copies, :status)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':isbn' => $book->getIsbn(),
            ':title' => $book->getTitle(),
            ':year' => $book->getPublicationYear(),
            ':category' => $book->getCategory(),
            ':copies' => $book->getAvailableCopies(),
            ':status' => $book->getStatus()
        ]);

        $authors = $book->getAuthors();
        foreach ($authors as $author) {
            $this->addBookAuthor($book->getIsbn(), $author->getId());
        }
    }

    public function findBookByIsbn($isbn)
    {
        $sql = "SELECT * FROM books WHERE isbn = :isbn";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':isbn' => $isbn]);
        
        $data = $stmt->fetch();
        if (!$data) return null;

        $book = new Book($data['isbn'], $data['title'], $data['publication_year'], $data['category']);
        $book->setAvailableCopies($data['available_copies']);
        $book->setStatus($data['status']);
        
        return $book;
    }

    public function searchBooks($searchTerm)
    {
        $sql = "SELECT b.* FROM books b 
                LEFT JOIN book_authors ba ON b.isbn = ba.book_isbn 
                LEFT JOIN authors a ON ba.author_id = a.id 
                WHERE b.title LIKE :term OR b.isbn LIKE :term OR a.name LIKE :term 
                GROUP BY b.isbn";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':term' => "%$searchTerm%"]);
        
        $books = [];
        while ($data = $stmt->fetch()) {
            $book = new Book($data['isbn'], $data['title'], $data['publication_year'], $data['category']);
            $book->setAvailableCopies($data['available_copies']);
            $book->setStatus($data['status']);
            $books[] = $book;
        }
        
        return $books;
    }

    public function updateBookCopies($isbn, $newCopies)
    {
        $sql = "UPDATE books SET available_copies = :copies WHERE isbn = :isbn";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':copies' => $newCopies, ':isbn' => $isbn]);
    }

    public function updateBookStatus($isbn, $status)
    {
        $sql = "UPDATE books SET status = :status WHERE isbn = :isbn";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':status' => $status, ':isbn' => $isbn]);
    }

    private function addBookAuthor($bookIsbn, $authorId)
    {
        $sql = "INSERT INTO book_authors (book_isbn, author_id) VALUES (:book_isbn, :author_id)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':book_isbn' => $bookIsbn, ':author_id' => $authorId]);
    }

    public function getBookAvailability($isbn)
    {
        $sql = "SELECT bi.branch_id, lb.branch_name, bi.copies 
                FROM branch_inventory bi 
                JOIN library_branches lb ON bi.branch_id = lb.branch_id 
                WHERE bi.book_isbn = :isbn AND bi.copies > 0";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':isbn' => $isbn]);
        
        return $stmt->fetchAll();
    }
}