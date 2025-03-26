<?php
// Ensure the correct file paths to the Database, Author, Category, and Quote models
include_once('config/Database.php');
include_once('models/Author.php');
include_once('models/Category.php');
include_once('models/Quote.php');

// Initialize the database connection
$database = new Database();
$db = $database->getConnection();

// Create the Author, Category, and Quote objects
$author = new Author($db);
$category = new Category($db);
$quote = new Quote($db);

// Retrieve all authors, categories, and quotes
$authors = $author->read();
$categories = $category->read();
$quotes = $quote->read();

// Output the header with styling for background and text color
echo "
    <html>
    <head>
        <style>
            body {
                background-color: gold;
                color: black;
                font-family: Arial, sans-serif;
                text-align: center;
            }
            h1 {
                font-size: 48px;
                margin-top: 50px;
            }
            h2 {
                font-size: 24px;
            }
            hr {
                border: 1px solid black;
                width: 80%;
                margin-top: 20px;
            }
        </style>
    </head>
    <body>
        <h1>Nicholas Pollard's Quotes Database</h1>
        <h2>INF 653 Midterm Project</h2>
        <hr>
    </body>
    </html>";
?>

