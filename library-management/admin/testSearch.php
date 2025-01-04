<?php
include('includes/header.php');
include('includes/top-nav.php');
include('includes/side-nav.php');
include('controllers/BookController.php');

$bookController = new BookController();

$allBooks = $bookController->getAllBooks();
?>




<div class="content-wrapper">

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

</div>



<script>
    const allBooks = <?php echo json_encode($allBooks); ?>;

    //console.log(allBooks);

    let allAvailableBooks = allBooks.map(book => book.title);

    let allAuthors = allBooks.map(book => book.title);

    let allIsbn = allBooks.map(book => book.isbn);

    //console.log("llllllllllllll",allAvailableBooks);

    const inputBox = document.getElementById("input-box");
    const resultBox = document.getElementById("result-box");

    inputBox.onkeyup = function () {
        let result = [];
        let input = inputBox.value;
        if (input.length) {
            result = allAvailableBooks.filter((keywords) => {
                return keywords.toLowerCase().includes(input.toLowerCase());
            });
            //console.log(result);
        }
        display(result);

        if (!result.length) {
            resultBox.innerHTML = '';
        }
    }

    function display(result) {
        const content = result.map((list) => {
            return "<li onClick=selectInput(this)>" + list + "</li>";
        });

        resultBox.innerHTML = "<ul>" + content.join('') + "</ul>";
        resultBox.style.display = "block";
    }

    function selectInput(list) {
        inputBox.value = list.innerHTML;
        resultBox.innerHTML = '';
        resultBox.style.display = "none";
    }
</script>

<?php
include('includes/footer.php');
?>