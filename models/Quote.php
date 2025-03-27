<?php
class Quote {
    private $conn;
    private $table = 'quotes';

    // Quote properties
    public $id;
    public $quote;
    public $author_id;
    public $category_id;
    public $author;
    public $category;

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
        return false;
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
            // Fetch all quotes as associative arrays
            $quotes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Create an array of Quote objects
            $quote_objects = [];
            foreach ($quotes as $row) {
                $quote_obj = new Quote($this->conn);
                $quote_obj->id = $row['id'];
                $quote_obj->quote = $row['quote'];
                $quote_obj->author = $row['author'];
                $quote_obj->category = $row['category'];
                $quote_objects[] = $quote_obj;
            }
            return $quote_objects;
        } else {
            return null;  // No quotes found
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
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Return a Quote object
            $quote_obj = new Quote($this->conn);
            $quote_obj->id = $row['id'];
            $quote_obj->quote = $row['quote'];
            $quote_obj->author = $row['author'];
            $quote_obj->category = $row['category'];
            return $quote_obj;
        }

        return false;  // No quote found
    }
}
?>
