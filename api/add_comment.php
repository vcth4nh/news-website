<?php
include 'util.php';

if (isset($_POST['content']) and isset($_POST['name']) and isset($_POST['news_id'])) {
    $result = (new DB())->addComment($_POST['news_id'], $_POST['name'], $_POST['content']);
    if ($result) {
        header("Location: /news.php?id=" . $_POST['news_id']);
    }
    exit();
}

header("Location: /index.php");

