<?php
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

// Get the raw POST data (JSON)
$data = json_decode(file_get_contents("php://input"));

// Check if 'author' field is set
if (!isset($data->author) || empty($data->author)) {
    echo json_encode(["message" => "Author field is required"]);
    exit();
}

// Set the Author object properties
$author->author = $data->author;

// Create the author (SQL query update for PostgreSQL)
$query = "INSERT INTO authors (author) VALUES (:author)";
$stmt = $db->prepare($query);
$stmt->bindParam(':author', $author->author);

// Execute the query and return appropriate response
if ($stmt->execute()) {
    echo json_encode(array("message" => "Author was created."));
} else {
    echo json_encode(array("message" => "Unable to create author."));
}
?>


