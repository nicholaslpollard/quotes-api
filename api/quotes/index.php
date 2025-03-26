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

// Include the database and Quote model
include_once('../../config/Database.php');
include_once('../../models/Quote.php');

// Initialize the database connection
$database = new Database();
$db = $database->getConnection();

// Create the Quote object
$quote = new Quote($db);

// Get query parameters for filtering
$author_id = isset($_GET['author_id']) ? $_GET['author_id'] : null;
$category_id = isset($_GET['category_id']) ? $_GET['category_id'] : null;

// Retrieve filtered quotes
$quotes = $quote->read($author_id, $category_id);

// Check if any quotes are found
if ($quotes) {
    echo json_encode($quotes);  // Return the quotes as JSON
} else {
    echo json_encode(array("message" => "No quotes found."));  // No quotes found
}
?>
