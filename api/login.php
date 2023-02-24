<?php
session_start();

include '../DB.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    header("Location: index.php");
}

if (!isset($_POST['email']) and !isset($_POST['password'])) {
    header("Location: index.php");
}

$result = (new DB())->login($_POST['email'], $_POST['password']);
if (pg_num_rows($result) > 0) {
    $_SESSION['cred'] = pg_fetch_assoc($result);
    header("Location: /index.php");
} else {
    header("Location: /login.php");
}