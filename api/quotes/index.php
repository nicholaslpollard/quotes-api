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

// Retrieve all quotes
$quotes = $quote->read();

// Check if quotes are found
if ($quotes) {
    echo json_encode($quotes);
} else {
    echo json_encode(array("message" => "No quotes found."));
}
?>
