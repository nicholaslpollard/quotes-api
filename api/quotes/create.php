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

// Check if 'quote', 'author_id', and 'category_id' fields are set
if (!isset($data->quote) || empty($data->quote) ||
    !isset($data->author_id) || empty($data->author_id) ||
    !isset($data->category_id) || empty($data->category_id)) {
    echo json_encode(["message" => "Missing Required Parameters"]);
    exit();
}

// Set the Quote object properties
$quote->quote = $data->quote;
$quote->author_id = $data->author_id;
$quote->category_id = $data->category_id;

// Check if author_id exists in authors table
$author_check_query = "SELECT id FROM authors WHERE id = :author_id";
$author_check_stmt = $db->prepare($author_check_query);
$author_check_stmt->bindParam(':author_id', $quote->author_id);
$author_check_stmt->execute();

if ($author_check_stmt->rowCount() == 0) {
    echo json_encode(["message" => "author_id Not Found"]);
    exit();
}

// Check if category_id exists in categories table
$category_check_query = "SELECT id FROM categories WHERE id = :category_id";
$category_check_stmt = $db->prepare($category_check_query);
$category_check_stmt->bindParam(':category_id', $quote->category_id);
$category_check_stmt->execute();

if ($category_check_stmt->rowCount() == 0) {
    echo json_encode(["message" => "category_id Not Found"]);
    exit();
}

// Create the quote (SQL query for inserting the new quote)
$query = "INSERT INTO quotes (quote, author_id, category_id) VALUES (:quote, :author_id, :category_id)";
$stmt = $db->prepare($query);
$stmt->bindParam(':quote', $quote->quote);
$stmt->bindParam(':author_id', $quote->author_id);
$stmt->bindParam(':category_id', $quote->category_id);

// Execute the query and return the created quote data with id, quote, author_id, and category_id fields
if ($stmt->execute()) {
    // Get the last inserted quote ID
    $quote_id = $db->lastInsertId();

    // Return the created quote data as JSON
    echo json_encode(array(
        "id" => $quote_id,  // Return the ID of the newly created quote
        "quote" => $quote->quote,  // Return the quote
        "author_id" => $quote->author_id,  // Return the author ID
        "category_id" => $quote->category_id  // Return the category ID
    ));
} else {
    echo json_encode(array("message" => "Unable to create quote."));
}
?>


