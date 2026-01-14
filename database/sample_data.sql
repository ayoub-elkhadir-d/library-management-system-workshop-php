USE library_db;

-- Insert sample categories
INSERT INTO categories (name) VALUES 
('Computer Science'),
('Literature'),
('Science'),
('Mathematics'),
('History');

-- Insert sample authors
INSERT INTO authors (name, nationality, primary_genre) VALUES
('Robert C. Martin', 'American', 'Computer Science'),
('Jane Austen', 'British', 'Literature'),
('Stephen Hawking', 'British', 'Science'),
('Isaac Newton', 'British', 'Science'),
('William Shakespeare', 'British', 'Literature');

-- Insert sample library branches
INSERT INTO library_branches (branch_name, location, phone) VALUES
('Main Campus Library', '123 University Ave', '555-0101'),
('North Campus Library', '456 College St', '555-0102'),
('Science Library', '789 Research Blvd', '555-0103');

-- Insert sample books
INSERT INTO books (isbn, title, publication_year, category_id, status) VALUES
('9780132350884', 'Clean Code', 2008, 1, 'Available'),
('9780141439518', 'Pride and Prejudice', 1813, 2, 'Available'),
('9780553380163', 'A Brief History of Time', 1988, 3, 'Available'),
('9780140434561', 'Hamlet', 1603, 2, 'Available');

-- Link books to authors
INSERT INTO book_authors (book_id, author_id) VALUES
(1, 1), -- Clean Code by Robert C. Martin
(2, 2), -- Pride and Prejudice by Jane Austen
(3, 3), -- A Brief History of Time by Stephen Hawking
(4, 5); -- Hamlet by William Shakespeare

-- Add book copies to branches
INSERT INTO book_copies (book_id, branch_id, available_copies, total_copies) VALUES
(1, 1, 5, 5), -- 5 copies at Main Library
(1, 2, 3, 3), -- 3 copies at North Library
(2, 1, 2, 2),
(3, 3, 4, 4),
(4, 1, 1, 1);

-- Insert sample members
INSERT INTO members (member_number, full_name, email, phone, membership_type, membership_start_date, membership_end_date) VALUES
('S2024001', 'John Smith', 'john.smith@university.edu', '555-1001', 'Student', '2024-09-01', '2025-08-31'),
('F2024001', 'Dr. Sarah Johnson', 's.johnson@university.edu', '555-2001', 'Faculty', '2024-01-01', '2027-12-31'),
('S2024002', 'Emily Chen', 'emily.chen@university.edu', '555-1002', 'Student', '2024-09-01', '2025-08-31');