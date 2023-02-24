<?php
session_start();

include '../util.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    header("Location: index.php");
}

if (!isset($_POST['email']) and !isset($_POST['password'])) {
    header("Location: index.php");
}

$email = $_POST['email'];
$password = $_POST['password'];

$result = (new DB())->executeQuery("SELECT * FROM author WHERE email = '$email' AND password = '$password'");
if (pg_num_rows($result) > 0) {
    $_SESSION['cred'] = pg_fetch_assoc($result);
    header("Location: /index.php");
} else {
    header("Location: /login.php");
}