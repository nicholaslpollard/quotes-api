<?php
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

// Get the raw POST data (JSON)
$data = json_decode(file_get_contents("php://input"));

// Check if 'category' field is set
if (!isset($data->category) || empty($data->category)) {
    echo json_encode(["message" => "Category field is required"]);
    exit();
}

// Set the Category object properties
$category->category = $data->category;

// Create the category
if ($category->create()) {
    echo json_encode(array("message" => "Category was created."));
} else {
    echo json_encode(array("message" => "Unable to create category."));
}
?>



