<?php
require_once "./config/conn.php";

class Book{
    private $conn;
    private $table_name = "books";

    public $id;
    public $title;
    public $author;
    public $isbn;
    public $publication_year;
    public $status;
    public $total_copies;
    public $available_copies;
    public $created_at;
    public $updated_at;

    // Constructor to initialize database connection
    public function __construct() {
        try {
            $this->conn = getDatabaseConnection(); // Use the provided database connection function
        } catch (Exception $e) {
            die("Error: Unable to establish a database connection. " . $e->getMessage());
        }
    }

    // add new book
    public function addBook() {
        $query = "INSERT INTO {$this->table_name} (title, author, isbn, publication_year, status, total_copies, available_copies)
                  VALUES (:title, :author, :isbn, :publication_year, :status, :total_copies, :available_copies)";
        $stmt = $this->conn->prepare($query);

        // Bind the parameters
        $stmt->bindparam(':title', $this->title);
        $stmt->bindparam(':author', $this->author);
        $stmt->bindparam(':isbn', $this->isbn);
        $stmt->bindparam(':publication_year', $this->publication_year);
        $stmt->bindparam(':status', $this->status); // Bind the status parameter
        $stmt->bindparam(':total_copies', $this->total_copies);
        $stmt->bindparam(':available_copies', $this->available_copies);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // get total number of books
    public function totalBooks(){
        $query = "SELECT COUNT(*) AS total_books FROM {$this->table_name}";
        $stmt = $this->conn->prepare($query);

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total_books'];
    }

    // Get total available books
    public function totalAvailableBooks() {
        $query = "SELECT COUNT(*) AS total_available FROM {$this->table_name} WHERE status = 'available'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total_available'];
    }


    // Get all books
    public function getAllBooks() {
        $query = "SELECT * FROM {$this->table_name}";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        // Fetch all books
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get a specific book by ID
    public function getBookById($id) {
        $query = "SELECT * FROM {$this->table_name} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindparam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Edit book details
    public function updateBook() {
        $query = "UPDATE {$this->table_name}
                  SET title = :title, 
                      author = :author, 
                      isbn = :isbn, 
                      publication_year = :publication_year, 
                      status = :status, 
                      total_copies = :total_copies,
                      available_copies = :available_copies
                  WHERE id = :id";
    
        $stmt = $this->conn->prepare($query);
    
        // Bind the parameters
        $stmt->bindparam(':title', $this->title);
        $stmt->bindparam(':author', $this->author);
        $stmt->bindparam(':isbn', $this->isbn);
        $stmt->bindparam(':publication_year', $this->publication_year);
        $stmt->bindparam(':status', $this->status);
        $stmt->bindparam(':total_copies', $this->total_copies);
        $stmt->bindparam(':available_copies', $this->available_copies);
        $stmt->bindparam(':id', $this->id);
    
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    

    // Delete book
    public function deleteBook() {
        $query = "DELETE FROM {$this->table_name} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindparam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }


    // Reduce available_copies by id and update status if available_copies is 0
    public function reduceAvailableCopies($id) {
    
        $query = "SELECT available_copies, status FROM {$this->table_name} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindparam(':id', $id);
        $stmt->execute();


        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return false; 
        }

        $available_copies = $row['available_copies'];
        $status = $row['status'];

        // Check if there are available copies
        if ($available_copies > 0) {
            // Reduce available copies by 1
            $new_available_copies = $available_copies - 1;

            // If available copies reach 0, update status to 'not_available'
            if ($new_available_copies == 0 && $status != 'not_available') {
                $status = 'not_available';
            }

            // Update available_copies and status in the database
            $update_query = "UPDATE {$this->table_name} 
                             SET available_copies = :available_copies, status = :status 
                             WHERE id = :id";
            $update_stmt = $this->conn->prepare($update_query);
            $update_stmt->bindparam(':available_copies', $new_available_copies);
            $update_stmt->bindparam(':status', $status);
            $update_stmt->bindparam(':id', $id);

            if ($update_stmt->execute()) {
                return true; 
            }
        }
        return false; 
    }

    public function searchBooks($keyword) {
        $query = "SELECT * FROM books WHERE title LIKE :keyword OR author LIKE :keyword";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':keyword', '%' . $keyword . '%');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>