<?php
include 'koneksi.php';

header('Content-Type: application/json');

// Ensure the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve username and password from the request
    $username = $_POST['username'];
    $sandi = $_POST['sandi'];

    // Check if username and password are not empty
    if (!empty($username) && !empty($sandi)) {
        
        $result= mysqli_query($conn,"SELECT * FROM pengguna WHERE Username = '$username' AND Sandi = '$sandi'");
        if ($result->num_rows > 0) {
            echo json_encode(['status' => 'success']);
        } else {
            http_response_code(401); // Unauthorized
            echo json_encode(['status' => 'error', 'message' => 'Invalid username or password']);
        }
    } else {
        http_response_code(400); // Bad Request
        echo json_encode(['status' => 'error', 'message' => 'Username and password required']);
    }
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}

$conn->close();
?>
