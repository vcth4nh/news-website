<?php

class DB
{
    public $conn;

    function __construct()
    {
        $this->conn = $this->connectDatabase();
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

    function connectDatabase()
    {
        $conn = $this->connectPgReal();
        if (!$this->checkDbConnection($conn)) die("Connection failed");
        return $conn;
    }

    function executeQuery($query)
    {
        $result = pg_query($this->conn, $query);
        if (!$result) {
            echo "An error occurred.\n";
            exit;
        }
        return $result;
    }

    function select($selectQuery)
    {
        $result = $this->executeQuery($selectQuery);
        $rows = pg_fetch_all($result);
        return $rows;
    }

    function getAllNews()
    {
        return $this->select("SELECT * FROM news ORDER BY date DESC");
    }

    function searchByTitle($title)
    {
        return $this->select("SELECT * FROM news WHERE title LIKE '%$title%' ORDER BY date DESC");
    }

    function searchByContent($content)
    {
        return $this->select("SELECT * FROM news WHERE content LIKE '%$content%' ORDER BY date DESC");
    }

    function searchByTitleOrContent($search_words)
    {
        return $this->select("SELECT * FROM news WHERE title LIKE '%$search_words%' OR content LIKE '%$search_words%' ORDER BY date DESC");
    }

    function searchByDate($dateFrom, $dateTo)
    {
        return $this->select("SELECT * FROM news WHERE date BETWEEN '$dateFrom' AND '$dateTo' ORDER BY date DESC");
    }

    function listAllKeyword()
    {
        return $this->select("SELECT * FROM keyword");
    }

    function searchByKeyword($keyword)
    {
        return $this->select("SELECT * FROM keyword WHERE keywords ='$keyword' ORDER BY date DESC");
    }

    function listAllStory()
    {
        return $this->select("SELECT * FROM story");
    }

    function searchByStory($story)
    {
        return $this->select("SELECT * FROM story WHERE story = '$story' ORDER BY date DESC");
    }

    function listCommentFromPost($postID)
    {
        return $this->select("SELECT * FROM comment WHERE post_id = '$postID'");
    }

    function __destruct()
    {
        pg_close($this->conn);
    }
}
