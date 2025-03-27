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

include_once '../../config/Database.php';
include_once '../../models/Quote.php';

$database = new Database();
$db = $database->connect();

$quote = new Quote($db);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->quote) && !empty($data->author_id) && !empty($data->category_id)) {
    $quote->quote = $data->quote;
    $quote->author_id = $data->author_id;
    $quote->category_id = $data->category_id;

    if ($quote->create()) {
        echo json_encode((object)["id" => $quote->id, "quote" => $quote->quote, "author_id" => $quote->author_id, "category_id" => $quote->category_id]);
    } else {
        echo json_encode((object)["message" => "Quote not created"]);
    }
} else {
    echo json_encode((object)["message" => "Missing required fields"]);
}
?>


