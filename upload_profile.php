<?php
session_start();
include("connection.php");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

if (!isset($_FILES['profile_image'])) {
    echo json_encode(['success' => false, 'message' => 'No image uploaded']);
    exit;
}

$file = $_FILES['profile_image'];
$userId = $_SESSION['user_id'];

// Validate file type
$allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
if (!in_array($file['type'], $allowed_types)) {
    echo json_encode(['success' => false, 'message' => 'Invalid file type']);
    exit;
}

// Create upload directory if it doesn't exist
$upload_dir = 'uploads/profile_images/';
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Generate unique filename
$extension = pathinfo($file['name'], PATHINFO_EXTENSION);
$filename = $userId . '_' . time() . '.' . $extension;
$filepath = $upload_dir . $filename;

// Move uploaded file
if (move_uploaded_file($file['tmp_name'], $filepath)) {
    // Update database with new image path
    $sql = "UPDATE users SET profile_image = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $filepath, $userId);
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'image_url' => $filepath
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to update database'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to upload file'
    ]);
}