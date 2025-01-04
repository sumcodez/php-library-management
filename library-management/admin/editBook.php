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

// Get the book ID from the URL
if (isset($_GET['id'])) {
    $bookId = $_GET['id'];
} else {
    echo "No book ID provided.";
    exit;
}

$bookController = new BookController();
$bookController->handleEditBook($bookId);
$bookInformation = $bookController->getBookById($bookId);
ob_end_flush();

?>

<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Book Page</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item active">Edit Book</li>
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
                        <h3 class="card-title">Edit Books</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form method="POST">
                        <div class="card-body" style="padding-bottom: 10px; padding-top: 20px;">
                            <!-- Book Title Input -->
                            <div class="form-group mb-3">
                                <label for="title">Book Title</label>
                                <input name="title" type="text" class="form-control" id="title" placeholder="Enter book title" value="<?= htmlspecialchars($bookInformation['title']) ?>" required>
                            </div>

                            <!-- Author Input -->
                            <div class="form-group mb-3">
                                <label for="author">Author</label>
                                <input name="author" type="text" class="form-control" id="author" placeholder="Enter author's name" value="<?= htmlspecialchars($bookInformation['author']) ?>" required>
                            </div>

                            <!-- ISBN Input -->
                            <div class="form-group mb-3">
                                <label for="isbn">ISBN Number</label>
                                <input name="isbn" type="text" class="form-control" id="isbn" placeholder="Enter ISBN number" pattern="^(97(8|9))?\d{9}(\d|X)$" title="ISBN must be 10 or 13 digits, with or without dashes" value="<?= htmlspecialchars($bookInformation['isbn']) ?>" required>
                            </div>

                            <!-- Publication Year Input -->
                            <div class="form-group mb-4">
                                <label for="publication_year">Publication Year</label>
                                <select name="publication_year" class="form-control" id="publication_year" required>
                                    <option value="" disabled selected>Publication Year</option>
                                </select>
                            </div>

                            <!-- Status Dropdown -->
                            <div class="form-group mb-4">
                                <label for="status">Status</label>
                                <select name="status" class="form-control" id="status" required>
                                    <option value="" disabled>Select status</option>
                                    <option value="available" <?= $bookInformation['status'] == 'available' ? 'selected' : '' ?>>Available</option>
                                    <option value="borrowed" <?= $bookInformation['status'] == 'borrowed' ? 'selected' : '' ?>>Borrowed</option>
                                </select>
                            </div>

                            <!-- Total Copies Input -->
                            <div class="form-group mb-3">
                                <label for="total_copies">Total Copies</label>
                                <input name="total_copies" type="number" class="form-control" id="total_copies" placeholder="Enter total copies" min="<?= $bookInformation['total_copies'] ?>" value="<?= $bookInformation['total_copies'] ?>" required>
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
    // Get the select element
    const yearSelect = document.getElementById('publication_year');

    // Generate the years from 1901 to 2155
    for (let year = 1901; year <= 2155; year++) {
        const option = document.createElement('option');
        option.value = year;
        option.textContent = year;

        // Pre-select the year if it matches the existing value (if editing)
        const selectedYear = <?= json_encode($bookInformation['publication_year'] ?? '') ?>;
        if (year == selectedYear) {
            option.selected = true;
        }

        yearSelect.appendChild(option);
    }
</script>


<?php
include('includes/footer.php');
?>
