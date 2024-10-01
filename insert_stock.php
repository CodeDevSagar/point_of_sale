<?php
// Configuration
$servername = "127.0.0.1";
$dbUsername = 'POS';
$dbPassword = '';
$dbName = 'CodeDev';

// Create a connection to the database
$conn = new mysqli($servername, $dbUsername, $dbPassword, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $category = $_POST['category'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $image = $_FILES['image'];

    // Validate the form data
    if (empty($category) || empty($name) || empty($price) || empty($image)) {
        $response = array('success' => false, 'message' => 'Please fill in all fields.');
        echo json_encode($response);
        exit;
    }

    // Upload the image
    $imagePath = 'uploads/' . basename($image['name']);
    if (
        !move_uploaded_file(
            $image['tmp_name'],
            $imagePath
        )
    ) {
        $response = array('success' => false, 'message' => 'Failed to upload image.');
        echo json_encode($response);
        exit;
    }

    // Insert the data into the database
    $stmt = $conn->prepare("INSERT INTO products (category, name, price, image) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $category, $name, $price, $imagePath);
    if ($stmt->execute()) {
        $response = array('success' => true, 'message' => 'Product added successfully.');
        echo json_encode($response);
        exit;
    } else {
        $response = array('success' => false, 'message' => 'Failed to add product.');
        echo json_encode($response);
        exit;
    }
}