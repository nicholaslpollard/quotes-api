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

// Include the database and Author model
include_once('../../config/Database.php');
include_once('../../models/Author.php');

// Initialize the database connection
$database = new Database();
$db = $database->getConnection();

// Create the Author object
$author = new Author($db);

// Get the ID from the URL (e.g., /authors/update.php?id=1)
if (isset($_GET['id'])) {
    $author->id = $_GET['id'];
} else {
    echo json_encode(array("message" => "Author ID is required."));
    exit();
}

// Retrieve the posted data (JSON)
$data = json_decode(file_get_contents("php://input"));

// Check if required fields are set
if (empty($data->author)) {
    echo json_encode(array("message" => "Author field is required."));
    exit();
}

// Set the Author object properties
$author->author = $data->author;

// Update the author
if ($author->update()) {
    echo json_encode(array("message" => "Author was updated."));
} else {
    echo json_encode(array("message" => "Unable to update author."));
}
?>


