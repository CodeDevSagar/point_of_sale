<?php
$servername = "127.0.0.1";
$username = "CodeDev";
$password = "";
$database = "pos";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if a category is provided
$category = isset($_GET['category']) ? $_GET['category'] : null;

// Fetch items based on category
if ($category) {
    $stmt = $conn->prepare("SELECT name, price, image_path FROM stock WHERE category = ?");
    $stmt->bind_param("s", $category);
} else {
    $stmt = $conn->prepare("SELECT name, price, image_path FROM stock");
}

$stmt->execute();
$result = $stmt->get_result();

$items = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
}

echo json_encode($items);

$stmt->close();
$conn->close();
?>