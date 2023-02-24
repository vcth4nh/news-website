<?php
session_start();
$news_list = [];

if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $news_list = (new DB())->searchByTitleOrContent($search);
} else {
    //$news_list = (new DB())->executeQuery("SELECT * FROM news ORDER BY date DESC");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>News Website - List View</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<header class="bg-dark text-white">
    <div class="container">
        <div class="row">
            <form action="/" method="GET" id="search"></form>
            <div class="col-md-4">
                <div class="input-group">
                    <input type="text" name="q" class="form-control" placeholder="Search...">
                    <div class="input-group-append">
                        <button class="btn btn-light" type="submit" form="search"><i class="fa fa-search"></i></button>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <nav class="navbar navbar-expand-sm navbar-dark">
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" href="/login.php">Author login</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Categories</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Contact</a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</header>
<main class="container py-5">
    <h2 class="text-center mb-4">Latest News</h2>
    <ul class="list-unstyled">
        <?php
        foreach ($news_list as $news):?>
            <li class="media">
                <img src="<?= $news['image'] ?? 'https://commons.wikimedia.org/wiki/File:No-Image-Placeholder.svg' ?>"
                     class="mr-3" alt="Article Image">
                <div class="media-body">
                    <h3 class="mt-0 mb-1"><?= $news['title'] ?></h3>
                    <p><?= $news['brief'] ?></p>
                    <a href="#" class="btn btn-primary">Read More</a>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</main>
<footer class="bg-light py-3">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <p class="text-center mb-0">Copyright &copy; 2023 VCT mai dink</p>
            </div>
        </div>
    </div>
</footer>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!--<script>-->
<!--    const searchInput = document.querySelector('#search-input');-->
<!--    const searchForm = document.querySelector('#search-form');-->
<!---->
<!--    let timerId;-->
<!--    const delay = 300;-->
<!---->
<!--    searchInput.addEventListener('input', () => {-->
<!--        clearTimeout(timerId);-->
<!--        timerId = setTimeout(() => {-->
<!--            if (searchInput.value.trim() !== '') {-->
<!--                searchForm.submit();-->
<!--            }-->
<!--        }, delay);-->
<!--    });-->
<!--</script>-->
</body>
</html>

