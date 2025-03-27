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
include_once('../../models/Author.php');
include_once('../../models/Category.php');

// Initialize database connection
$database = new Database();
$db = $database->getConnection();

// Create new Quote, Author, and Category objects
$quote = new Quote($db);
$author = new Author($db);
$category = new Category($db);

// Check if ID is provided
if (isset($_GET['id'])) {
    $quote->id = $_GET['id'];
    
    // Call the read_single method to fetch the quote
    if ($quote->read_single()) {
        // Fetch the full author and category details
        $author->id = $quote->author_id;
        $author->read_single();  // Assuming this method fetches the author name

        $category->id = $quote->category_id;
        $category->read_single();  // Assuming this method fetches the category name

        // Return the quote as a single JSON object with author and category names
        echo json_encode(array(
            "id" => $quote->id,
            "quote" => $quote->quote,
            "author" => $author->author,
            "category" => $category->category
        ));
    } else {
        // If no quote was found, return a JSON object with a message
        echo json_encode(array("message" => "No Quotes Found"));
    }
}
// Check if both author_id and category_id are provided
elseif (isset($_GET['author_id']) && isset($_GET['category_id'])) {
    $quote->author_id = $_GET['author_id'];
    $quote->category_id = $_GET['category_id'];
    
    // Call the read_by_author_and_category method to fetch quotes
    $result = $quote->read_by_author_and_category();
    
    // Check if any quotes were found
    if ($result) {
        echo json_encode($result);
    } else {
        echo json_encode(array("message" => "No quotes found for the provided author_id and category_id."));
    }
} 
// If neither is provided, return an error
else {
    echo json_encode(array("message" => "Quote ID or Author ID and Category ID are required."));
}
?>

