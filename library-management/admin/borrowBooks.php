<?php
ob_start();
session_start();
include('includes/header.php');
include('includes/top-nav.php');
include('includes/side-nav.php');

// Redirect if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: userLogin.php?failed=Please login to access the dashboard");
    exit();
}

// User session data
$first_name = $_SESSION['user_first_name'];
$last_name = $_SESSION['user_last_name'];
$curr_userId = $_SESSION['user_id'];

// Include controllers
include 'controllers/AuthController.php';
include 'controllers/BookController.php';
include 'controllers/BorrowedBooksController.php';

$borrowController = new BorrowedBookController();
$bookController = new BookController();
$authenticator = new Authenticator();
$allUsers = $authenticator->handleGetUsersWithoutCurrentUser($curr_userId);
$allBooks = $bookController->getAllBooks();
$borrowController->handleAddBorrowedBooks();
?>

<!-- Content Wrapper -->
<div class="content-wrapper">


    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Borrow Books Management</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">




            <form method='POST'>
                <!-- Borrow Books Form -->
                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title">Borrow Books Form</h3>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-md-6">
                                <!-- User Selection -->
                                <div class="form-group">
                                    <label>Select User</label>
                                    <select class="form-control select2" style="width: 100%;" name="user_id">
                                        <option selected="selected" disabled>Select a User</option>
                                        <?php foreach ($allUsers as $user) { ?>
                                            <option value="<?php echo htmlspecialchars($user['id']); ?>">
                                                <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <!-- Borrow Date -->
                                <div class="form-group">
                                    <label>Borrow Date:</label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        value="<?php echo date('Y-m-d'); ?>"
                                        readonly
                                    />
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="col-md-6">
                                <!-- Books Selection -->
                                <div class="form-group">
                                    <label>Select Books</label>
                                    <select class="select2" multiple="multiple" data-placeholder="Select a Book" style="width: 100%;" name="book_ids[]">
                                        <?php foreach ($allBooks as $books) { ?>
                                            <option value="<?php echo htmlspecialchars($books['id']); ?>">
                                                <?php echo htmlspecialchars($books['title']); ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <!-- Return Date -->
                                <!-- <div class="form-group">
                                    <label>Return Date:</label>
                                    <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                        <input 
                                            type="text" 
                                            class="form-control datetimepicker-input" 
                                            data-target="#reservationdate"
                                            name="return_date"
                                            required
                                        />
                                        <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div> -->

                                <div class="form-group">
                                    <label>Return Date:</label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        value="<?php echo date('Y-m-d', strtotime('+7 days')); ?>"
                                        name="return_date"
                                        readonly
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card Footer -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <a href="borrowBooksInfo.php"><button type="button" class="btn btn-secondary float-right">Cancel</button></a>
                    </div>
                </div>
            </form>



        </div>
    </section>
</div>

<?php
include('includes/footer.php');
?>
