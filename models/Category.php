<?php
class Category {
    private $conn;
    private $table = 'categories';

    public $id;
    public $category;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = 'INSERT INTO ' . $this->table . ' (category) VALUES (:category) RETURNING id';
        $stmt = $this->conn->prepare($query);
        $this->category = htmlspecialchars(strip_tags($this->category));
        $stmt->bindParam(':category', $this->category);

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
        $query = 'SELECT id, category FROM ' . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function read_single() {
        $query = 'SELECT id, category FROM ' . $this->table . ' WHERE id = :id LIMIT 1';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();

        $category = $stmt->fetch(PDO::FETCH_OBJ);
        return $category ?: ["message" => "Category not found"];
    }

    public function update() {
        $query = 'UPDATE ' . $this->table . ' SET category = :category WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $this->category = htmlspecialchars(strip_tags($this->category));
        $stmt->bindParam(':category', $this->category);
        $stmt->bindParam(':id', $this->id);

        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            echo json_encode(["message" => "Error: " . $e->getMessage()]);
            return false;
        }
    }

    public function delete() {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
        $stmt = $this->conn->prepare($query);
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




