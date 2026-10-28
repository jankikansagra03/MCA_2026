<?php
class DBConnection
{
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    // private $database = "my_database";

    private $con;

    public function __construct($database)
    {
        try {
            $this->con = new mysqli($this->host, $this->username, $this->password, $database);
            if ($this->con->connect_error) {
                throw new Exception("Connection failed: " . $this->con->connect_error);
            }
        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function getConnection()
    {
        return $this->con;
    }

    public function createDatabase($database)
    {
        $sql = "CREATE DATABASE IF NOT EXISTS $database";
        if ($this->con->query($sql) === TRUE) {
            echo "Database created successfully or already exists.";
        } else {
            echo "Error creating database: " . $this->con->error;
        }
    }

    public function selectDatabase($database)
    {
        if ($this->con->select_db($database)) {
            echo "Database '$database' selected successfully.";
        } else {
            echo "Error selecting database: " . $this->con->error;
        }
    }
    public function createTable($tableName, $columns)
    {
        $sql = "CREATE TABLE IF NOT EXISTS $tableName ($columns)";
        if ($this->con->query($sql) === TRUE) {
            echo "Table '$tableName' created successfully or already exists.";
        } else {
            echo "Error creating table: " . $this->con->error;
        }
    }

    public function closeConnection()
    {
        $this->con->close();
    }

    public function deleteDatabase($database)
    {
        $sql = "DROP DATABASE IF EXISTS $database";
        if ($this->con->query($sql) === TRUE) {
            echo "Database '$database' deleted successfully.";
        } else {
            echo "Error deleting database: " . $this->con->error;
        }
    }

    public function deleteTable($tableName)
    {
        $sql = "DROP TABLE IF EXISTS $tableName";
        if ($this->con->query($sql) === TRUE) {
            echo "Table '$tableName' deleted successfully.";
        } else {
            echo "Error deleting table: " . $this->con->error;
        }
    }
}


