<?php
include('controllers/BookController.php');

// Get the book ID from the URL
if (isset($_GET['id'])) {
    $bookId = $_GET['id'];
} else {
    echo "No book ID provided.";
    exit;
}

$bookController = new BookController();
$bookController->handleDeleteBook($bookId);
ob_end_flush();

?>