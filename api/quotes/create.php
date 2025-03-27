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
    echo json_encode((object)["message" => "Quote field is required"]);
    exit();
}

// Check if 'author_id' and 'category_id' are set
if (!isset($data->author_id) || !isset($data->category_id)) {
    echo json_encode((object)["message" => "Both author_id and category_id are required"]);
    exit();
}

// Set the properties of the Quote object
$quote->quote = $data->quote;
$quote->author_id = $data->author_id;
$quote->category_id = $data->category_id;

// Attempt to create the quote
if ($quote->create()) {
    echo json_encode((object)[
        "message" => "Quote created successfully",
        "quote" => $quote // Include the created quote object (with id)
    ]);
} else {
    echo json_encode((object)["message" => "Unable to create the quote"]);
}
?>

