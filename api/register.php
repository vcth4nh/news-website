<?php
session_start();

include '../DB.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    header("Location: index.php");
}

if (!isset($_POST['email']) and !isset($_POST['password'])) {
    header("Location: index.php");
}


$result = (new DB())->register($_POST['fullname'], $_POST['email'], $_POST['password']);

if ($result) {
    header("Location: /login.php");
} else {
    header("Location: /register.php");
}