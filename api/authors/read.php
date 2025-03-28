<?php
// Include CORS and error handling headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'OPTIONS') {
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
    exit();
}

// Include the database and Author model
include_once('../../config/Database.php');
include_once('../../models/Author.php');

// Initialize the database connection
$database = new Database();
$db = $database->getConnection();

// Create the Author object
$author = new Author($db);

// Retrieve all authors
$stmt = $author->read();
$authors = $stmt->fetchAll(PDO::FETCH_OBJ); // Fetch as objects

// Check if authors are found
if (!empty($authors)) {
    echo json_encode($authors);
} else {
    echo json_encode((object)["message" => "No authors found."]);
}
?>
