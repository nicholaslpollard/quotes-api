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
        $query = 'INSERT INTO ' . $this->table . ' (category) VALUES (:category)';
        
        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Clean input
        $this->category = htmlspecialchars(strip_tags($this->category));

        // Bind values
        $stmt->bindParam(':category', $this->category);

        // Execute query
        if ($stmt->execute()) {
            return true;
        }

        // Print error if something goes wrong
        printf("Error: %s.\n", $stmt->errorInfo()[2]);
        return false;
    }

    // Read all categories
    public function read() {
        $query = 'SELECT id, category FROM ' . $this->table;

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        // Fetch and return as objects
        $categories = $stmt->fetchAll(PDO::FETCH_OBJ);

        // Return an empty object with a message if no categories found
        return !empty($categories) ? $categories : (object)["message" => "No categories found"];
    }

    // Read a single category by ID
    public function read_single() {
        $query = 'SELECT id, category FROM ' . $this->table . ' WHERE id = :id LIMIT 1';

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();

        $category = $stmt->fetch(PDO::FETCH_OBJ);

        // Return category object or message if not found
        return $category ? $category : (object)["message" => "Category not found"];
    }

    // Get category by ID (used when displaying quotes with category name instead of ID)
    public function get_category_by_id() {
        $query = 'SELECT category FROM ' . $this->table . ' WHERE id = :id LIMIT 1';

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();

        $category = $stmt->fetch(PDO::FETCH_OBJ);
        // Return category object or null if not found
        return $category ? $category : null;
    }

    // Update an existing category
    public function update() {
        $query = 'UPDATE ' . $this->table . ' SET category = :category WHERE id = :id';

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category', $this->category);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }

        printf("Error: %s.\n", $stmt->errorInfo()[2]);
        return false;
    }

    // Delete a category
    public function delete() {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }

        printf("Error: %s.\n", $stmt->errorInfo()[2]);
        return false;
    }
}
?>



