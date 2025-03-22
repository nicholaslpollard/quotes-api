<?php
// Handle CORS (Cross-Origin Resource Sharing)
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Get the request method
$method = $_SERVER['REQUEST_METHOD'];

// If the request method is OPTIONS (preflight request), return the allowed methods and headers
if ($method === 'OPTIONS') {
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
    exit();
}

// Include database connection
require_once '../../includes/db.php';

// Handle GET requests for categories
if ($method === 'GET') {
    if (isset($_GET['id'])) {
        // Get a specific category
        $id = $_GET['id'];
        $query = "SELECT * FROM categories WHERE id = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$id]);
        $category = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($category) {
            echo json_encode($category);
        } else {
            echo json_encode(["message" => "category_id Not Found"]);
        }
    } else {
        // Get all categories
        $query = "SELECT * FROM categories";
        $stmt = $pdo->query($query);
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($categories) {
            echo json_encode($categories);
        } else {
            echo json_encode(["message" => "No Categories Found"]);
        }
    }
}

// Handle POST requests for categories
if ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['category'])) {
        // Insert a new category
        $category = $input['category'];
        $query = "INSERT INTO categories (category) VALUES (?)";
        $stmt = $pdo->prepare($query);

        if ($stmt->execute([$category])) {
            echo json_encode(["id" => $pdo->lastInsertId(), "category" => $category]);
        } else {
            echo json_encode(["message" => "Failed to create category"]);
        }
    } else {
        echo json_encode(["message" => "Missing Required Parameters"]);
    }
}

// Handle PUT requests for categories
if ($method === 'PUT') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['id']) && isset($input['category'])) {
        // Update an existing category
        $id = $input['id'];
        $category = $input['category'];
        $query = "UPDATE categories SET category = ? WHERE id = ?";
        $stmt = $pdo->prepare($query);

        if ($stmt->execute([$category, $id])) {
            echo json_encode(["id" => $id, "category" => $category]);
        } else {
            echo json_encode(["message" => "No Categories Found"]);
        }
    } else {
        echo json_encode(["message" => "Missing Required Parameters"]);
    }
}

// Handle DELETE requests for categories
if ($method === 'DELETE') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['id'])) {
        try {
            // Attempt to delete the category
            $query = "DELETE FROM categories WHERE id = ?";
            $stmt = $pdo->prepare($query);
            if ($stmt->execute([$input['id']])) {
                echo json_encode(["id" => $input['id'], "message" => "Category deleted"]);
            } else {
                echo json_encode(["message" => "Failed to delete category"]);
            }
        } catch (PDOException $e) {
            // Catch the error if foreign key constraint fails
            echo json_encode([
                "message" => "Cannot delete category because there are quotes using this category",
                "error" => $e->getMessage()
            ]);
        }
    } else {
        echo json_encode(["message" => "Missing Required Parameters"]);
    }
}
?>
