<?php

namespace LibraryManagementSystem\Models;

abstract class Person
{
    protected int $id;
    protected string $name;
    protected string $email;
    protected string $phone;

    public function __construct(string $name, string $email, string $phone = '')
    {
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
    }

    
    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    // Setters
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setEmail(string $email): void
    {
      
        $this->email = $email;
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }
}