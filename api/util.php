<?php
include '../DB.php';

function is_logged_in()
{
    return isset($_SESSION['cred']);
}

function assert_logged_in()
{
    if (!is_logged_in()) {
        header('Location: /login.php');
        exit();
    }
}