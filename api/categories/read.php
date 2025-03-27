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

$stmt = $category->read();
$num = $stmt->rowCount();

if ($num > 0) {
    $categories_arr = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $categories_arr[] = ["id" => $id, "category" => $category];
    }
    echo json_encode($categories_arr);
} else {
    echo json_encode(["message" => "No categories found."]);
}
?>
