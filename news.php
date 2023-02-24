<?php
include 'DB.php';
if (!isset($_GET['id'])) {
    header('Location: /index.php');
    exit();
}
$conn = (new DB());
$news = $conn->getNewsById($_GET['id']);
if (!$news) {
    header('Location: /index.php');
    exit();
}
$news = $news[0];
$keywords = $conn->getKeywordsByNewsId($_GET['id']);
$category = $conn->getCategoryByNewsId($_GET['id'])[0];
$comments = $conn->listCommentsFromPost($_GET['id']);
?>

<?php include 'template/begin.php' ?>
<?php include 'template/header.php' ?>

<div class="container">
    <div class="row">
        <div class="col-md-8">
            <h2>Title: <?= $news['title'] ?></h2>
            <p>Posted on <?= $news['date'] ?></p>
            <?php if ($category): ?>
                <p>Category: <?= $category['name'] ?></p>
            <?php endif; ?>
            <hr>
            <p><?= $news['content'] ?></p>

            <?php if ($keywords): ?>
                <p>Keywords:
                    <?php foreach ($keywords as $keyword): ?>
                        <?= $keyword['keyword'] ?>,
                    <?php endforeach; ?>
                </p>
            <?php endif; ?>
            <hr>

            <div class="comments-section">
                <h3>Comments</h3>
                <hr>
                <?php if ($comments): ?>
                    <?php foreach ($comments as $comment): ?>
                        <div class="comment">
                            <p class="comment-author"><?= $comment['username'] ?></p>
                            <p class="comment-date">Posted on <?= $comment['date'] ?></p>
                            <p class="comment-text"><?= $comment['content'] ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No comments yet.</p>
                <?php endif; ?>
                <!-- Add new comment form -->
                <div class="add-comment">
                    <h4>Add a Comment</h4>
                    <form action="/api/add_comment.php" method="post">
                        <input type="hidden" name="news_id" value="<?= $news['id'] ?>">
                        <div class="form-group">
                            <label for="comment-name">Name:</label>
                            <input type="text" class="form-control" id="comment-name" name="name">
                        </div>
                        <div class="form-group">
                            <label for="comment-text">Comment:</label>
                            <textarea class="form-control" id="comment-text" name="content"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'template/end.php' ?>
