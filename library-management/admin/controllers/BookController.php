<?php
require_once "./models/Book.php";
require_once "./models/Borrowed_Books.php";

class BookController{
    private $book;

    public function __construct(){
        $this->book = new Book();
        $this->borrowedBooks = new BorrowedBooks();
    }

    // Handle add book 
    public function handleAddBooks() {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize and trim the input fields
            $title = isset($_POST['title']) ? trim($_POST['title']) : null;
            $author = isset($_POST['author']) ? trim($_POST['author']) : null;
            $isbn = isset($_POST['isbn']) ? trim($_POST['isbn']) : null;
            $publication_year = isset($_POST['publication_year']) ? trim($_POST['publication_year']) : null;
            $status = isset($_POST['status']) ? trim($_POST['status']) : "available";
            $total_copies = isset($_POST['total_copies']) ? $_POST['total_copies'] : 1;
            $available_copies = isset($_POST['total_copies']) ? $_POST['total_copies'] : 1;

            // Check if the required fields are empty
            if (empty($title) || empty($author) || empty($isbn) || empty($publication_year) || empty($total_copies) || empty($available_copies)) {
                echo "All fields are required to add a book.";
                return;
            }

            // Assign values to the book object
            $this->book->title = $title;
            $this->book->author = $author;
            $this->book->isbn = $isbn;
            $this->book->publication_year = $publication_year;
            $this->book->status = $status;
            $this->book->total_copies = $total_copies;
            $this->book->available_copies = $available_copies;

            // Attempt to add the book to the database
            if ($this->book->addBook()) {
                echo "Book added successfully.";
                header("Location: allBooks.php?success=Book added successfully."); // Redirect to the books listing page
                exit;
            } else {
                echo "Book adding process failed.";
                header("Location: allBooks.php?failed=Book added failed."); // Redirect to the books listing page
                exit;
            }
        }
    }

    // Handle fetching total number of books
    public function getTotalBooks() {
        return $this->book->totalBooks();
    }

    // Handle fetching available books
    public function getTotalAvailableBooks() {
        return $this->book->totalAvailableBooks();
    }

    // Handle fetching borrowed books
    public function getTotalBorrowedBooks() {
        return $this->book->totalBorrowedBooks();
    }

    // Handle fetching all books
    public function getAllBooks() {
        return $this->book->getAllBooks();
    }
    
    // Handle fetching a specific book by ID
    public function getBookById($id) {
        return $this->book->getBookById($id);
    }
    
    // Handle editing book details
    public function handleEditBook($id) {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $currentBook = $this->book->getBookById($id);
            if (!$currentBook) {
                echo "Book not found.";
                header("Location: allBooks.php?failed=Book not found.");
                exit;
            }
    
            $currentTotalCopies = $currentBook['total_copies'];
            $currentAvailableCopies = $currentBook['available_copies'];

            $title = isset($_POST['title']) ? trim($_POST['title']) : null;
            $author = isset($_POST['author']) ? trim($_POST['author']) : null;
            $isbn = isset($_POST['isbn']) ? trim($_POST['isbn']) : null;
            $publication_year = isset($_POST['publication_year']) ? trim($_POST['publication_year']) : null;
            $status = isset($_POST['status']) ? trim($_POST['status']) : "available";
            $total_copies = isset($_POST['total_copies']) ? $_POST['total_copies'] : 0;
    
            if (empty($title) || empty($author) || empty($isbn) || empty($publication_year) || empty($total_copies)) {
                echo "All fields are required to edit a book.";
                return;
            }

            // the difference in total copies
            $differenceInCopies = $total_copies - $currentTotalCopies;

            // available copies based on the difference
            $newAvailableCopies = $currentAvailableCopies + $differenceInCopies;
    
            // Assign values to the book object
            $this->book->id = $id;
            $this->book->title = $title;
            $this->book->author = $author;
            $this->book->isbn = $isbn;
            $this->book->publication_year = $publication_year;
            $this->book->status = $status;
            $this->book->total_copies = $total_copies;
            $this->book->available_copies = $newAvailableCopies;
    
            // Attempt to update the book in the database
            if ($this->book->updateBook()) {
                echo "Book updated successfully.";
                header("Location: allBooks.php?success=Book successfully updated."); // Redirect to the books listing page
                exit;
            } else {
                echo "Book update process failed.";
                header("Location: allBooks.php?failed=Book update failed."); // Redirect to the books listing page
                exit;
            }
        }
    }
    
    // Handle deleting a book
    public function handleDeleteBook($id) {

        // Check if the book is borrowed
        if ($this->borrowedBooks->isBookBorrowed($id)) {
            echo "This book is currently borrowed and cannot be deleted.";
            header("Location: allBooks.php?failed=This book is currently borrowed and cannot be deleted.");
            exit;
        }

        $this->book->id = $id;
    
        if ($this->book->deleteBook()) {
            echo "Book deleted successfully.";
            header("Location: allBooks.php?success=Book successfully deleted."); // Redirect to the books listing page
            exit;
        } else {
            echo "Failed to delete the book.";
            header("Location: allBooks.php?failed=Book delete failed."); // Redirect to the books listing page
            exit;
        }
    }

    // Method to handle the search 
    public function handleSearchBooks($keyword = '') {
        if ($keyword) {
            return $this->book->searchBooks($keyword);
        }
        return [];
    }
}

?>