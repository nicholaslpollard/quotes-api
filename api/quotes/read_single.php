<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Quote.php';

$database = new Database();
$db = $database->connect();

$quote = new Quote($db);

$quote->id = isset($_GET['id']) ? $_GET['id'] : die();

$quoteData = $quote->read_single();

// Ensure the response is an object
if (is_object($quoteData)) {
    echo json_encode($quoteData);
} else {
    echo json_encode((object)["message" => "Quote not found"]);
}
?>
