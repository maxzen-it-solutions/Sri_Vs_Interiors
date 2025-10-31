<?php
include 'db_connect.php';
$id = intval($_GET['id']);
$res = $conn->query("SELECT * FROM products WHERE id=$id");
$data = $res->fetch_assoc();

$images_res = $conn->query("SELECT id,image_path FROM project_images WHERE project_id=$id ORDER BY order_index ASC");
$images = [];
while($row = $images_res->fetch_assoc()) { $images[] = $row; }
$data['images'] = $images;

echo json_encode($data);
