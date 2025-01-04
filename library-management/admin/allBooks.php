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

$bookController->handleSearchBooks();

$allBooks = $bookController->getAllBooks();

if (isset($_GET['keyword'])) {
    $keyword = $_GET['keyword'];

    $books = $bookController->handleSearchBooks($keyword);

    echo json_encode($books);
    exit();
}
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
                    <div class="card" id="datas">
                        <div class="card-header">
                            <h3 class="card-title">List of All Books</h3>
                            <a href="addBooks.php" class="btn btn-primary float-right btn-sm">
                                <i class="fa-solid fa-plus me-2"></i>Add Books
                            </a>
                        </div>


                        <!-- Main content [ Serach Engine ] -->
                            <section class="content">
                                <div class="container-fluid">
                                    <h2 class="text-center display-6">Search Books</h2>
                                    <div class="row">
                                        <div class="col-md-8 offset-md-2">
                                            <form id="search-form">
                                                <div class="input-group">
                                                    <input
                                                        type="text" 
                                                        id="input-box" 
                                                        class="form-control form-control-lg" 
                                                        placeholder="Search by title"
                                                        autocomplete="off"
                                                    >
                                                    <div class="input-group-append">
                                                        <button type="button" class="btn btn-lg btn-default">
                                                            <i class="fa fa-search"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div id="result-box" class="autocomplete-suggestions"></div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </section>


                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Book Title</th>
                                        <th>Author</th>
                                        <th>ISBN</th>
                                        <th>Publication Year</th>
                                        <th>Status</th>
                                        <th>Total Copies</th>
                                        <th>Available Copies</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Loop through all books and display them in the table
                                    foreach ($allBooks as $book) {
                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($book['title']) . "</td>";
                                        echo "<td>" . htmlspecialchars($book['author']) . "</td>";
                                        echo "<td>" . htmlspecialchars($book['isbn']) . "</td>";
                                        echo "<td>" . htmlspecialchars($book['publication_year']) . "</td>";
                                        $status = ($book['status'] == 'available') ? 'Available' : 'Not Available';
                                        echo "<td>" . $status . "</td>";
                                        echo "<td>" . $book['total_copies'] . "</td>";
                                        echo "<td>" . $book['available_copies'] . "</td>";
                                        echo "<td>
                                                <div class='btn-group'>
                                                    <button type='button' class='btn btn-danger'>Action</button>
                                                    <button type='button' class='btn btn-danger dropdown-toggle dropdown-toggle-split' data-bs-toggle='dropdown' aria-expanded='false' aria-label='Toggle Dropdown'>
                                                        <span class='visually-hidden'>Toggle Dropdown</span>
                                                    </button>
                                                    <ul class='dropdown-menu'>
                                                        <li><a class='dropdown-item' href='editBook.php?id=" . $book['id'] . "'>Edit</a></li>
                                                        <li><a class='dropdown-item' href='deleteBook.php?id=" . $book['id'] . "' onclick='return confirm(\"Are you sure you want to delete this book?\");'>Delete</a></li>
                                                    </ul>
                                                </div>
                                              </td>";
                                        echo "</tr>";
                                    }
                                    ?>
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






    // const allBooks = <?php echo json_encode($allBooks); ?>;

    // //console.log(allBooks);

    // let allAvailableBooks = allBooks.map(book => book.title);

    // //console.log("llllllllllllll",allAvailableBooks);

    // const inputBox = document.getElementById("input-box");
    // const resultBox = document.getElementById("result-box");

    // inputBox.onkeyup = function () {
    //     let result = [];
    //     let input = inputBox.value;
    //     if (input.length) {
    //         result = allAvailableBooks.filter((keywords) => {
    //             return keywords.toLowerCase().includes(input.toLowerCase());
    //         });
    //         //console.log(result);
    //     }
    //     display(result);

    //     if (!result.length) {
    //         resultBox.innerHTML = '';
    //     }
    // }

    // function display(result) {
    //     const content = result.map((list) => {
    //         return "<li onClick=selectInput(this)>" + list + "</li>";
    //     });

    //     resultBox.innerHTML = "<ul>" + content.join('') + "</ul>";
    //     resultBox.style.display = "block";
    // }

    // function selectInput(list){
    //     inputBox.value = list.innerHTML;
    //     resultBox.innerHTML = '';
    //     resultBox.style.display = "none";
    // }


    const allBooks = <?php echo json_encode($allBooks); ?>;

    // Input box and result box
    const inputBox = document.getElementById("input-box");
    const resultBox = document.getElementById("result-box");
    const tableBody = document.querySelector("#example2 tbody");

    // Update the table based on search results
    function updateTable(books) {
        tableBody.innerHTML = ""; // Clear existing rows

        if (books.length === 0) {
            tableBody.innerHTML = "<tr><td colspan='8' class='text-center'>No books found</td></tr>";
            return;
        }

        books.forEach(book => {
            const status = book.status === "available" ? "Available" : "Not Available";
            const row = `
                <tr>
                    <td>${book.title}</td>
                    <td>${book.author}</td>
                    <td>${book.isbn}</td>
                    <td>${book.publication_year}</td>
                    <td>${status}</td>
                    <td>${book.total_copies}</td>
                    <td>${book.available_copies}</td>
                    <td>
                        <div class='btn-group'>
                            <button type='button' class='btn btn-danger'>Action</button>
                            <button type='button' class='btn btn-danger dropdown-toggle dropdown-toggle-split' data-bs-toggle='dropdown' aria-expanded='false' aria-label='Toggle Dropdown'>
                                <span class='visually-hidden'>Toggle Dropdown</span>
                            </button>
                            <ul class='dropdown-menu'>
                                <li><a class='dropdown-item' href='editBook.php?id=${book.id}'>Edit</a></li>
                                <li><a class='dropdown-item' href='deleteBook.php?id=${book.id}' onclick='return confirm("Are you sure you want to delete this book?");'>Delete</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
            `;
            tableBody.innerHTML += row;
        });
    }

    // Filter and display suggestions
    inputBox.onkeyup = function () {
        let input = inputBox.value.trim().toLowerCase();

        if (input.length > 0) {
            let filteredBooks = allBooks.filter(book =>
                book.title.toLowerCase().includes(input)
            );

            updateTable(filteredBooks); // Update the table

            // Display autocomplete suggestions
            let result = filteredBooks.map(book => book.title);
            displaySuggestions(result);
        } else {
            // If input is empty, reset the table and hide suggestions
            updateTable(allBooks); // Show all books in the table
            resultBox.innerHTML = '';
            resultBox.style.display = "none";
        }
    };

    function displaySuggestions(result) {
        const content = result.map(list => `<li onClick="selectInput(this)">${list}</li>`);
        resultBox.innerHTML = `<ul>${content.join('')}</ul>`;
        //resultBox.style.display = "block";
        resultBox.style.display = result.length ? "block" : "none";
    }

    function selectInput(list) {
        inputBox.value = list.innerHTML;
        resultBox.innerHTML = '';
        resultBox.style.display = "none";

        // Trigger table update with the selected value
        const filteredBooks = allBooks.filter(book =>
            book.title.toLowerCase().includes(inputBox.value.trim().toLowerCase())
        );
        updateTable(filteredBooks);
    }


    function confirmLogout() {
        if (confirm("Are you sure you want to logout?")) {
            window.location.href = "logout.php";
        }
    }
    
</script>


<?php
include('includes/footer.php');
?>
