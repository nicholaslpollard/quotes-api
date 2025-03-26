<?php
class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $port;
    public $conn;

    public function __construct() {
        // Check if running on Render (i.e., using environment variables)
        if (getenv('RENDER')) {
            $this->host = getenv('DB_HOST');
            $this->db_name = getenv('DB_NAME');
            $this->username = getenv('DB_USER');
            $this->password = getenv('DB_PASS');
            $this->port = getenv('DB_PORT');
        } else {
            // Local XAMPP/PostgreSQL settings
            $this->host = "localhost";
            $this->db_name = "quotesdb";
            $this->username = "postgres";
            $this->password = "admin";
            $this->port = "5432"; // Default PostgreSQL port
        }
    }

    // Get the database connection
    public function getConnection() {
        $this->conn = null;

        try {
            // Use PostgreSQL connection string
            $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->db_name}";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo json_encode(array("message" => "Connection error: " . $exception->getMessage()));
            exit();
        }

        return $this->conn;
    }

    // Close the database connection
    public function closeConnection() {
        $this->conn = null;
    }
}
?>


