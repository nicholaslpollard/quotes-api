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

// Include the database and models for Quote, Author, and Category
include_once('../../config/Database.php');
include_once('../../models/Quote.php');
include_once('../../models/Author.php');
include_once('../../models/Category.php');

// Initialize database connection
$database = new Database();
$db = $database->getConnection();

// Create new instances of Quote, Author, and Category classes
$quote = new Quote($db);
$author = new Author($db);
$category = new Category($db);

// Check if ID is provided in the query parameters
if (isset($_GET['id'])) {
    $quote->id = $_GET['id'];

    // Call the read_single method to fetch the quote
    $quote_obj = $quote->read_single();

    // If the quote was found
    if ($quote_obj) {
        // Set author ID and category ID from the quote data
        $author->id = $quote_obj->author_id;  // Access author_id as a property of the object
        $author_data = $author->read_single();  // Fetch the author data

        $category->id = $quote_obj->category_id;  // Access category_id as a property of the object
        $category_data = $category->read_single();  // Fetch the category data

        // If author and category data are fetched successfully, return the quote
        if ($author_data && $category_data) {
            echo json_encode(array(
                "id" => $quote_obj->id,  // Access id as a property of the object
                "quote" => $quote_obj->quote,  // Access quote as a property of the object
                "author" => $author_data['author'],  // Access author's name from the array
                "category" => $category_data['category']  // Access category's name from the array
            ));
        } else {
            // If author or category not found, return an error message
            echo json_encode(array("message" => "Author or Category not found"));
        }
    } else {
        // If no quote was found, return a message
        echo json_encode(array("message" => "Quote not found"));
    }
} else {
    // If ID is not provided, return an error message
    echo json_encode(array("message" => "Quote ID is required"));
}
?>

