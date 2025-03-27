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

// Get the ID from the URL (e.g., /quotes/update.php?id=1)
if (isset($_GET['id'])) {
    $quote->id = $_GET['id'];
} else {
    echo json_encode(array("message" => "Quote ID is required."));
    exit();
}

// Retrieve the posted data (JSON)
$data = json_decode(file_get_contents("php://input"));

// Check if required fields are set
if (empty($data->author_id) || empty($data->category_id) || empty($data->quote)) {
    echo json_encode(array("message" => "Author ID, Category ID, and Quote text are required."));
    exit();
}

// Set the Quote object properties
$quote->author_id = $data->author_id;
$quote->category_id = $data->category_id;
$quote->quote = $data->quote;  // Use "quote" instead of "text"

// Update the quote
if ($quote->update()) {
    echo json_encode(array("message" => "Quote was updated."));
} else {
    echo json_encode(array("message" => "Unable to update quote."));
}
?>
