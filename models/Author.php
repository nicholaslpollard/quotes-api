<?php
class Author {
    private $conn;
    private $table = 'authors';

    // Author properties
    public $id;
    public $author;

    // Constructor with DB
    public function __construct($db) {
        $this->conn = $db;
    }

    // Create a new author
    public function create() {
        // PostgreSQL will auto-generate the id if it's set as SERIAL
        $query = 'INSERT INTO ' . $this->table . ' (author) VALUES (:author) RETURNING id';
        
        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Clean input
        $this->author = htmlspecialchars(strip_tags($this->author));

        // Bind values
        $stmt->bindParam(':author', $this->author);

        // Execute query
        if ($stmt->execute()) {
            // If successful, we can get the inserted id and return true
            $this->id = $stmt->fetch(PDO::FETCH_ASSOC)['id'];
            return true;
        }

        // Print error if something goes wrong
        printf("Error: %s.\n", $stmt->errorInfo());
        return false;
    }

    // Read all authors (for use in other CRUD operations like index or read)
    public function read() {
        $query = 'SELECT id, author FROM ' . $this->table;

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    // Read single author (for use in read_single.php)
    public function read_single() {
        $query = 'SELECT id, author FROM ' . $this->table . ' WHERE id = :id LIMIT 1';

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update author
    public function update() {
        $query = 'UPDATE ' . $this->table . ' SET author = :author WHERE id = :id';

        $stmt = $this->conn->prepare($query);

        // Clean input
        $this->author = htmlspecialchars(strip_tags($this->author));

        // Bind values
        $stmt->bindParam(':author', $this->author);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }

        printf("Error: %s.\n", $stmt->errorInfo());
        return false;
    }

    // Delete author
    public function delete() {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }

        printf("Error: %s.\n", $stmt->errorInfo());
        return false;
    }
}
?>

