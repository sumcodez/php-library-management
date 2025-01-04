<?php
require_once "./models/Borrowed_Books.php";

class BorrowedBookController {
    private $borrowedBooks;

    public function __construct() {
        $this->borrowedBooks = new BorrowedBooks();
    }

    public function handleAddBorrowedBooks() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Retrieve form data
            $user_id = $_POST['user_id'] ?? null;
            $book_ids = $_POST['book_ids'] ?? []; 
            $borrow_date = date('Y-m-d');
            $return_date = $_POST['return_date'] ?? null;
    
            // Validate form data
            if (!$user_id) {
                echo "User selection is required.";
                return;
            }
    
            if (empty($book_ids)) {
                echo "At least one book must be selected.";
                return;
            }
    
            if (!$return_date) {
                echo "Return date is required.";
                return;
            }
    
            // Check if return_date is already in 'Y-m-d' format
            $format_date = DateTime::createFromFormat('Y-m-d', $return_date);
            if ($format_date && $format_date->format('Y-m-d') === $return_date) {
                // Return date is already in 'Y-m-d' format, no need to format
                $formated_return_date = $return_date;
            } else {
                // Format return_date to 'Y-m-d' format
                $format_date = DateTime::createFromFormat('m/d/Y', $return_date);
                if ($format_date) {
                    $formated_return_date = $format_date->format('Y-m-d');
                } else {
                    echo "Invalid return date format. Please use 'MM/DD/YYYY'.";
                    return;
                }
            }
    
            $this->borrowedBooks->user_id = $user_id;
            $this->borrowedBooks->book_ids = $book_ids;
            $this->borrowedBooks->borrow_date = $borrow_date;
            $this->borrowedBooks->return_date = $formated_return_date;

            if ($this->borrowedBooks->addBorrowedBooks()) {
                echo "Books borrowed successfully!";
                header("Location: borrowBooksInfo.php?success=Books borrowed successfully!");
                exit;
            } else {
                echo "Failed to borrow books. Please try again.";
            }
        }
    }

    // Handle to get all borrowed books
    public function handleGetAllBorrowedBooks(){
        return $this->borrowedBooks->getBorrowedBooks();
    }

    // Handle fetching all borrowed books
    public function handleGetDetailedBorrowedBooks(){
        return $this->borrowedBooks->getDetailedBorrowedBooks();
    }

    // Handle return books
    public function handleReturnBooks(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $borrow_id = $_POST['borrow_id'];

            $result = $this->borrowedBooks->returnBooks($borrow_id);

            if ($result) {
                echo "Book returned successfully";
                header("Location: borrowBooksInfo.php?success=Book returned successfully");
                exit;
            } else {
                echo "Error during return book";
            }
        }
    }
    
}
?>
