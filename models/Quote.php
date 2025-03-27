<?php
class Quote {
    private $conn;
    private $table = 'quotes';

    public $id;
    public $quote;
    public $author_id;
    public $category_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = 'INSERT INTO ' . $this->table . ' (quote, author_id, category_id) VALUES (:quote, :author_id, :category_id) RETURNING id';
        $stmt = $this->conn->prepare($query);
        $this->quote = htmlspecialchars(strip_tags($this->quote));
        $stmt->bindParam(':quote', $this->quote);
        $stmt->bindParam(':author_id', $this->author_id);
        $stmt->bindParam(':category_id', $this->category_id);

        try {
            if ($stmt->execute()) {
                $this->id = $stmt->fetch(PDO::FETCH_OBJ)->id;
                return true;
            }
        } catch (PDOException $e) {
            echo json_encode(["message" => "Error: " . $e->getMessage()]);
            return false;
        }
        return false;
    }

    public function read() {
        $query = 'SELECT id, quote, author_id, category_id FROM ' . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function read_single() {
        $query = 'SELECT id, quote, author_id, category_id FROM ' . $this->table . ' WHERE id = :id LIMIT 1';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();

        $quote = $stmt->fetch(PDO::FETCH_OBJ);
        return $quote ?: ["message" => "Quote not found"];
    }

    public function update() {
        $query = 'UPDATE ' . $this->table . ' SET quote = :quote, author_id = :author_id, category_id = :category_id WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $this->quote = htmlspecialchars(strip_tags($this->quote));
        $stmt->bindParam(':quote', $this->quote);
        $stmt->bindParam(':author_id', $this->author_id);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':id', $this->id);

        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            echo json_encode(["message" => "Error: " . $e->getMessage()]);
            return false;
        }
    }
}
?>
