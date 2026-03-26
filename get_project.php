<?php
include 'db_connect.php';

// Use prepared statement to prevent SQL injection
$id = intval($_GET['id']);

// select columns individually to make it clear what is returned
$stmt = $conn->prepare(
    "SELECT id, name, client_name, project_address, description, category, estimated_budget, project_phase, status, created_at, updated_at 
     FROM products WHERE id = ?"
);
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
$data = $res->fetch_assoc() ?: []; // Ensure $data is an array if not found
$stmt->close();

$imgStmt = $conn->prepare("SELECT id, image_path FROM project_images WHERE project_id = ? ORDER BY order_index ASC");
$imgStmt->bind_param('i', $id);
$imgStmt->execute();
$images_res = $imgStmt->get_result();
$images = [];
while($row = $images_res->fetch_assoc()) { $images[] = $row; }
$data['images'] = $images;
$imgStmt->close();

echo json_encode($data);
