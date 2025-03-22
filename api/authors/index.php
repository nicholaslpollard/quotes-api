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

// Handle GET requests for authors
if ($method === 'GET') {
    if (isset($_GET['id'])) {
        // Get a specific author
        $id = $_GET['id'];
        $query = "SELECT * FROM authors WHERE id = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$id]);
        $author = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($author) {
            echo json_encode($author);
        } else {
            echo json_encode(["message" => "author_id Not Found"]);
        }
    } else {
        // Get all authors
        $query = "SELECT * FROM authors";
        $stmt = $pdo->query($query);
        $authors = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($authors) {
            echo json_encode($authors);
        } else {
            echo json_encode(["message" => "No Authors Found"]);
        }
    }
}

// Handle POST requests for authors
if ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['author'])) {
        // Insert a new author
        $author = $input['author'];
        $query = "INSERT INTO authors (author) VALUES (?)";
        $stmt = $pdo->prepare($query);

        if ($stmt->execute([$author])) {
            echo json_encode(["id" => $pdo->lastInsertId(), "author" => $author]);
        } else {
            echo json_encode(["message" => "Failed to create author"]);
        }
    } else {
        echo json_encode(["message" => "Missing Required Parameters"]);
    }
}

// Handle PUT requests for authors
if ($method === 'PUT') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['id']) && isset($input['author'])) {
        // Update an existing author
        $id = $input['id'];
        $author = $input['author'];
        $query = "UPDATE authors SET author = ? WHERE id = ?";
        $stmt = $pdo->prepare($query);

        if ($stmt->execute([$author, $id])) {
            echo json_encode(["id" => $id, "author" => $author]);
        } else {
            echo json_encode(["message" => "No Authors Found"]);
        }
    } else {
        echo json_encode(["message" => "Missing Required Parameters"]);
    }
}

// Handle DELETE requests for authors
if ($method === 'DELETE') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['id'])) {
        try {
            // Attempt to delete the author
            $query = "DELETE FROM authors WHERE id = ?";
            $stmt = $pdo->prepare($query);
            if ($stmt->execute([$input['id']])) {
                echo json_encode(["id" => $input['id'], "message" => "Author deleted"]);
            } else {
                echo json_encode(["message" => "Failed to delete author"]);
            }
        } catch (PDOException $e) {
            // Catch the error if foreign key constraint fails
            echo json_encode([
                "message" => "Cannot delete author because there are quotes using this author",
                "error" => $e->getMessage()
            ]);
        }
    } else {
        echo json_encode(["message" => "Missing Required Parameters"]);
    }
}
?>

