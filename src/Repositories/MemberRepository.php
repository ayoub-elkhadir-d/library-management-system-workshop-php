<?php
namespace LibraryManagementSystem\Repositories;

use LibraryManagementSystem\Models\StudentMember;
use LibraryManagementSystem\Models\FacultyMember;

class MemberRepository
{
    private $db;

    public function __construct()
    {
        $dbConnection = DatabaseConnection::getInstance();
        $this->db = $dbConnection->getConnection();
    }

    public function addMember($member)
    {
        $sql = "INSERT INTO members (member_id, full_name, email, phone, membership_expiry_date, member_type, student_type, department) 
                VALUES (:id, :name, :email, :phone, :expiry, :type, :student_type, :department)";
        
        $memberType = get_class($member);
        
        if ($memberType === 'LibraryManagementSystem\Models\StudentMember') {
            $type = 'student';
            $studentType = $member->getStudentType();
            $department = null;
        } else {
            $type = 'faculty';
            $studentType = null;
            $department = $member->getDepartment();
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id' => $member->getMemberId(),
            ':name' => $member->getFullName(),
            ':email' => $member->getEmail(),
            ':phone' => $member->getPhone(),
            ':expiry' => $member->getMembershipExpiryDate(),
            ':type' => $type,
            ':student_type' => $studentType,
            ':department' => $department
        ]);
    }

    public function findMemberById($memberId)
    {
        $sql = "SELECT * FROM members WHERE member_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $memberId]);
        
        $data = $stmt->fetch();
        if (!$data) return null;

        if ($data['member_type'] === 'student') {
            $member = new StudentMember($data['full_name'], $data['email'], $data['phone'], $data['student_type']);
        } else {
            $member = new FacultyMember($data['full_name'], $data['email'], $data['phone'], $data['department']);
        }
        
        $member->setMembershipExpiryDate($data['membership_expiry_date']);
        
        return $member;
    }

    public function findMemberByEmail($email)
    {
        $sql = "SELECT * FROM members WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        
        $data = $stmt->fetch();
        if (!$data) return null;

        if ($data['member_type'] === 'student') {
            $member = new StudentMember($data['full_name'], $data['email'], $data['phone'], $data['student_type']);
        } else {
            $member = new FacultyMember($data['full_name'], $data['email'], $data['phone'], $data['department']);
        }
        
        $member->setMembershipExpiryDate($data['membership_expiry_date']);
        
        return $member;
    }

    public function updateMemberContact($memberId, $email, $phone)
    {
        $sql = "UPDATE members SET email = :email, phone = :phone WHERE member_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email, ':phone' => $phone, ':id' => $memberId]);
    }

    public function renewMembership($memberId, $newExpiryDate)
    {
        $sql = "UPDATE members SET membership_expiry_date = :expiry WHERE member_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':expiry' => $newExpiryDate, ':id' => $memberId]);
    }

    public function getMemberBorrowHistory($memberId)
    {
        $sql = "SELECT br.*, b.title 
                FROM borrow_records br 
                JOIN books b ON br.book_isbn = b.isbn 
                WHERE br.member_id = :member_id 
                ORDER BY br.borrow_date DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':member_id' => $memberId]);
        
        return $stmt->fetchAll();
    }

    public function getCurrentBorrowCount($memberId)
    {
        $sql = "SELECT COUNT(*) as count FROM borrow_records 
                WHERE member_id = :member_id AND return_date IS NULL";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':member_id' => $memberId]);
        
        $data = $stmt->fetch();
        return $data['count'];
    }

    public function updateBorrowHistoryCount($memberId, $newCount)
    {
        $sql = "UPDATE members SET total_borrowed_history = :count WHERE member_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':count' => $newCount, ':id' => $memberId]);
    }
}