<?php
session_start();
include 'util.php';
?>

<?php include 'template/begin.php' ?>
<?php include 'template/header.php' ?>
    <div class="container mt-5">
        <h2>Login</h2>
        <form action="/api/register.php" method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" id="username" name="email" placeholder="Enter username">
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter password">
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>
<?php include 'template/end.php' ?>