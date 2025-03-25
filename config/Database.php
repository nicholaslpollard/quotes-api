<?php
class Database {
    private $host = "localhost";  // PostgreSQL host
    private $db_name = "quotesdb";  // The PostgreSQL database name
    private $username = "postgres";  // PostgreSQL username (default is usually 'postgres')
    private $password = "admin";  // The password for the PostgreSQL user
    public $conn;

    // Get the database connection
    public function getConnection() {
        $this->conn = null;

        try {
            // Use PostgreSQL connection string
            $this->conn = new PDO("pgsql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  // Set error mode to exception
        } catch (PDOException $exception) {
            // Improved error message handling
            echo json_encode(array("message" => "Connection error: " . $exception->getMessage()));
            exit();  // Exit the script if the connection fails
        }

        return $this->conn;
    }

    // Close the database connection
    public function closeConnection() {
        $this->conn = null;
    }
}
?>


