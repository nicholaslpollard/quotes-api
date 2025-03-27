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
            return (object)["message" => "Error: " . $e->getMessage()];
        }

        // If something goes wrong
        return (object)["message" => "Unable to create quote."];
    }

    // Read all quotes with optional filtering by author_id and category_id
    public function read($author_id = null, $category_id = null) {
        // Base query with joins to get actual author and category names
        $query = 'SELECT q.id, q.quote, a.author, c.category 
                  FROM ' . $this->table . ' q
                  LEFT JOIN authors a ON q.author_id = a.id
                  LEFT JOIN categories c ON q.category_id = c.id';

        // Add conditions if filters are provided
        $conditions = [];
        if (!is_null($author_id)) {
            $conditions[] = 'q.author_id = :author_id';
        }
        if (!is_null($category_id)) {
            $conditions[] = 'q.category_id = :category_id';
        }
        
        if (!empty($conditions)) {
            $query .= ' WHERE ' . implode(' AND ', $conditions);
        }

        // Prepare the statement
        $stmt = $this->conn->prepare($query);

        // Bind filter parameters
        if (!is_null($author_id)) {
            $stmt->bindParam(':author_id', $author_id);
        }
        if (!is_null($category_id)) {
            $stmt->bindParam(':category_id', $category_id);
        }

        // Execute the query
        $stmt->execute();

        // Check if any quotes are found
        if ($stmt->rowCount() > 0) {
            // Fetch all quotes as an associative array
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return (object)["message" => "No quotes found."];
        }
    }

    // Read a single quote with the actual author and category names
    public function read_single() {
        // Query to get a single quote with the actual author and category names
        $query = 'SELECT q.id, q.quote, a.author, c.category 
                  FROM ' . $this->table . ' q
                  LEFT JOIN authors a ON q.author_id = a.id
                  LEFT JOIN categories c ON q.category_id = c.id
                  WHERE q.id = :id LIMIT 1';

        // Prepare the statement
        $stmt = $this->conn->prepare($query);

        // Clean input
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind the ID parameter
        $stmt->bindParam(':id', $this->id);

        // Execute the query
        $stmt->execute();

        // Check if a quote is found
        if ($stmt->rowCount() > 0) {
            // Fetch the single quote as an associative array
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        return (object)["message" => "No quote found."];
    }

    // Read quotes by author_id and category_id
    public function read_by_author_and_category() {
        $query = 'SELECT q.id, q.quote, a.author, c.category 
                  FROM ' . $this->table . ' q
                  LEFT JOIN authors a ON q.author_id = a.id
                  LEFT JOIN categories c ON q.category_id = c.id
                  WHERE q.author_id = :author_id AND q.category_id = :category_id';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(':author_id', $this->author_id);
        $stmt->bindParam(':category_id', $this->category_id);

        // Execute the query
        $stmt->execute();

        // Fetch results
        if ($stmt->rowCount() > 0) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return (object)["message" => "No quotes found for this author and category."];
        }
    }

    // Update an existing quote
    public function update() {
        $query = 'UPDATE ' . $this->table . ' SET quote = :quote, author_id = :author_id, category_id = :category_id WHERE id = :id';
        
        // Prepare the statement
        $stmt = $this->conn->prepare($query);

        // Clean input
        $this->quote = htmlspecialchars(strip_tags($this->quote));

        // Bind the values
        $stmt->bindParam(':quote', $this->quote);
        $stmt->bindParam(':author_id', $this->author_id);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':id', $this->id);

        // Execute the query
        try {
            if ($stmt->execute()) {
                return true;
            }
        } catch (PDOException $e) {
            // Handle error with exception
            return (object)["message" => "Error: " . $e->getMessage()];
        }

        return (object)["message" => "Unable to update quote."];
    }

    // Delete a quote
    public function delete() {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

        // Prepare the statement
        $stmt = $this->conn->prepare($query);

        // Clean input
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind the ID parameter
        $stmt->bindParam(':id', $this->id);

        // Execute the query
        if ($stmt->execute()) {
            return (object)["message" => "Quote deleted."];
        }

        return (object)["message" => "Unable to delete quote."];
    }
}
?>
