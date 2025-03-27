<?php
// Include CORS and error handling headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Include the database and Author model
include_once('../../config/Database.php');
include_once('../../models/Author.php');

// Initialize the database connection
$database = new Database();
$db = $database->getConnection();

// Create the Author object
$author = new Author($db);

// Get the ID from the URL (e.g., /authors/read_single.php?id=1)
if (isset($_GET['id'])) {
    $author->id = $_GET['id'];
} else {
    echo json_encode(array("message" => "Author ID is required."));
    exit();
}

// Retrieve the single author data
$author_data = $author->read_single();

// Check if author is found
if ($author_data) {
    // Return author details in JSON format
    echo json_encode(array(
        "id" => $author_data['id'],  // Assuming read_single() returns an associative array with id and author fields
        "author" => $author_data['author']
    ));
} else {
    // If no author is found, return a message
    echo json_encode(array("message" => "author_id Not Found"));
}
?>

