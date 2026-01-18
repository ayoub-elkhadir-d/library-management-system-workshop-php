<?php
namespace LibraryManagementSystem\Models;

class Book
{
    private $isbn;
    private $title;
    private $publicationYear;
    private $category;
    private $availableCopies;
    private $status;
    private $authors;

    public function __construct($isbn, $title, $publicationYear, $category)
    {
        $this->isbn = $isbn;
        $this->title = $title;
        $this->publicationYear = $publicationYear;
        $this->category = $category;
        $this->availableCopies = 0;
        $this->status = "Available";
        $this->authors = [];
    }

    public function getIsbn()
    {
        return $this->isbn;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getPublicationYear()
    {
        return $this->publicationYear;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function getAvailableCopies()
    {
        return $this->availableCopies;
    }

    public function setAvailableCopies($copies)
    {
        $this->availableCopies = $copies;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function addAuthor($author)
    {
        $this->authors[] = $author;
    }

    public function getAuthors()
    {
        return $this->authors;
    }
}