<?php
session_start();
include 'DB.php';
$posted = (new DB())->getAllPosts($_SESSION['cred']['id']);
$categories = (new DB())->getAllCategories();
?>

<?php include 'template/begin.php' ?>
<?php include 'template/header.php' ?>

<?php include 'template/end.php' ?>

<div class="container">
    <h1>Welcome, <?php echo $_SESSION['cred']['name'] ?></h1>

    <h2 class="mt-4">Create a New Post</h2>
    <form action="api/create_post.php" method="POST">
        <div class="form-group">
            <label for="post_title">Title:</label>
            <input type="text" class="form-control" id="title" name="title">
        </div>

        <div class="form-group">
            <label for="post_content">Content:</label>
            <textarea class="form-control" id="content" name="content"></textarea>
            <small class="form-text text-muted">Minimum 10 words</small>
        </div>
        <div class="form-group">
            <label for="tags">Tags:</label>
            <input type="text" class="form-control" id="tags" name="keyword">
            <small class="form-text text-muted">Enter comma-separated, no space tags for your post</small>
        </div>
        <h3>Category</h3>
        <?php foreach ($categories as $category): ?>
            <input type="radio" name="category"
                   value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?>
        <?php endforeach; ?>
        <br>
        <button type="submit" class="btn btn-primary">Create Post</button>
    </form>

    <h2 class="mt-4">Manage Existing Posts</h2>
    <table class="table">
        <thead>
        <tr>
            <th>Title</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($posted as $post): ?>
            <tr>
                <td><?php echo $post['title']; ?></td>
                <td>
                    <a href="api/edit_post.php?id=<?php echo $post['id']; ?>" class="btn btn-secondary">Edit</a>
                    <a href="api/delete_post.php?id=<?php echo $post['id']; ?>" class="btn btn-danger">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</table>