<?php
session_start();

include 'util.php';
assert_logged_in();

if ($_GET['id']) {
    $result = (new DB())->deletePost($_GET['id']);
    if ($result) {
        header('Location: /author.php');
    } else {
        echo "An error occurred.\n";
        exit;
    }
}
