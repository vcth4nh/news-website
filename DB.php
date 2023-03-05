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
        return $this->select("SELECT * FROM list_all_news()");
    }

    function searchByTitleOrContent($search_words): bool|array
    {
//        TODO: thiếu function search_by_title_or_content
        return $this->select("SELECT * FROM news WHERE title LIKE '%$search_words%' OR content LIKE '%$search_words%' ORDER BY date DESC");
    }

    function login($email, $password): \PgSql\Result|bool
    {
        return $this->executeQuery("SELECT * FROM search_author_by_email_password('$email', '$password')");
//        return $this->executeQuery("SELECT search_author_by_email_password('$email', '$password')");
    }

    function register($fullname, $email, $password): \PgSql\Result|bool
    {
//        return $this->executeQuery("INSERT INTO author (name, email, password) VALUES ('$fullname', '$email', '$password')");
        return $this->executeQuery("SELECT create_author('$fullname', '$email', '$password')");

    }

    function getAllPostsByAuthor($id): bool|array
    {
//        return $this->select("SELECT * FROM news WHERE author_id = '$id' ORDER BY date DESC");
        return $this->select("SELECT * FROM search_news_by_author_id($id)");
    }

    function createNews($title, $content, $cate_id): bool|\PgSql\Result
    {
        $author_id = $_SESSION['cred']['id'];
        $date = date('Y-m-d H:i:s');
//        TODO:  hàm create_news chưa có date
        return $this->executeQuery("INSERT INTO news (title, content, category_id, author_id, date) VALUES ('$title', '$content', $cate_id,'$author_id', '$date') RETURNING id");
    }

    function createKeyword($keyword): bool|\PgSql\Result
    {
//        TODO: hàm cần return id của keyword vừa tạo
        return $this->executeQuery("INSERT INTO keyword (keyword) VALUES ('$keyword') RETURNING id");
    }

    function deletePost($id): \PgSql\Result|bool
    {
//        return $this->executeQuery("DELETE FROM news WHERE id = $id and author_id = " . $_SESSION['cred']['id']);
        return $this->executeQuery("SELECT delete_news_by_author_id($id, " . $_SESSION['cred']['id'] . ")");
    }

    function getNewsById($id): bool|array
    {
//        return $this->select("SELECT * FROM news WHERE id = $id");
        return $this->select("SELECT * FROM search_news_by_id($id)");
    }

    function createKeywordMap($kw_id, $news_id): \PgSql\Result|bool
    {
        return $this->executeQuery("INSERT INTO news_keyword (keyword_id, news_id) VALUES ($kw_id, $news_id)");
//        return $this->executeQuery("SELECT create_news_keyword($kw_id, $news_id)");
    }

    function getKeywordsByNewsId($id): bool|array
    {
//        return $this->select("SELECT k.keyword FROM keyword k JOIN news_keyword nk ON k.id = nk.keyword_id WHERE nk.news_id = $id");
        return $this->select("SELECT * FROM search_keywords_by_news_id($id)");
    }

    function getCategoryByNewsId($id): bool|array
    {
//        return $this->select("SELECT c.name FROM category c JOIN news n ON c.id = n.category_id WHERE n.id = $id");
        return $this->select("SELECT * FROM search_category_by_news_id($id)");
    }

    function getAllCategories(): bool|array
    {
//        return $this->select("SELECT * FROM category");
        return $this->select("SELECT * FROM list_all_categories()");
    }

    function listCommentsFromPost($id): bool|array
    {
//        return $this->select("SELECT * FROM comment WHERE news_id = $id");
        return $this->select("SELECT * FROM search_comment_by_news_id($id)");
    }

    function addComment(mixed $news_id, mixed $name, mixed $comment): \PgSql\Result|bool
    {
        $date = date('Y-m-d H:i:s');
//        TODO: thiếu date
        return $this->executeQuery("INSERT INTO comment (news_id, username, content, date) VALUES ($news_id, '$name', '$comment', '$date')");
//        return $this->executeQuery("SELECT create_comment('$name', '$comment', '$news_id', '$date')");
    }

    function getAllStories(): bool|array
    {
//        TODO lỗi ERROR: structure of query does not match function result type. Detail: Returned type character varying(255) does not match expected type text in column 2.
        return $this->select("SELECT * FROM story");
    }

    public function createStoryMap(mixed $story_id, mixed $post_id): \PgSql\Result|bool
    {
//        return $this->executeQuery("INSERT INTO news_story (story_id, news_id) VALUES ($story_id, $post_id)");
        return $this->executeQuery("SELECT create_news_story($story_id, $post_id)");
    }
}
