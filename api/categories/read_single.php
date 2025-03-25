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
    echo json_encode($category_data);
} else {
    echo json_encode(array("message" => "Category not found."));
}
?>


