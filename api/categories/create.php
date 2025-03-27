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

$data = json_decode(file_get_contents("php://input"));

if (!isset($data->category) || empty($data->category)) {
    echo json_encode(["message" => "Category field is required."]);
    exit();
}

$category->category = $data->category;

$query = "INSERT INTO categories (category) VALUES (:category) RETURNING id, category";
$stmt = $db->prepare($query);
$stmt->bindParam(':category', $category->category);

if ($stmt->execute()) {
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($result);
} else {
    echo json_encode(["message" => "Unable to create category."]);
}
?>


