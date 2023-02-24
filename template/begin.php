<?php
$title = "News Website";
$path = $_SERVER['REQUEST_URI'];
if ($path !== '/') {
    $title .= ' - ' . ucfirst(substr($path, 1, -4));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>