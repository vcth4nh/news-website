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
        return $this->select("SELECT * FROM search_news('$search_words')");
    }

    function login($email, $password): \PgSql\Result|bool
    {
        return $this->executeQuery("SELECT * FROM search_author_by_email_password('$email', '$password')");
    }

    function register($fullname, $email, $password): \PgSql\Result|bool
    {
        return $this->executeQuery("SELECT create_author('$fullname', '$email', '$password')");

    }

    function getAllPostsByAuthor($id): bool|array
    {
        return $this->select("SELECT * FROM search_news_by_author_id($id)");
    }

    function createNews($title, $content, $cate_id): bool|\PgSql\Result
    {
        $author_id = $_SESSION['cred']['id'];
        return $this->executeQuery("SELECT create_news('$title', '$content', $cate_id, $author_id)");
    }

    function createKeyword($keyword): bool|\PgSql\Result
    {
        return $this->executeQuery("SELECT create_keyword('$keyword')");
    }

    function deletePost($id): \PgSql\Result|bool
    {
        return $this->executeQuery("SELECT delete_news_by_author_id($id, " . $_SESSION['cred']['id'] . ")");
    }

    function getNewsById($id): bool|array
    {
        return $this->select("SELECT * FROM search_news_by_id($id)");
    }

    function createKeywordMap($kw_id, $news_id): \PgSql\Result|bool
    {
        return $this->executeQuery("SELECT create_news_keyword($kw_id, $news_id)");
    }

    function getKeywordsByNewsId($id): bool|array
    {
        return $this->select("SELECT * FROM search_keywords_by_news_id($id)");
    }

    function getCategoryByNewsId($id): bool|array
    {
        return $this->select("SELECT * FROM search_category_by_news_id($id)");
    }

    function getAllCategories(): bool|array
    {
        return $this->select("SELECT * FROM list_all_categories()");
    }

    function listCommentsFromPost($id): bool|array
    {
        return $this->select("SELECT * FROM search_comment_by_news_id($id)");
    }

    function addComment(mixed $news_id, mixed $name, mixed $comment): \PgSql\Result|bool
    {
        return $this->executeQuery("SELECT create_comment('$name', '$comment', '$news_id')");
    }

    function getAllStories(): bool|array
    {
        return $this->select("SELECT * FROM story");
    }

    public function createStoryMap(mixed $story_id, mixed $post_id): \PgSql\Result|bool
    {
        return $this->executeQuery("SELECT create_news_story($story_id, $post_id)");
    }
}
