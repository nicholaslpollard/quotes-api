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

// Get the ID from the URL (e.g., /categories/update.php?id=1)
if (isset($_GET['id'])) {
    $category->id = $_GET['id'];
} else {
    echo json_encode(array("message" => "Category ID is required."));
    exit();
}

// Retrieve the posted data (JSON)
$data = json_decode(file_get_contents("php://input"));

// Check if required fields are set
if (empty($data->category)) {
    echo json_encode(array("message" => "Category field is required."));
    exit();
}

// Set the Category object properties
$category->category = $data->category;

// Update the category
if ($category->update()) {
    echo json_encode(array("message" => "Category was updated."));
} else {
    echo json_encode(array("message" => "Unable to update category."));
}
?>


