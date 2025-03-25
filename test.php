<?php
require_once 'config/Database.php';

// Instantiate DB & connect
$database = new Database();
$conn = $database->connect();

if ($conn) {
    echo "Database connected successfully!";
} else {
    echo "Database connection failed!";
}
?>

