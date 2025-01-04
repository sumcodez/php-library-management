<?php
ob_start();
session_start();
include('includes/header.php');
include('includes/top-nav.php');
include('includes/side-nav.php');
if (!isset($_SESSION['user_id'])) {
    header("Location: userLogin.php?failed=Please login to access the dashboard");
    exit();
}
include('controllers/BookController.php');

$bookController = new BookController();
$bookController->handleAddBooks();

$allBooks = $bookController->getAllBooks();

ob_end_flush();

?>

<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Add a New Book</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item active">Add Book</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="content d-flex justify-content-center" style="height: 100vh; width: auto;">
        <div class="container-fluid" style="margin-bottom: 50px; margin-left: 10rem;">
            <div class="col-md-10">
                <!-- general form elements -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Add Books</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form method="POST">
                        <div class="card-body" style="padding-bottom: 10px; padding-top: 20px;">
                            <!-- Book Title Input -->
                            <div class="form-group mb-3">
                                <label for="title">Book Title</label>
                                <input name="title" type="text" class="form-control" id="title" placeholder="Enter book title" required>
                            </div>

                            <!-- Author Input -->
                            <div class="form-group mb-3">
                                <label for="author">Author</label>
                                <input name="author" type="text" class="form-control" id="author" placeholder="Enter author's name" required>
                            </div>

                            <!-- ISBN Input -->
                            <div class="form-group mb-3">
                                <label for="isbn">ISBN Number</label>
                                <input name="isbn" type="text" class="form-control" id="isbn" placeholder="Enter ISBN number" pattern="^(97(8|9))?\d{9}(\d|X)$" title="ISBN must be 13 digits" maxlength="13" required>
                                <small id="isbnFeedback" class="form-text text-danger" style="display: none;">
                                    This ISBN number is already used.
                                </small>
                            </div>

                            <!-- Publication Year Input -->
                            <div class="form-group mb-4">
                                <label for="publication_year">Publication Year</label>
                                <select name="publication_year" class="form-control" id="publication_year" required>
                                    <option value="" disabled selected>Publication Year</option>
                                </select>
                            </div>

                            <!-- Status Dropdown -->
                            <!-- <div class="form-group mb-4">
                                <label for="status">Status</label>
                                <select name="status" class="form-control" id="status" required>
                                    <option value="available" selected>Available</option>
                                </select>
                            </div> -->
                            
                            <!-- Total Copies Input -->
                            <div class="form-group mb-3">
                                <label for="total_copies">Total Copies</label>
                                <input name="total_copies" type="number" class="form-control" id="total_copies" placeholder="Enter total copies" min="1" required>
                            </div>
                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer d-flex justify-content-center" style="padding-top: 10px; padding-bottom: 10px;">
                            <button type="submit" class="btn btn-primary ">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- /Main Content -->

</div>


<script>
    // All isbns numbers that are already present in the database
    const allISBN = <?php echo json_encode(array_column($allBooks, 'isbn')); ?>; 

    // Get the select element
    const yearSelect = document.getElementById('publication_year');

    // years from 1901 to 2155
    for (let year = 1901; year <= 2155; year++) {
        const option = document.createElement('option');
        option.value = year;
        option.textContent = year;
        yearSelect.appendChild(option);
    }

    document.getElementById('isbn').addEventListener('input', function () {
        const isbn = this.value; // Get the current value of the input
        const feedback = document.getElementById('isbnFeedback'); // Reference the feedback element

        if (isbn.length === 13) { // Validate only if the ISBN length is 13
            if (allISBN.includes(isbn)) { // Check if the ISBN exists in the array
                feedback.style.display = 'block'; // Show feedback if ISBN is already used
            } else {
                feedback.style.display = 'none'; // Hide feedback if ISBN is not used
            }
        } else {
            feedback.style.display = 'none'; // Hide feedback for invalid length
        }
    });
</script>


<?php
include('includes/footer.php');
?>
