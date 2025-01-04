<?php
include('controllers/BookController.php');

if (isset($_GET['keyword'])) {
    $keyword = $_GET['keyword'];

    $bookController = new BookController();
    $books = $bookController->handleSearchBooks($keyword);

    echo json_encode($books);
    exit();
}
?>
