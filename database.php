<?php
class Database {
    private $host = 'localhost';
    private $dbname = 'moviemood';
    private $username = 'root';
    private $password = ''; // Enter your actual MySQL password here
    private $conn;

    public function __construct() {
        try {
            // Use curly braces to access private properties in the connection string
            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->conn;
    }
}

// Create a global connection for legacy code
$db = new Database();
$conn = $db->getConnection();

// Function to get PDO connection for direct usage
function getPDO() {
    global $db;
    return $db->getConnection();
}

