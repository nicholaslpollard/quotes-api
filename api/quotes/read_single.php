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
        $author->id = $quote_obj->author_id;
        $author_data = $author->read_single();  // Assuming this method fetches the author's name

        $category->id = $quote_obj->category_id;
        $category_data = $category->read_single();  // Assuming this method fetches the category name

        // If author and category data are fetched successfully, return the quote
        if ($author_data && $category_data) {
            echo json_encode(array(
                "id" => $quote_obj->id,
                "quote" => $quote_obj->quote,
                "author" => $author_data->author,  // Assuming the author's name is returned from read_single()
                "category" => $category_data->category  // Assuming the category name is returned from read_single()
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

