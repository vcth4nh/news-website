<?php
session_start();

include 'DB.php';
$news_list = [];

if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $news_list = (new DB())->searchByTitleOrContent($search);
} else {
    $news_list = (new DB())->getAllNews();
}
?>

<?php include 'template/begin.php' ?>
<?php include 'template/header.php' ?>
    <main class="container py-5">
        <h2 class="text-center mb-4">Latest News</h2>
        <ul class="list-unstyled">
            <?php
            foreach ($news_list as $news):?>
                <li class="media m-2">
                    <img src="<?= $news['image'] ?? 'static/image/no-image.jpg' ?>"
                         class="mr-3" alt="Article Image" style="width: 150px; height: 150px;">
                    <div class="media-body">
                        <h3 class="mt-0 mb-1"><?= $news['title'] ?></h3>
                        <p><?= substr($news['content'], 0, 200) ?></p>
                        <a href="#" class="btn btn-primary">Read More</a>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </main>
<?php include 'template/end.php' ?>