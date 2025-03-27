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
        try {
            if ($stmt->execute()) {
                // If successful, we can get the inserted id and return true
                $this->id = $stmt->fetch(PDO::FETCH_ASSOC)['id'];
                return true;
            }
        } catch (PDOException $e) {
            // Handle error with exception
            echo json_encode(array("message" => "Error: " . $e->getMessage()));
            return false;
        }

        return false;
    }

    // Read all authors (for use in other CRUD operations like index or read)
    public function read() {
        $query = 'SELECT id, author FROM ' . $this->table;

        // Prepare statement
        $stmt = $this->conn->prepare($query);
        
        // Execute the query
        $stmt->execute();

        // Check if the query is valid
        if ($stmt instanceof PDOStatement) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    // Read single author (for use in read_single.php)
    public function read_single() {
        $query = 'SELECT id, author FROM ' . $this->table . ' WHERE id = :id LIMIT 1';

        // Prepare statement
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();

        // Check if an author was found
        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC); // Return the author data
        }

        // If no author found, return a custom error message
        return array("message" => "Author not found");
    }

    // Get author by ID (used when displaying quotes with author name instead of ID)
    public function get_author_by_id() {
        $query = 'SELECT author FROM ' . $this->table . ' WHERE id = :id LIMIT 1';

        // Prepare statement
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();

        // Fetch author
        $author = $stmt->fetch(PDO::FETCH_ASSOC);
        return $author ? $author['author'] : null;  // Return author name or null
    }

    // Update author
    public function update() {
        $query = 'UPDATE ' . $this->table . ' SET author = :author WHERE id = :id';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Clean input
        $this->author = htmlspecialchars(strip_tags($this->author));

        // Bind values
        $stmt->bindParam(':author', $this->author);
        $stmt->bindParam(':id', $this->id);

        // Execute the query
        try {
            if ($stmt->execute()) {
                return true;
            }
        } catch (PDOException $e) {
            // Handle error with exception
            echo json_encode(array("message" => "Error: " . $e->getMessage()));
            return false;
        }

        return false;
    }

    // Delete author
    public function delete() {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

        // Prepare statement
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);

        // Execute the query
        try {
            if ($stmt->execute()) {
                return true;
            }
        } catch (PDOException $e) {
            // Handle error with exception
            echo json_encode(array("message" => "Error: " . $e->getMessage()));
            return false;
        }

        return false;
    }
}
?>


