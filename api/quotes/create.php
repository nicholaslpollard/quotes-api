<?php
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

// Get the raw POST data (JSON)
$data = json_decode(file_get_contents("php://input"));

// Check if 'quote' field is set
if (!isset($data->quote) || empty($data->quote)) {
    echo json_encode(["message" => "Quote field is required"]);
    exit();
}

// Check if 'author_id' is set and valid
if (!isset($data->author_id) || empty($data->author_id)) {
    echo json_encode(["message" => "Author ID is required"]);
    exit();
}

// Check if 'category_id' is set and valid
if (!isset($data->category_id) || empty($data->category_id)) {
    echo json_encode(["message" => "Category ID is required"]);
    exit();
}

// Set the Quote object properties
$quote->quote = $data->quote;
$quote->author_id = $data->author_id;
$quote->category_id = $data->category_id;  // Assuming category_id is passed as part of the JSON

// Create the quote
if ($quote->create()) {
    echo json_encode(array("message" => "Quote was created."));
} else {
    echo json_encode(array("message" => "Unable to create quote."));
}
?>


