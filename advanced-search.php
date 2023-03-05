<?php
session_start();
include "DB.php";
$news_list = [];
$conn = new DB();
$categories = $conn->getAllCategories();
$stories = $conn->getAllStories();

$title = $_GET['title'] ?? '';
$content = $_GET['content'] ?? '';
$dateFrom = $_GET['dateFrom'] ?? '';
$dateTo = $_GET['dateTo'] ?? '';
$story = $_GET['story'] ?? '';
$category = $_GET['category'] ?? '';

$first = true;
$sql = "SELECT * FROM news WHERE ";
if ($title != '') {
    $sql .= "title LIKE '%$title%'";
    $first = false;
}
if ($content != '') {
    if (!$first) $sql .= " AND ";
    $sql .= "content LIKE '%$content%'";
    $first = false;
}
if ($dateFrom != '' && $dateTo != '') {
    if (!$first) $sql .= " AND ";
    $sql .= "date BETWEEN '$dateFrom' AND '$dateTo'";
    $first = false;
}
if ($category != '') {
    if (!$first) $sql .= " AND ";
    $sql .= "category_id = $category";
    $first = false;
}
if ($story != '') {
    if (!$first) $sql .= " AND ";
    $sql .= "id in (SELECT news_id FROM news_story WHERE story_id = $story)";
    $first = false;
}

if (!$first)
    $news_list = $conn->select($sql);

$uri = $_SERVER['REQUEST_URI'];
if (!str_contains($uri, '?')) {
    $uri .= '?';
}

function unsetParam(array $param): array
{
    $tmp = $_GET;
    foreach ($param as $item) {
        unset($tmp[$item]);
    }
    return $tmp;
}

function excludeParam($param): string
{
    $uri = $_SERVER['REQUEST_URI'];
    $uri = explode('?', $uri)[0];
    $uri .= '?';
    $params = $_GET;
    unset($params[$param]);
    foreach ($params as $key => $value) {
        $uri .= "$key=$value&";
    }
    return $uri;
}

?>

<?php include_once 'template/begin.php' ?>
<?php include_once 'template/header.php' ?>
    <main class="container py-5">
        <form action="<?= $uri ?>" method="GET">
            <div class="input-group">
                <input type="text" name="title" class="form-control" placeholder="Search by title">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">Search</button>
                </div>
                <?php foreach (unsetParam(['title']) as $key => $value): ?>
                    <input type="hidden" name="<?= $key ?>" value="<?= $value ?>">
                <?php endforeach; ?>
            </div>
        </form>
        <br>
        <form action="<?= $uri ?>" method="GET">
            <div class="input-group">
                <input type="text" name="content" class="form-control" placeholder="Search by content">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">Search</button>
                </div>
                <?php foreach (unsetParam(['content']) as $key => $value): ?>
                    <input type="hidden" name="<?= $key ?>" value="<?= $value ?>">
                <?php endforeach; ?>
            </div>
        </form>
        <br>
        <form action="<?= $uri ?>" method="GET">
            <div class="input-group">
                <input type="date" name="dateFrom" class="form-control" placeholder="Search by date">
                <input type="date" name="dateTo" class="form-control" placeholder="Search by date">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">Search</button>
                </div>
                <?php foreach (unsetParam(['dateFrom', 'dateTo']) as $key => $value): ?>
                    <input type="hidden" name="<?= $key ?>" value="<?= $value ?>">
                <?php endforeach; ?>
            </div>
        </form>
        <br>
        <div class="container">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6 dropdown">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
                            Categories
                            <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <?php foreach ($categories as $category): ?>
                                <li>
                                    <a href="<?= excludeParam('category') ?>category=<?= $category['id']; ?>"><?= $category['name']; ?></a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="col-sm-6 dropdown">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Stories
                            <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <?php foreach ($stories as $story): ?>
                                <li>
                                    <a href=<?= excludeParam('story') ?>story=<?= $story['id']; ?>><?= $story['title']; ?></a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <h3><?= !$first ? "$sql<br>" : ""; ?></h3>
        <h1>News:</h1>
        <ul class="list-unstyled">
            <?php foreach ($news_list as $news): ?>
                <li class="media m-2">
                    <img src="<?= $news['image'] ?? 'static/image/no-image.jpg' ?>"
                         class="mr-3" alt="Article Image" style="width: 150px; height: 150px;">
                    <div class="media-body">
                        <h3 class="mt-0 mb-1"><?= $news['title'] ?></h3>
                        <p><?= substr($news['content'], 0, 200) ?></p>
                        <a href="/news.php?id=<?= $news['id'] ?>" class="btn btn-primary">Read More</a>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </main>
<?php include_once 'template/end.php' ?>