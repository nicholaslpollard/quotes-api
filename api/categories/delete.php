<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'OPTIONS') {
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
    exit();
}

include_once('../../config/Database.php');
include_once('../../models/Category.php');

$database = new Database();
$db = $database->getConnection();

$category = new Category($db);

if (isset($_GET['id'])) {
    $category->id = $_GET['id'];
} else {
    echo json_encode(["message" => "Category ID is required."]);
    exit();
}

if ($category->delete()) {
    echo json_encode(["message" => "Category was deleted."]);
} else {
    echo json_encode(["message" => "Unable to delete category."]);
}
?>



