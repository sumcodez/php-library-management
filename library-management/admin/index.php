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

$first_name = $_SESSION['user_first_name'];
$last_name = $_SESSION['user_last_name'];


include 'controllers/BookController.php';
include 'controllers/BorrowedBooksController.php';

$borrowController = new BorrowedBookController();
$bookController = new BookController();
$totalBooks = $bookController->getTotalBooks();
$totalAvailableBooks = $bookController->getTotalAvailableBooks();
$totalBorrowedBooks = $borrowController->handleGetAllBorrowedBooks();
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


    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0"><?php echo "Welcome, ".$first_name; ?></h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item"><a href="#" onclick="confirmLogout()">Logout</a></li>
              <!-- <li class="breadcrumb-item active">Dashboard v1</li>
              <li class="breadcrumb-item"><a href="alluser.php">All User</a></li>
              <li class="breadcrumb-item"><a href="adduser.php">Add User</a></li> -->
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>



    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3><?php echo $totalBooks ?></h3>

                <p>Total Books</p>
              </div>
              <div class="icon">
                <i class="fa-solid fa-book"></i>
              </div>
              <a href="allBooks.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3><?php echo $totalAvailableBooks ?></h3>

                <p>Available Books</p>
              </div>
              <div class="icon">
                <i class="fa-solid fa-book"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3><?php echo $totalBorrowedBooks ?></h3>

                <p>Borrowed Books</p>
              </div>
              <div class="icon">
                <i class="fa-solid fa-book"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <!-- <div class="col-lg-3 col-6"> -->
            <!-- small box -->
            <!-- <div class="small-box bg-danger">
              <div class="inner">
                <h3>65</h3>

                <p>Unique Visitors</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div> -->
          <!-- ./col -->
        </div>

      </div><!-- /.container-fluid -->
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
</script>


<?php
include('includes/footer.php');
?>