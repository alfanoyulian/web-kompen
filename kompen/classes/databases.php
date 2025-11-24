<?php
class Database{
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $db = "db_kompen";
    public $conn;

    function __construct(){
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db);
    }
}
?>