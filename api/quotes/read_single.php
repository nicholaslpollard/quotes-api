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

// Initialize database connection
$database = new Database();
$db = $database->getConnection();

// Create a new Quote object
$quote = new Quote($db);

// Check if ID is provided
if (isset($_GET['id'])) {
    $quote->id = $_GET['id'];
    $result = $quote->read_single();

    // Ensure result is an object or return an appropriate error message
    if (is_object($result)) {
        echo json_encode($result);
    } else {
        echo json_encode((object)["message" => "No quote found with the provided id."]);
    }
}
// Check if both author_id and category_id are provided
elseif (isset($_GET['author_id']) && isset($_GET['category_id'])) {
    $quote->author_id = $_GET['author_id'];
    $quote->category_id = $_GET['category_id'];
    
    $result = $quote->read_by_author_and_category();

    // Ensure result is an array of objects; if empty, return an error message object
    if (!empty($result)) {
        echo json_encode($result);
    } else {
        echo json_encode((object)["message" => "No quotes found for the provided author_id and category_id."]);
    }
} 
// If neither is provided, return an error
else {
    echo json_encode((object)["message" => "Quote ID or Author ID and Category ID are required."]);
}
?>
