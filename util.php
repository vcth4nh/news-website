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
            echo "Opened database successfully\n";
            return true;
        } else {
            echo "Error : Unable to open database\n";
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

    function searchByTitle($title)
    {
        return $this->executeQuery("SELECT * FROM news WHERE title LIKE '%$title%' ORDER BY date DESC");
    }

    function searchByContent($content)
    {
        return $this->executeQuery("SELECT * FROM news WHERE content LIKE '%$content%' ORDER BY date DESC");
    }

    function searchByTitleOrContent($search_words)
    {
        return $this->executeQuery("SELECT * FROM news WHERE title LIKE '%$search_words%' OR content LIKE '%$search_words%' ORDER BY date DESC");
    }

    function __destruct()
    {
        pg_close($this->conn);
    }
}
