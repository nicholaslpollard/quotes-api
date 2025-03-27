<?php
// Include CORS and error handling headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Include the database and Category model
include_once('../../config/Database.php');
include_once('../../models/Category.php');

// Initialize the database connection
$database = new Database();
$db = $database->getConnection();

// Create the Category object
$category = new Category($db);

// Get the ID from the URL (e.g., /categories/read_single.php?id=1)
if (isset($_GET['id'])) {
    $category->id = $_GET['id'];
} else {
    echo json_encode(array("message" => "Category ID is required."));
    exit();
}

// Retrieve the single category data
$category_data = $category->read_single();

// Check if category is found
if ($category_data) {
    // Return category details in JSON format
    echo json_encode(array(
        "id" => $category_data['id'],  // Assuming read_single() returns an associative array with id and category fields
        "category" => $category_data['category']
    ));
} else {
    // If no category is found, return a message
    echo json_encode(array("message" => "category_id Not Found"));
}
?>




