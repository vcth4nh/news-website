<?php
session_start();

include 'util.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    header("Location: index.php");
}

if (!isset($_POST['username']) and !isset($_POST['password'])) {
    header("Location: index.php");
}

$username = $_POST['username'];
$password = $_POST['password'];

$result = (new DB())->executeQuery("SELECT * FROM users WHERE username = '$username' AND password = '$password'");
if (pg_num_rows($result) > 0) {
    $_SESSION['cred'] = pg_fetch_assoc($result);
    header("Location: index.php");
} else {
    header("Location: login.php");
}