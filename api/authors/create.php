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
    echo json_encode((object)["message" => "Missing Required Parameters"]);
    exit();
}

// Set the Author object properties
$author->author = $data->author;

// Create the author (SQL query update for PostgreSQL)
$query = "INSERT INTO authors (author) VALUES (:author) RETURNING id";
$stmt = $db->prepare($query);
$stmt->bindParam(':author', $author->author);

// Execute the query and return appropriate response
if ($stmt->execute()) {
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode((object)["id" => $result['id'], "author" => $author->author]);
} else {
    echo json_encode((object)["message" => "Unable to create author"]);
}
?>

