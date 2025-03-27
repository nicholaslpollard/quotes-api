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

// Get the ID from the URL (e.g., /quotes/delete.php?id=1)
if (isset($_GET['id'])) {
    $quote->id = $_GET['id'];
} else {
    echo json_encode(array("message" => "Quote ID is required."));
    exit();
}

// Delete the quote
if ($quote->delete()) {
    echo json_encode(array("id" => $quote->id));  // Return the id of the deleted quote
} else {
    echo json_encode(array("message" => "No Quotes Found"));
}
?>
