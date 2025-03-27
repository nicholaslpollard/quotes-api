<?php
class Category {
    private $conn;
    private $table = 'categories';

    // Category properties
    public $id;
    public $category;

    // Constructor with DB
    public function __construct($db) {
        $this->conn = $db;
    }

    // Create a new category
    public function create() {
        $query = 'INSERT INTO ' . $this->table . ' (category) VALUES (:category) RETURNING id';
        
        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Clean input
        $this->category = htmlspecialchars(strip_tags($this->category));

        // Bind values
        $stmt->bindParam(':category', $this->category);

        // Execute query
        try {
            if ($stmt->execute()) {
                // Get the last inserted id
                $this->id = $stmt->fetchColumn();
                return true;
            }
        } catch (PDOException $e) {
            // Handle error with exception
            echo json_encode(array("message" => "Error: " . $e->getMessage()));
            return false;
        }

        // If something goes wrong
        return false;
    }

    // Read all categories
    public function read() {
        $query = 'SELECT id, category FROM ' . $this->table;

        // Prepare statement
        $stmt = $this->conn->prepare($query);
        
        // Execute the query
        $stmt->execute();

        // Return results
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Read a single category by ID
    public function read_single() {
        $query = 'SELECT id, category FROM ' . $this->table . ' WHERE id = :id LIMIT 1';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Bind the ID parameter
        $stmt->bindParam(':id', $this->id);

        // Execute the query
        $stmt->execute();

        // Check if category was found
        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);  // Return the category data
        }

        // If no category found, return a custom error message
        return array("message" => "Category not found");
    }

    // Get category by ID (used when displaying quotes with category name instead of ID)
    public function get_category_by_id() {
        $query = 'SELECT category FROM ' . $this->table . ' WHERE id = :id LIMIT 1';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Bind the ID parameter
        $stmt->bindParam(':id', $this->id);

        // Execute the query
        $stmt->execute();

        // Fetch category
        $category = $stmt->fetch(PDO::FETCH_ASSOC);
        return $category ? $category['category'] : null;  // Return category name or null
    }

    // Update an existing category
    public function update() {
        $query = 'UPDATE ' . $this->table . ' SET category = :category WHERE id = :id';
        
        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Clean input
        $this->category = htmlspecialchars(strip_tags($this->category));

        // Bind values
        $stmt->bindParam(':category', $this->category);
        $stmt->bindParam(':id', $this->id);

        // Execute query
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

    // Delete a category
    public function delete() {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Bind the ID parameter
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



