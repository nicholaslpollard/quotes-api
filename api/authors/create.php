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
    echo json_encode(["message" => "Missing Required Parameters"]);
    exit();
}

// Set the Author object properties
$author->author = $data->author;

// Create the author (SQL query for inserting the new author)
$query = "INSERT INTO authors (author) VALUES (:author)";
$stmt = $db->prepare($query);
$stmt->bindParam(':author', $author->author);

// Execute the query
if ($stmt->execute()) {
    // Get the last inserted author ID
    $author_id = $db->lastInsertId();

    // Return the created author data with id and author fields
    echo json_encode(array(
        "id" => $author_id,  // Return the ID of the newly created author
        "author" => $author->author  // Return the name of the newly created author
    ));
} else {
    echo json_encode(array("message" => "Unable to create author."));
}
?>


