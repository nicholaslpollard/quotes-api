<?php
class Quote {
    private $conn;
    private $table = 'quotes';

    // Quote properties
    public $id;
    public $quote;
    public $author_id;
    public $category_id;

    // Constructor with DB
    public function __construct($db) {
        $this->conn = $db;
    }

    // Create a new quote
    public function create() {
        $query = 'INSERT INTO ' . $this->table . ' (quote, author_id, category_id) 
                  VALUES (:quote, :author_id, :category_id) RETURNING id';
        
        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Clean input
        $this->quote = htmlspecialchars(strip_tags($this->quote));

        // Bind values
        $stmt->bindParam(':quote', $this->quote);
        $stmt->bindParam(':author_id', $this->author_id);
        $stmt->bindParam(':category_id', $this->category_id);

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
        printf("Error: %s.\n", $stmt->error);
        return false;
    }
}
?>

