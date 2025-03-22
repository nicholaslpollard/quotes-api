<?php
// Database connection settings
$host = 'localhost'; // Update with your PostgreSQL host
$dbname = 'quotesdb'; // your PostgreSQL database name
$username = 'postgres'; // your PostgreSQL username (default is usually "postgres")
$password = ''; // your PostgreSQL password (leave empty if no password)

// Create a new PDO connection
try {
    // PostgreSQL connection string
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Set encoding (optional but recommended)
    $pdo->exec("SET NAMES 'UTF8'");
} catch (PDOException $e) {
    // Display the error message if the connection fails
    echo 'Connection failed: ' . $e->getMessage();
}
?>
