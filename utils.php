<?php
class DB
{
    private $conn;
    public $table;
    public function __construct($table)
    {
        $this->connect();
        $this->table = $table;
    }

    public function __destruct()
    {
        $this->disconnect();
    }

    private function connect()
    {
        $this->host = "";
        $this->user = "root";
        $this->pass = "";
        $this->dbname = "test";

        try {
            $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->dbname . '', $this->user, $this->pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            echo "<br><h1>Please create database named test</h1>";
        }

        if (!$this->conn) {
            $this->error = 'Fatal Error :' . $e->getMessage();
        }

        return $this->conn;
    }

    public function disconnect()
    {
        if ($this->conn) {
            $this->conn = null;
        }
    }

    public function createTable($table)
    {
        $sql = "CREATE TABLE `$table` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,`name` varchar(255) DEFAULT NULL,
                    `email` varchar(255) DEFAULT NULL,
                    `phone` varchar(255) DEFAULT NULL,
                    `message` varchar(255) DEFAULT NULL,
                    `status` tinyint(4) NOT NULL DEFAULT '1',
                    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
                    PRIMARY KEY (`id`)
                ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1";
        $this->execute($sql);
    }
    public function get($id = NULL)
    {
        $table = $this->table;
        $sql = "SELECT * FROM $table" . ($id ? " WHERE id = $id" : "") . " ORDER BY id DESC";
        $result = $this->conn->prepare($sql);
        $query = $result->execute();
        if ($query == false) {
            echo 'Error SQL: ' . $query;
            die();
        }
        $result->setFetchMode(PDO::FETCH_ASSOC);
        return $id ? $result->fetch() :  $result->fetchAll();
    }

    function save($data, $id = null)
    {
        $table = $this->table;

        foreach ($data as $key => $value) {
            $array[] = "`$key`='" . $value . "'";
        }
        $datatoupdate = implode(", ", $array);
        if ($id) {
            $sql = "UPDATE `$table` SET $datatoupdate WHERE id = $id";
        } else {
            $sql = "INSERT INTO  `$table` SET $datatoupdate";
        }
        return $this->execute($sql);
    }

    function delete($id)
    {
        $table = $this->table;

        $sql = "DELETE FROM $table WHERE id = $id";
        return $this->execute($sql);
    }

    /* 
    all = false = single data
    all = true  = all data
    */
    public function getData($query, $all = true)
    {
        $result = $this->conn->prepare($query);
        $query = $result->execute();
        if ($query == false) {
            echo 'Error SQL: ' . $query;
            die();
        }
        $result->setFetchMode(PDO::FETCH_ASSOC);
        return $all ? $result->fetchAll() :  $result->fetch();
    }
    public function execute($query)
    {
        $response = $this->conn->exec($query);
        return $response;
    }
}
