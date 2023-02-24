<?php

class DB
{
    public $conn;

    function __construct()
    {
        $this->conn = $this->connectDatabase();
    }

    function __destruct()
    {
        pg_close($this->conn);
    }

    function connectPgReal(): \PgSql\Connection|bool
    {
        $host = "host = localhost";
        $port = "port = 5432";
        $dbname = "dbname = news";
        $credentials = "user = postgres password=1";

        return pg_connect("$host $port $dbname $credentials");
    }

    function checkDbConnection($conn): bool
    {
        if ($conn) {
            return true;
        } else {
            return false;
        }
    }

    function connectDatabase(): \PgSql\Connection|bool
    {
        $conn = $this->connectPgReal();
        if (!$this->checkDbConnection($conn)) die("Connection failed");
        return $conn;
    }

    function executeQuery($query): \PgSql\Result|bool
    {
        return pg_query($this->conn, $query);
    }

    function select($selectQuery): array|bool
    {
        $result = $this->executeQuery($selectQuery);
        if (!$result) {
            echo "An error occurred.\n";
            exit;
        }
        return pg_fetch_all($result);
    }


    function getAllNews(): bool|array
    {
        return $this->select("SELECT * FROM news ORDER BY date DESC");
    }

    function searchByTitle($title): bool|array
    {
        return $this->select("SELECT * FROM news WHERE title LIKE '%$title%' ORDER BY date DESC");
    }

    function searchByContent($content): bool|array
    {
        return $this->select("SELECT * FROM news WHERE content LIKE '%$content%' ORDER BY date DESC");
    }

    function searchByTitleOrContent($search_words): bool|array
    {
        return $this->select("SELECT * FROM news WHERE title LIKE '%$search_words%' OR content LIKE '%$search_words%' ORDER BY date DESC");
    }

    function searchByDate($dateFrom, $dateTo): bool|array
    {
        return $this->select("SELECT * FROM news WHERE date BETWEEN '$dateFrom' AND '$dateTo' ORDER BY date DESC");
    }

    function searchByKeyword($keyword): bool|array
    {
        return $this->select("SELECT * FROM keyword WHERE keywords ='$keyword' ORDER BY date DESC");
    }

    function listAllStory(): bool|array
    {
        return $this->select("SELECT * FROM story");
    }

    function searchByStory($story): bool|array
    {
        return $this->select("SELECT * FROM story WHERE story = '$story' ORDER BY date DESC");
    }

    function listCommentFromPost($postID): bool|array
    {
        return $this->select("SELECT * FROM comment WHERE post_id = '$postID'");
    }

    function login($email, $password): \PgSql\Result|bool
    {
        return $this->executeQuery("SELECT * FROM author WHERE email = '$email' AND password = '$password'");
    }

    function register($fullname, $email, $password): \PgSql\Result|bool
    {
        return $this->executeQuery("INSERT INTO author (name, email, password) VALUES ('$fullname', '$email', '$password')");
    }


    function getAllPosts($id): bool|array
    {
        return $this->select("SELECT * FROM news WHERE author_id = '$id' ORDER BY date DESC");
    }

    function createNews($title, $content, $cate_id): bool|\PgSql\Result
    {
        $author_id = $_SESSION['cred']['id'];
        $date = date('Y-m-d H:i:s');
        return $this->executeQuery("INSERT INTO news (title, content, category_id, author_id, date) VALUES ('$title', '$content', $cate_id,'$author_id', '$date') RETURNING id");
    }

    function createKeyword($keyword): bool|\PgSql\Result
    {
        return $this->executeQuery("INSERT INTO keyword (keyword) VALUES ('$keyword') RETURNING id");
    }

    function getAllCategories(): bool|array
    {
        return $this->select("SELECT * FROM category");
    }

    function createKeywordMap($kw_id, $news_id): \PgSql\Result|bool
    {
        return $this->executeQuery("INSERT INTO news_keyword (keyword_id, news_id) VALUES ($kw_id, $news_id)");
    }

    function deletePost($id): \PgSql\Result|bool
    {
        return $this->executeQuery("DELETE FROM news WHERE id = $id and author_id = " . $_SESSION['cred']['id']);
    }

    function getNewsById($id): bool|array
    {
        return $this->select("SELECT * FROM news WHERE id = $id");
    }

    function getKeywordsByNewsId($id): bool|array
    {
        return $this->select("SELECT k.keyword FROM keyword k JOIN news_keyword nk ON k.id = nk.keyword_id WHERE nk.news_id = $id");
    }

    function getCategoryByNewsId($id): bool|array
    {
        return $this->select("SELECT c.name FROM category c JOIN news n ON c.id = n.category_id WHERE n.id = $id");
    }

    function listCommentsFromPost($id): bool|array
    {
        return $this->select("SELECT * FROM comment WHERE news_id = $id");
    }

    function addComment(mixed $news_id, mixed $name, mixed $comment): \PgSql\Result|bool
    {
        $date = date('Y-m-d H:i:s');
        return $this->executeQuery("INSERT INTO comment (news_id, username, content, date) VALUES ($news_id, '$name', '$comment', '$date')");
    }
}
