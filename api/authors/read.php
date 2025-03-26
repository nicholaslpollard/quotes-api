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

// Retrieve all authors
$stmt = $author->read();
$num = $stmt->rowCount();  // rowCount() should now be valid on the PDOStatement

// Check if authors are found
if ($num > 0) {
    $authors_arr = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $author_item = array(
            "id" => $id,
            "author" => $author  // Make sure this is the correct field in your table
        );
        array_push($authors_arr, $author_item);
    }

    // Return the authors as JSON
    echo json_encode($authors_arr);
} else {
    echo json_encode(array("message" => "No authors found."));
}
?>

