<?php
require_once "./config/conn.php";
require_once 'Book.php';

class BorrowedBooks {
    private $conn;
    private $table_name = "borrowed_books";
    private $book;

    public $id;
    public $user_id;
    public $book_ids = [];  // Array of book IDs to borrow
    public $borrow_date;
    public $return_date;

    // Constructor to initialize database connection
    public function __construct() {
        try {
            $this->conn = getDatabaseConnection();
            $this->book = new Book();
        } catch (Exception $e) {
            die("Error: Unable to establish a database connection. " . $e->getMessage());
        }
    }

    // Add new borrowed book records (for multiple books)
    public function addBorrowedBooks() {
        if (empty($this->book_ids)) {
            return false;
        }

        $query = "INSERT INTO {$this->table_name} (user_id, book_id, borrow_date, return_date) 
                  VALUES (:user_id, :book_id, :borrow_date, :return_date)";
        $stmt = $this->conn->prepare($query);

        // Loop through the array of book IDs and insert each one
        foreach ($this->book_ids as $book_id) {
            $stmt->bindparam(':user_id', $this->user_id);
            $stmt->bindparam(':book_id', $book_id);
            $stmt->bindparam(':borrow_date', $this->borrow_date);
            $stmt->bindparam(':return_date', $this->return_date);

            // Reduce available copies for each book
            if (!$this->book->reduceAvailableCopies($book_id)) {
                return false;
            }

            // Execute the statement
            if (!$stmt->execute()) {
                return false;
            }
        }

        return true;
    }

    // Get all borrowed books
    public function getBorrowedBooks(){
        $query = "SELECT COUNT(*) AS total_borrowed_books FROM {$this->table_name}";
        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total_borrowed_books'];
    }


    // Get detailed borrowed books data
    public function getDetailedBorrowedBooks() {
        $query = "
            SELECT 
                b.title, 
                b.isbn, 
                CONCAT(u.first_name, ' ', u.last_name) AS user_name, 
                u.email, 
                bb.borrow_date, 
                bb.return_date,
                bb.id
            FROM 
                {$this->table_name} bb
            JOIN 
                users u ON bb.user_id = u.id
            JOIN 
                books b ON bb.book_id = b.id
        ";
        $stmt = $this->conn->prepare($query);

        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all rows as an associative array
        }

        return []; // Return an empty array if the query fails
    }

    // Return borrowed books 
    public function returnBooks($id) {
        $query = "SELECT book_id FROM {$this->table_name} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $book_id = $stmt->fetchColumn();

        // Delete the borrowed book record
        $query = "DELETE FROM borrowed_books WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $deleteSuccess = $stmt->execute();

        if ($deleteSuccess) {
            // Increment available copies for the book
            $query = "UPDATE books SET available_copies = available_copies + 1 WHERE id = :book_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':book_id', $book_id);
            $stmt->execute();

            $query_a = "SELECT available_copies, status FROM books WHERE id = :book_id";
            $stmt = $this->conn->prepare($query_a);
            $stmt->bindparam(':book_id', $book_id);
            $stmt->execute();
    
    
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if (!$row) {
                return false; 
            }
    
            $available_copies = $row['available_copies'];
            $status = $row['status'];

            if ($available_copies > 0 ) {
                $status = "available";
            }

            $update_query = "UPDATE books
                            SET status = :status 
                            WHERE id = :id";
            
            $update_stmt = $this->conn->prepare($update_query);
            $update_stmt->bindparam(':status', $status);
            $update_stmt->bindparam(':id', $book_id);

            if ($update_stmt->execute()) {
                return true; 
            }
        }
        return false;
    }

    // Check if a book is borrowed
    public function isBookBorrowed($bookId) {
        $query = "SELECT COUNT(*) FROM {$this->table_name} WHERE book_id = :book_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':book_id', $bookId);
        $stmt->execute();

    // If the count is greater than 0, the book is borrowed
    return $stmt->fetchColumn() > 0;
}
}
?>
