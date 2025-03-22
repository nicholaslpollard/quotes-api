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

// Handle GET requests for quotes
if ($method === 'GET') {
    // Check if we have query parameters like id, author_id, or category_id
    $query = "SELECT q.id, q.quote, a.author, c.category FROM quotes q
              JOIN authors a ON q.author_id = a.id
              JOIN categories c ON q.category_id = c.id";
    
    // Prepare and execute the query
    if (isset($_GET['id'])) {
        $query .= " WHERE q.id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['id' => $_GET['id']]);
    } elseif (isset($_GET['author_id'])) {
        $query .= " WHERE q.author_id = :author_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['author_id' => $_GET['author_id']]);
    } elseif (isset($_GET['category_id'])) {
        $query .= " WHERE q.category_id = :category_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['category_id' => $_GET['category_id']]);
    } else {
        $stmt = $pdo->prepare($query);
        $stmt->execute();
    }

    $quotes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Check if we have any results
    if ($quotes) {
        echo json_encode($quotes);
    } else {
        echo json_encode(["message" => "No Quotes Found"]);
    }
}

// Handle POST requests for quotes
if ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (isset($input['quote'], $input['author_id'], $input['category_id'])) {
        $query = "INSERT INTO quotes (quote, author_id, category_id) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($query);
        if ($stmt->execute([$input['quote'], $input['author_id'], $input['category_id']])) {
            echo json_encode([
                "id" => $pdo->lastInsertId(),
                "quote" => $input['quote'],
                "author_id" => $input['author_id'],
                "category_id" => $input['category_id']
            ]);
        } else {
            echo json_encode(["message" => "Failed to create quote"]);
        }
    } else {
        echo json_encode(["message" => "Missing Required Parameters"]);
    }
}

// Handle PUT requests for quotes
if ($method === 'PUT') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (isset($input['id'], $input['quote'], $input['author_id'], $input['category_id'])) {
        $query = "UPDATE quotes SET quote = ?, author_id = ?, category_id = ? WHERE id = ?";
        $stmt = $pdo->prepare($query);
        if ($stmt->execute([$input['quote'], $input['author_id'], $input['category_id'], $input['id']])) {
            echo json_encode([
                "id" => $input['id'],
                "quote" => $input['quote'],
                "author_id" => $input['author_id'],
                "category_id" => $input['category_id']
            ]);
        } else {
            echo json_encode(["message" => "Failed to update quote"]);
        }
    } else {
        echo json_encode(["message" => "Missing Required Parameters"]);
    }
}

// Handle DELETE requests for quotes
if ($method === 'DELETE') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (isset($input['id'])) {
        $query = "DELETE FROM quotes WHERE id = ?";
        $stmt = $pdo->prepare($query);
        if ($stmt->execute([$input['id']])) {
            echo json_encode(["id" => $input['id'], "message" => "Quote deleted"]);
        } else {
            echo json_encode(["message" => "Failed to delete quote"]);
        }
    } else {
        echo json_encode(["message" => "Missing Required Parameters"]);
    }
}
?>
