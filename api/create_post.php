<?php
session_start();

include 'util.php';
assert_logged_in();


if (isset($_POST['title']) && isset($_POST['content'])) {
    $conn = new DB();
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category = $_POST['category'];

    $result = $conn->createNews($title, $content, $category);
    print_r(pg_fetch_all($result));

    $post_id = pg_fetch_all($result)[0]['id'];
    if (isset($_POST['keyword'])) {
        $keyword = explode(',', $_POST['keyword']);
        foreach ($keyword as $item) {
            $tmp = pg_fetch_all($conn->createKeyword($item));
            print_r($tmp);
            $kw_id = $tmp[0]['id'];
            $conn->createKeywordMap($kw_id, $post_id);
        }
    }

    if (isset($_POST['story_id'])) {
        $story_id = $_POST['story_id'];
        foreach ($story_id as $id) {
            $conn->createStoryMap($id, $post_id);
        }
    }

    if ($result) {
//        header('Location: /author.php');
        exit();
    }
}

header('Location: /');
exit();

