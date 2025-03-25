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

// Get the ID from the URL (e.g., /quotes/read_single.php?id=1)
if (isset($_GET['id'])) {
    $quote->id = $_GET['id'];
} else {
    echo json_encode(array("message" => "Quote ID is required."));
    exit();
}

// Retrieve the single quote data
$quote_data = $quote->read_single();

// Check if quote is found
if ($quote_data) {
    echo json_encode($quote_data);
} else {
    echo json_encode(array("message" => "Quote not found."));
}
?>

