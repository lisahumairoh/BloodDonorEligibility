<?php
require_once 'config.php';

class Database {
    private $conn;
    
    public function __construct() {
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        if ($this->conn->connect_error) {
            throw new Exception("Connection failed: " . $this->conn->connect_error);
        }
    }
    
    public function getConnection() {
        return $this->conn;
    }
    
    public function close() {
        $this->conn->close();
    }
}