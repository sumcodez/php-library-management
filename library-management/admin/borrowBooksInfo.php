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

include 'controllers/BorrowedBooksController.php';

$borrowController = new BorrowedBookController();
$borrowedBooksInfo = $borrowController->handleGetDetailedBorrowedBooks();
$borrowController->handleReturnBooks();
$currentDate = new DateTime();
ob_flush();
?>

<div class="content-wrapper">


    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show position-fixed bottom-0 end-0 m-3" role="alert" id="success_msg" style="z-index: 999;">
            <?= htmlspecialchars($_GET['success']) ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['failed'])): ?>
        <div class="alert alert-warning alert-dismissible fade show position-fixed bottom-0 end-0 m-3" role="alert" id="failed_msg" style="z-index: 999;">
            <?= htmlspecialchars($_GET['failed']) ?>
        </div>
    <?php endif; ?>



    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Pay Fine</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="returnDate">Previous return date: Not available</p>
                    <p id="fineAmount">Please pay the fine of Rs. 0</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    <form method="POST" action="">
                        <input type="hidden" id="borrowIdInput" name="borrow_id" value="">
                        <button type="submit" class="btn btn-primary btn-sm">Pay</button>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Welcome</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item">
                            <a href="#" onclick="confirmLogout()">Logout</a>
                        </li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">List of All Books</h3>
                            <a href="borrowBooks.php" class="btn btn-primary float-right btn-sm">
                                <i class="fa-solid fa-plus me-2"></i>Lend Books
                            </a>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Book Title</th>
                                        <th>ISBN</th>
                                        <th>User</th>
                                        <th>Email</th>
                                        <th>Borrow Date</th>
                                        <th>Return Date</th>
                                        <th>Return Book</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if (!empty($borrowedBooksInfo)) : ?>
                                    <?php foreach ($borrowedBooksInfo as $book) : 
                                        $returnDate = new DateTime($book['return_date']);
                                        $overdueDays = $returnDate < $currentDate ? $currentDate->diff($returnDate)->days : 0;
                                        $fine = $overdueDays * 10;    
                                    ?>
                                    <tr>
                                        <td><?= htmlspecialchars($book['title']) ?></td>
                                        <td><?= htmlspecialchars($book['isbn']) ?></td>
                                        <td><?= htmlspecialchars($book['user_name']) ?></td>
                                        <td><?= htmlspecialchars($book['email']) ?></td>
                                        <td><?= htmlspecialchars($book['borrow_date']) ?></td>
                                        <td><?= htmlspecialchars($book['return_date']) ?></td>
                                        <td>
                                        <?php if ($overdueDays > 0) : ?>
                                            <button 
                                                type="button" 
                                                class="btn btn-danger btn-sm" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#exampleModal" 
                                                onclick="setModalContent(<?= $fine ?>, '<?= htmlspecialchars($book['return_date']) ?>', <?= htmlspecialchars($book['id']) ?>)">
                                                Pay Fine
                                            </button>
                                        <?php else : ?>
                                            <form method="POST" onsubmit="return confirm('Are you sure you want to return this book?');">
                                                <input type="hidden" name="borrow_id" value="<?= htmlspecialchars($book['id']) ?>">
                                                <button type="submit" class="btn btn-danger btn-sm">Return</button>
                                            </form>
                                        <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                <tr>
                                    <td colspan="6" class="text-center">No borrowed books found.</td>
                                </tr>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
</div>


<script>
    setTimeout(() => {
        const alert = document.getElementById('success_msg');
        const alert_failed = document.getElementById('failed_msg');
        const url = new URL(window.location.href); // Get the current URL

        if (alert) {
            alert.classList.remove('show');
            alert.classList.add('fade');
            setTimeout(() => alert.remove(), 1000);
            url.searchParams.delete('success'); // Remove the 'success' query parameter
            window.history.replaceState({}, document.title, url.toString()); // Update the URL without reloading
        }

        if (alert_failed) {
            alert_failed.classList.remove('show');
            alert_failed.classList.add('fade');
            setTimeout(() => alert_failed.remove(), 1000);
            url.searchParams.delete('failed'); // Remove the 'failed' query parameter
            window.history.replaceState({}, document.title, url.toString()); // Update the URL without reloading
        }

    }, 2000);

    function confirmLogout() {
        if (confirm("Are you sure you want to logout?")) {
            window.location.href = "logout.php";
        }
    }
    
    function setModalContent(fine, returnDate, id) {
        document.getElementById('fineAmount').innerText = `Please pay the fine of Rs. ${fine}`;
        document.getElementById('returnDate').innerText = `Previous return date: ${returnDate}`;
        document.getElementById('borrowIdInput').value = id;
        console.log("This is id !!!!!!!!!!!!!!!!!", id);
    }
</script>


<?php
include('includes/footer.php');
?>
