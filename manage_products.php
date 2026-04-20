<?php
if (session_status() == PHP_SESSION_NONE) session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

/**
 * Modern Image Processor
 * Handles Resize, WebP Conversion, and Compression
 */
function optimizeImage($sourcePath, $targetDir)
{
    $absTargetDir = __DIR__ . "/" . $targetDir;
    if (!file_exists($absTargetDir)) mkdir($absTargetDir, 0755, true);

    $info = getimagesize($sourcePath);
    $mime = $info['mime'];

    switch ($mime) {
        case 'image/jpeg':
            $img = imagecreatefromjpeg($sourcePath);
            break;
        case 'image/png':
            $img = imagecreatefrompng($sourcePath);
            break;
        case 'image/webp':
            $img = imagecreatefromwebp($sourcePath);
            break;
        default:
            return false; // Unsupported type
    }

    $width = imagesx($img);
    $height = imagesy($img);
    $maxSize = 1200; // Reduced for better performance on live servers

    // Maintain Aspect Ratio & Resize
    if ($width > $maxSize || $height > $maxSize) {
        if ($width > $height) {
            $newWidth = $maxSize;
            $newHeight = floor($height * ($maxSize / $width));
        } else {
            $newHeight = $maxSize;
            $newWidth = floor($width * ($maxSize / $height));
        }
        $img = imagescale($img, $newWidth, $newHeight);
    }

    $baseName = pathinfo($sourcePath, PATHINFO_FILENAME) . "_" . time();
    
    // Check for WebP support and provide fallback to JPEG
    if (function_exists('imagewebp')) {
        $newName = $baseName . ".webp";
        $destination = $absTargetDir . $newName;
        imagewebp($img, $destination, 80);
    } else {
        $newName = $baseName . ".jpg";
        $destination = $absTargetDir . $newName;
        imagejpeg($img, $destination, 80);
    }
    
    imagedestroy($img);
    return $targetDir . $newName; // Returns relative path for DB
}

// ----------------- Handle Add Product -----------------
if (isset($_POST['add_product'])) {
    $name = trim($_POST['name']);
    $client_name = trim($_POST['client_name']);
    $project_address = trim($_POST['project_address']);
    $description = trim($_POST['description']);
    $category = strtolower(trim($_POST['category'] ?? ''));
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $estimated_budget = trim($_POST['estimated_budget']);
    $project_phase = $_POST['project_phase'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("INSERT INTO products (name, client_name, project_address, description, category, estimated_budget, start_date, end_date, project_phase, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssss", $name, $client_name, $project_address, $description, $category, $estimated_budget, $start_date, $end_date, $project_phase, $status);
    $stmt->execute();
    $project_id = $stmt->insert_id;

    // Upload multiple images
    if (!empty($_FILES['images']['name'][0])) {
        $targetDir = "uploads/";
        $targetDirAbs = __DIR__ . "/" . $targetDir;
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'video/mp4'];
        foreach ($_FILES['images']['tmp_name'] as $index => $tmpName) {
            if (empty($tmpName)) continue;

            $fileType = $_FILES['images']['type'][$index];
            if (!in_array($fileType, $allowedTypes)) continue;

            $mediaType = (strpos($fileType, 'video') !== false) ? 'video' : 'image';

            if ($mediaType === 'image') {
                $optimizedPath = optimizeImage($tmpName, $targetDir);
            } else {
                $newName = pathinfo($_FILES['images']['name'][$index], PATHINFO_FILENAME) . "_" . time() . "_" . $index . ".mp4";
                $optimizedPath = $targetDir . $newName; // DB path
                if (!move_uploaded_file($tmpName, $targetDirAbs . $newName)) $optimizedPath = false;
            }
            if (!$optimizedPath) continue;

            $imgStmt = $conn->prepare("INSERT INTO project_images (project_id, image_path, order_index, media_type) VALUES (?, ?, ?, ?)");
            $order_index = $index;
            $imgStmt->bind_param("isis", $project_id, $optimizedPath, $order_index, $mediaType);
            $imgStmt->execute();
        }
    }

    header("Location: manage_products.php");
    exit;
}

// ----------------- Handle Delete Product -----------------
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    // 1. Fetch image paths before deleting records
    $stmt_files = $conn->prepare("SELECT image_path FROM project_images WHERE project_id = ?");
    $stmt_files->bind_param("i", $id);
    $stmt_files->execute();
    $res = $stmt_files->get_result();
    $image_paths = [];
    while ($row = $res->fetch_assoc()) {
        $image_paths[] = $row['image_path'];
    }
    $stmt_files->close();

    // 2. Use Transaction for Atomic Deletion
    $conn->begin_transaction();
    try {
        $del_imgs = $conn->prepare("DELETE FROM project_images WHERE project_id = ?");
        $del_imgs->bind_param("i", $id);
        $del_imgs->execute();

        $del_proj = $conn->prepare("DELETE FROM products WHERE id = ?");
        $del_proj->bind_param("i", $id);
        $del_proj->execute();

        $conn->commit();

        // 3. Clean up physical files after successful DB commit
        foreach ($image_paths as $path) {
            if (!empty($path) && file_exists($path)) @unlink($path);
        }
    } catch (Exception $e) {
        $conn->rollback();
    }

    header("Location: manage_products.php?status=deleted");
    exit;
}

// ----------------- Delete Single Image -----------------
if (isset($_GET['delete_image'])) {
    $img_id = intval($_GET['delete_image']);

    // 1. Fetch path using prepared statement
    $stmt_get = $conn->prepare("SELECT project_id, image_path FROM project_images WHERE id = ?");
    $stmt_get->bind_param("i", $img_id);
    $stmt_get->execute();
    $res = $stmt_get->get_result();
    $project_id = null;
    if ($row = $res->fetch_assoc()) {
        $project_id = intval($row['project_id']);
        if (!empty($row['image_path']) && file_exists($row['image_path'])) @unlink($row['image_path']);
    }
    $stmt_get->close();

    // 2. Delete DB record
    $stmt_del = $conn->prepare("DELETE FROM project_images WHERE id = ?");
    $stmt_del->bind_param("i", $img_id);
    $stmt_del->execute();
    $stmt_del->close();

    // if AJAX request, return JSON and DO NOT redirect
    $isAjax = false;
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        $isAjax = true;
    }
    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'deleted_id' => $img_id, 'project_id' => $project_id]);
        exit;
    } else {
        // default behaviour for non-AJAX (keep backward compatibility)
        header("Location: manage_products.php");
        exit;
    }
}


// ----------------- Handle Edit Product -----------------
if (isset($_POST['edit_product'])) {
    $id = intval($_POST['id']);
    $name = trim($_POST['name']);
    $client_name = trim($_POST['client_name']);
    $project_address = trim($_POST['project_address']);
    $description = trim($_POST['description']);
    $category = strtolower(trim($_POST['category'] ?? ''));
    $estimated_budget = trim($_POST['estimated_budget']);
    // Use null coalescing operator (??) to assign null if the key doesn't exist
    // Also checking for empty string to ensure NULL is passed to DB for empty dates
    $start_date = !empty($_POST['start_date']) ? $_POST['start_date'] : null;
    $end_date = !empty($_POST['end_date']) ? $_POST['end_date'] : null;
    $project_phase = $_POST['project_phase'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE products SET 
    name=?, client_name=?, project_address=?, description=?, category=?, estimated_budget=?, start_date=?, end_date=?, project_phase=?, status=? 
    WHERE id=?");
    $stmt->bind_param("ssssssssssi", $name, $client_name, $project_address, $description, $category, $estimated_budget, $start_date, $end_date, $project_phase, $status, $id);
    $stmt->execute();

    // Upload any new images
    if (!empty($_FILES['images']['name'][0])) {
        $targetDir = "uploads/";
        $targetDirAbs = __DIR__ . "/" . $targetDir;
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'video/mp4'];
        foreach ($_FILES['images']['tmp_name'] as $index => $tmpName) {
            if (empty($tmpName)) continue;

            $fileType = $_FILES['images']['type'][$index];
            if (!in_array($fileType, $allowedTypes)) continue;

            $mediaType = (strpos($fileType, 'video') !== false) ? 'video' : 'image';

            if ($mediaType === 'image') {
                $optimizedPath = optimizeImage($tmpName, $targetDir);
            } else {
                $newName = pathinfo($_FILES['images']['name'][$index], PATHINFO_FILENAME) . "_" . time() . "_" . $index . ".mp4";
                $optimizedPath = $targetDir . $newName; // DB path
                if (!move_uploaded_file($tmpName, $targetDirAbs . $newName)) $optimizedPath = false;
            }
            if (!$optimizedPath) continue;

            $imgStmt = $conn->prepare("INSERT INTO project_images (project_id, image_path, order_index, media_type) VALUES (?, ?, ?, ?)");
            $order_index = time() + $index;
            $imgStmt->bind_param("isis", $id, $optimizedPath, $order_index, $mediaType);
            $imgStmt->execute();
        }
    }

    header("Location: manage_products.php");
    exit;
}

// ----------------- Save Image Order -----------------
if (isset($_POST['save_order'])) {
    foreach ($_POST['order'] as $order_index => $img_id) {
        $stmt = $conn->prepare("UPDATE project_images SET order_index=? WHERE id=?");
        $stmt->bind_param("ii", $order_index, $img_id);
        $stmt->execute();
    }
    echo 'Order Saved';
    exit;
}

// ----------------- Fetch Products -----------------
$projects = $conn->query("SELECT * FROM products ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Projects | Admin Panel</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <!-- Make sure to include this for the toggle icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="img/logo-white.png" rel="icon" sizes="32x32" type="image/png">

    <style>
        body {
            background-color: #f8fafc;
            color: #1e293b;
        }

        .main-content {
            padding: 2.5rem 2.5rem 5rem 2.5rem;
            transition: all 0.3s ease;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .page-header h1 {
            font-size: 1.875rem;
            font-weight: 700;
            margin: 0;
            color: #0f172a;
        }

        .filter-section {
            background: white;
            padding: 1.25rem;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            align-items: center;
        }

        .search-group {
            position: relative;
            flex: 1;
            min-width: 280px;
        }

        .search-group i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
        }

        #adminSearchInput {
            padding: 0.625rem 1rem 0.625rem 2.5rem;
            border-radius: 0.5rem;
            border: 1px solid #e2e8f0;
            width: 100%;
            font-size: 0.875rem;
            transition: border-color 0.2s;
        }

        #adminSearchInput:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        #adminCategoryFilter {
            padding: 0.625rem 1rem;
            border-radius: 0.5rem;
            border: 1px solid #e2e8f0;
            min-width: 180px;
            font-size: 0.875rem;
            background-color: white;
            cursor: pointer;
        }

        .btn-add {
            background-color: #0f172a;
            color: white;
            border: none;
            padding: 0.625rem 1.25rem;
            border-radius: 0.5rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: background-color 0.2s;
        }

        .btn-add:hover {
            background-color: #1e293b;
        }

        /* Product List - Modern Table Styles */
        .product-list-container {
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .product-list-header {
            display: grid;
            grid-template-columns: 60px 2fr 150px 3fr 1fr 120px 140px;
            padding: 1rem 1.5rem;
            background: #f8fafc;
            border-bottom: 1px solid #f1f5f9;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
        }

        .product-card {
            display: grid;
            grid-template-columns: 60px 2fr 150px 3fr 1fr 120px 140px;
            padding: 1rem 1.5rem;
            align-items: center;
            border-bottom: 1px solid #f1f5f9;
            transition: background-color 0.2s;
        }

        .product-card:hover {
            background-color: #f8fafc;
        }

        .product-card .name {
            font-weight: 600;
            color: #0f172a;
        }

        .product-card .category-pill {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            background: #f1f5f9;
            color: #475569;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
            text-transform: capitalize;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-active {
            background: #dcfce7;
            color: #166534;
        }

        .status-inactive {
            background: #fee2e2;
            color: #991b1b;
        }

        .product-card .description {
            font-size: 0.875rem;
            color: #64748b;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .images-stack {
            display: flex;
            align-items: center;
        }

        .img-thumb-container {
            width: 32px;
            height: 32px;
            border-radius: 0.375rem;
            border: 2px solid white;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
            margin-right: -12px;
            overflow: hidden;
            background: #f1f5f9;
            position: relative;
        }

        .img-thumb-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }

        .img-more {
            width: 32px;
            height: 32px;
            border-radius: 0.375rem;
            background: #f1f5f9;
            color: #64748b;
            font-size: 0.75rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid white;
            margin-left: 4px;
            cursor: pointer;
        }

        .btn-icon {
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.5rem;
            transition: all 0.2s;
            border: none;
            font-size: 1.1rem;
        }

        .btn-edit-icon {
            color: #2563eb;
            background: #eff6ff;
        }

        .btn-edit-icon:hover {
            background: #dbeafe;
        }

        .btn-delete-icon {
            color: #dc2626;
            background: #fef2f2;
        }

        .btn-delete-icon:hover {
            background: #fee2e2;
        }

        .toggle-details {
            display: none;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(4px);
            justify-content: center;
            align-items: center;
            z-index: 10000;
        }

        .modal-content {
            background: #fff;
            padding: 2rem;
            border-radius: 1rem;
            width: 95%;
            max-width: 800px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .modal-content h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.25rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .form-group label {
            font-size: 0.875rem;
            font-weight: 600;
            color: #475569;
        }

        .form-group.full-width {
            grid-column: span 2;
        }

        .form-control-custom {
            padding: 0.625rem 0.875rem;
            border-radius: 0.5rem;
            border: 1px solid #e2e8f0;
            background-color: #fff;
            font-size: 0.875rem;
            transition: all 0.2s;
        }

        .form-control-custom:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #f1f5f9;
        }

        .btn-save {
            background-color: #3b82f6;
            color: white;
            padding: 0.625rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            border: none;
            transition: background-color 0.2s;
        }

        .btn-save:hover {
            background-color: #2563eb;
        }

        .btn-cancel {
            background-color: #f1f5f9;
            color: #475569;
            padding: 0.625rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            border: none;
            transition: background-color 0.2s;
        }

        .btn-cancel:hover {
            background-color: #e2e8f0;
        }

        #existing_images {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 0.75rem;
            margin-top: 0.5rem;
            padding: 1rem;
            background: #f8fafc;
            border-radius: 0.75rem;
            border: 1px dashed #e2e8f0;
        }

        .draggable-img {
            position: relative;
            aspect-ratio: 1;
            cursor: grab;
        }

        .draggable-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 0.5rem;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .delete-image {
            position: absolute;
            top: -5px;
            right: -5px;
            width: 20px;
            height: 20px;
            background: #ef4444;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            text-decoration: none;
            font-weight: bold;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 992px) {
            .main-content {
                padding: 5rem 1rem 2rem 1rem;
            }

            body.sidebar-open .main-content {
                margin-left: 240px;
            }
        }

        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: stretch;
                gap: 1rem;
            }

            .filter-section {
                flex-direction: column;
                align-items: stretch;
            }

            .search-group {
                min-width: auto;
            }

            #adminCategoryFilter {
                width: 100%;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-group.full-width {
                grid-column: auto;
            }

            .product-list-header {
                display: none;
            }

            .product-card {
                display: block;
                position: relative;
                margin-bottom: 1rem;
                padding: 1.25rem;
                border: 1px solid #f1f5f9;
                border-radius: 0.75rem;
                background: white;
                box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            }

            .product-card .id,
            .product-card .description,
            .product-card .category,
            .product-card .images,
            .product-card .status,
            .product-card .actions {
                display: none;
                margin-top: 1rem;
            }

            .product-card.open .id,
            .product-card.open .description,
            .product-card.open .category,
            .product-card.open .images,
            .product-card.open .status,
            .product-card.open .actions {
                display: block;
            }

            .product-card.open .images {
                display: flex;
            }

            .product-card .name {
                display: block;
                font-size: 1rem;
                margin-bottom: 0.25rem;
                padding-right: 20px;
            }

            /* Mobile Labels */
            .product-card .id:before,
            .product-card .category:before,
            .product-card .description:before,
            .product-card .status:before,
            .product-card .images:before {
                display: block;
                font-size: 0.7rem;
                text-transform: uppercase;
                font-weight: 700;
                color: #94a3b8;
                margin-bottom: 0.25rem;
            }

            .product-card .id:before {
                content: "Project ID";
            }

            .product-card .category:before {
                content: "Category";
            }

            .product-card .description:before {
                content: "Description";
            }

            .product-card .status:before {
                content: "Current Status";
            }

            .product-card .images:before {
                content: "Gallery Images";
            }

            .toggle-details {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                margin-top: 0.75rem;
                background: #f1f5f9;
                border: none;
                padding: 0.5rem 1rem;
                border-radius: 0.5rem;
                font-size: 0.875rem;
                font-weight: 500;
                color: #475569;
                cursor: pointer;
            }

            .product-card.open .toggle-details i {
                transform: rotate(180deg);
            }

            .product-card .actions {
                border-top: 1px solid #f1f5f9;
                padding-top: 1rem;
                display: flex;
                gap: 1rem;
            }

            .product-card .btn-icon {
                flex: 1;
                height: 44px;
            }
        }

        @media (min-width: 769px) {
            .product-card .toggle-details {
                display: none;
            }
        }
    </style>
</head>

<body>
    <?php include 'admin_sidebar.php'; ?>

    <div class="main-content">

        <div class="page-header">
            <h1>Manage Projects</h1>
            <button class="btn-add" onclick="openAddModal()"><i class="bi bi-plus-lg"></i> Add Project</button>
        </div>

        <div class="filter-section">
            <div class="search-group">
                <i class="bi bi-search"></i>
                <input type="text" id="adminSearchInput" placeholder="Search by project name or description...">
            </div>
            <select id="adminCategoryFilter">
                <option value="all">All Categories</option>
                <option value="kitchen">Kitchen</option>
                <option value="bedroom">Bedroom</option>
                <option value="pooja-mandir">Pooja Mandir</option>
                <option value="hall">Hall / Living Room</option>
                <option value="tv-units">TV Units</option>
                <option value="utility-area">Utility Area</option>
                <option value="balcony">Balcony</option>
                <option value="door-entrance">Door Entrance</option>
                <option value="corridor">Corridor</option>
                <option value="false-ceiling">False Ceiling</option>
                <option value="wardrobe">Wardrobe</option>
                <option value="wall-design">Wall Design</option>
                <option value="others">Others</option>
            </select>
        </div>

        <div class="product-list-container">
            <div class="product-list-header">
                <div class="id">ID</div>
                <div class="name">Project</div>
                <div class="category">Category</div>
                <div class="description">Description</div>
                <div class="images">Gallery</div>
                <div class="status">Status</div>
                <div class="actions">Actions</div>
            </div>
            <?php while ($row = $projects->fetch_assoc()):
                // fetch images for this project
                $stmt_img = $conn->prepare("SELECT id, image_path, media_type FROM project_images WHERE project_id=? ORDER BY order_index ASC");
                $stmt_img->bind_param("i", $row['id']);
                $stmt_img->execute();
                $res = $stmt_img->get_result();
                $images = [];
                while ($img = $res->fetch_assoc()) $images[] = $img;
                $stmt_img->close();
                $imgCount = count($images);
            ?>
                <div class="product-card" data-project-id="<?= $row['id']; ?>" data-images='<?= htmlspecialchars(json_encode($images), ENT_QUOTES); ?>'>
                    <div class="id">#<?= $row['id']; ?></div>
                    <div class="name"><?= htmlspecialchars($row['name']); ?></div>
                    <div class="category">
                        <span class="category-pill"><?= htmlspecialchars($row['category']); ?></span>
                    </div>
                    <div class="description">
                        <span><?= htmlspecialchars($row['description']); ?></span>
                    </div>
                    <div class="images">
                        <div class="images-stack">
                            <?php
                            $displayImgs = array_slice($images, 0, 3);
                            foreach ($displayImgs as $img): ?>
                                <div class="img-thumb-container skeleton">
                                    <?php if ($img['media_type'] === 'video'): ?>
                                        <video src="<?= $img['image_path'] ?>" style="width:100%; height:100%; object-fit:cover; opacity: 1;"></video>
                                    <?php else: ?>
                                        <img src="<?= $img['image_path'] ?>" loading="lazy" onload="this.style.opacity='1'; this.parentElement.classList.remove('skeleton')" onerror="this.parentElement.classList.remove('skeleton')">
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                            <button type="button" class="img-more images-count-btn" data-project-id="<?= $row['id']; ?>">
                                <?= $imgCount ?>
                            </button>
                        </div>
                    </div>
                    <div class="status">
                        <span class="status-pill status-<?= strtolower($row['status']) ?>">
                            <?= htmlspecialchars($row['status']); ?>
                        </span>
                    </div>
                    <div class="actions">
                        <button class="btn-icon btn-edit-icon" title="Edit Project" onclick="openEditModal(<?= $row['id']; ?>)">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <a href="?delete=<?= $row['id']; ?>" class="btn-icon btn-delete-icon" title="Delete Project" onclick="return confirm('Are you sure you want to delete this project? This action cannot be undone.')">
                            <i class="bi bi-trash3"></i>
                        </a>
                    </div>
                    <button class="toggle-details"><i class="bi bi-chevron-expand"></i> Details</button>
                </div>
            <?php endwhile; ?>

            <!-- Gallery Modal (reuse) -->
            <div class="modal" id="galleryModal">
                <div class="modal-content">
                    <button class="btn-cancel" id="galleryClose">Close</button>
                    <div id="galleryGrid" style="display:flex; flex-wrap:wrap; gap:8px; margin-top:10px;"></div>
                </div>
            </div>

            <script>
                // Images count button opens gallery modal populated from data-images
                function openGalleryFor(button) {
                    const card = button.closest('.product-card');
                    if (!card) return;
                    const images = JSON.parse(card.getAttribute('data-images') || '[]');
                    const grid = document.getElementById('galleryGrid');
                    grid.innerHTML = '';
                    images.forEach(img => {
                        const wrap = document.createElement('div');
                        wrap.style.position = 'relative';
                        wrap.style.width = '120px';
                        wrap.style.height = '90px';
                        
                        let mediaEl;
                        if (img.media_type === 'video') {
                            mediaEl = document.createElement('video');
                            mediaEl.src = img.image_path;
                        } else {
                            mediaEl = document.createElement('img');
                            mediaEl.src = img.image_path;
                        }
                        
                        mediaEl.style.width = '100%';
                        mediaEl.style.height = '100%';
                        mediaEl.style.objectFit = 'cover';
                        mediaEl.style.borderRadius = '6px';
                        const del = document.createElement('a');
                        del.href = 'manage_products.php?delete_image=' + img.id;
                        del.textContent = 'x';
                        del.className = 'delete-image';
                        // store project id on the delete link so the delegated handler can update product-card
                        del.dataset.projectId = card.dataset.projectId;
                        del.style.position = 'absolute';
                        del.style.top = '6px';
                        del.style.right = '6px';
                        del.style.background = 'rgba(220,38,38,0.85)';
                        del.style.color = '#fff';
                        del.style.padding = '2px 6px';
                        del.style.borderRadius = '4px';
                        del.style.textDecoration = 'none';
                        // keep default navigation prevented; deletion handled by delegated AJAX handler
                        del.onclick = function(evt) {
                            evt.preventDefault();
                        };
                        wrap.appendChild(mediaEl);
                        wrap.appendChild(del);
                        grid.appendChild(wrap);
                    });
                    document.getElementById('galleryModal').style.display = 'flex';
                }

                // attach handlers to images-count buttons
                document.addEventListener('click', function(e) {
                    if (e.target && e.target.classList.contains('images-count-btn')) {
                        openGalleryFor(e.target);
                    }
                });

                document.getElementById('galleryClose').addEventListener('click', function() {
                    document.getElementById('galleryModal').style.display = 'none';
                });
                // Close modal when clicking outside content
                document.getElementById('galleryModal').addEventListener('click', function(e) {
                    if (e.target === this) this.style.display = 'none';
                });
                // Close on ESC
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') document.getElementById('galleryModal').style.display = 'none';
                });
            </script>
        </div>
    </div>

    <!-- Add Project Modal -->
    <div class="modal" id="addModal">
        <div class="modal-content">
            <h2>Add New Project</h2>
            <form method="post" enctype="multipart/form-data">
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label>Project Name</label>
                        <input type="text" name="name" class="form-control-custom" placeholder="e.g. Luxury Apartment Hallway" required>
                    </div>
                    <div class="form-group">
                        <label>Client Name</label>
                        <input type="text" name="client_name" class="form-control-custom" placeholder="e.g. John Doe" required>
                    </div>
                    <div class="form-group">
                        <label>Project Category</label>
                        <select name="category" id="add_category" class="form-control-custom scrollable-select" required>
                            <option value="">Select category</option>
                            <option value="kitchen">Kitchen</option>
                            <option value="bedroom">Bedroom</option>
                            <option value="pooja-mandir">Pooja Mandir</option>
                            <option value="hall">Hall / Living Room</option>
                            <option value="tv-units">TV Units</option>
                            <option value="utility-area">Utility Area</option>
                            <option value="balcony">Balcony</option>
                            <option value="door-entrance">Door Entrance</option>
                            <option value="corridor">Corridor</option>
                            <option value="false-ceiling">False Ceiling</option>
                            <option value="wardrobe">Wardrobe</option>
                            <option value="wall-design">Wall Design</option>
                            <option value="others">Others</option>
                        </select>
                    </div>
                    <div class="form-group full-width">
                        <label>Site Address</label>
                        <textarea name="project_address" class="form-control-custom" placeholder="Enter full address of the site" rows="2" required></textarea>
                    </div>
                    <div class="form-group full-width">
                        <label>Description</label>
                        <textarea name="description" class="form-control-custom" placeholder="Tell more about the design and workflow" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Budget (₹)</label>
                        <input type="text" name="estimated_budget" class="form-control-custom" placeholder="Approximate Cost" required>
                    </div>
                    <div class="form-group">
                        <label>Current Phase</label>
                        <select name="project_phase" class="form-control-custom" required>
                            <option value="present" selected>Present (Ongoing)</option>
                            <option value="past">Past (Completed)</option>
                            <option value="future">Future (Upcoming)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Start Date</label>
                        <input type="date" name="start_date" class="form-control-custom" required>
                    </div>
                    <div class="form-group">
                        <label>Estimated End Date</label>
                        <input type="date" name="end_date" class="form-control-custom" required>
                    </div>
                    <div class="form-group">
                        <label>Upload Gallery</label>
                        <input type="file" name="images[]" class="form-control-custom" accept="image/*,video/mp4" multiple>
                    </div>
                    <div class="form-group">
                        <label>Visibility Status</label>
                        <select name="status" class="form-control-custom" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
                    <button type="submit" name="add_product" class="btn-save">Create Project</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Project Modal -->
    <div class="modal" id="editModal">
        <div class="modal-content">
            <h2>Edit Project Details</h2>
            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" id="edit_id">
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label>Project Name</label>
                        <input type="text" name="name" id="edit_name" class="form-control-custom" required>
                    </div>
                    <div class="form-group">
                        <label>Client Name</label>
                        <input type="text" name="client_name" id="edit_client_name" class="form-control-custom" required>
                    </div>
                    <div class="form-group">
                        <label>Category</label>
                        <select name="category" id="edit_category" class="form-control-custom scrollable-select" required></select>
                    </div>
                    <div class="form-group full-width">
                        <label>Project Address</label>
                        <textarea name="project_address" id="edit_project_address" class="form-control-custom" rows="2" required></textarea>
                    </div>
                    <div class="form-group full-width">
                        <label>Description</label>
                        <textarea name="description" id="edit_description" class="form-control-custom" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Budget (₹)</label>
                        <input type="text" name="estimated_budget" id="edit_estimated_budget" class="form-control-custom" required>
                    </div>
                    <div class="form-group">
                        <label>Phase</label>
                        <select name="project_phase" id="edit_project_phase" class="form-control-custom" required>
                            <option value="present">Present (Ongoing)</option>
                            <option value="past">Past (Completed)</option>
                            <option value="future">Future (Upcoming)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Start Date</label>
                        <input type="date" name="start_date" id="edit_start_date" class="form-control-custom" required>
                    </div>
                    <div class="form-group">
                        <label>End Date</label>
                        <input type="date" name="end_date" id="edit_end_date" class="form-control-custom" required>
                    </div>
                    <div class="form-group full-width">
                        <div style="display:flex; justify-content:space-between; align-items:center;">
                            <label>Manage Gallery <small class="text-muted">(Drag to reorder)</small></label>
                            <button type="button" class="btn-icon" style="font-size:0.7rem; width:auto; height:auto; padding:4px 8px; background:#eff6ff; color:#2563eb; border: 1px solid #dbeafe;" onclick="saveOrder()"><i class="bi bi-arrow-repeat"></i> Update Order</button>
                        </div>
                        <div id="existing_images"></div>
                    </div>
                    <div class="form-group">
                        <label>Add More Media (Images/Video)</label>
                        <input type="file" name="images[]" class="form-control-custom" accept="image/*,video/mp4" multiple>
                    </div>
                    <div class="form-group">
                        <label>Visibility Status</label>
                        <select name="status" id="edit_status" class="form-control-custom" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
                    <button type="submit" name="edit_product" class="btn-save">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAddModal() {
            document.getElementById('addModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('addModal').style.display = 'none';
            document.getElementById('editModal').style.display = 'none';
        }

        function openEditModal(id) {
            document.getElementById('editModal').style.display = 'flex';
            fetch('get_project.php?id=' + id)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('edit_id').value = data.id;
                    document.getElementById('edit_name').value = data.name;
                    document.getElementById('edit_description').value = data.description;
                    document.getElementById('edit_category').value = data.category;
                    document.getElementById('edit_client_name').value = data.client_name;
                    document.getElementById('edit_project_address').value = data.project_address;
                    document.getElementById('edit_estimated_budget').value = data.estimated_budget;
                    document.getElementById('edit_start_date').value = data.start_date;
                    document.getElementById('edit_end_date').value = data.end_date;
                    document.getElementById('edit_project_phase').value = data.project_phase;
                    document.getElementById('edit_status').value = data.status;

                    let imgContainer = document.getElementById('existing_images');
                    imgContainer.innerHTML = '';
                    data.images.forEach(img => {
                        let div = document.createElement('div');
                        div.className = 'draggable-img';
                        div.draggable = true;
                        div.dataset.id = img.id;
                        
                        let mediaHtml = '';
                        if (img.media_type === 'video') {
                            mediaHtml = `<video src="${img.image_path}" style="width:100%; height:100%; object-fit:cover; border-radius:0.5rem;"></video>`;
                        } else {
                            mediaHtml = `<img src="${img.image_path}">`;
                        }
                        
                        div.innerHTML = `${mediaHtml}<a href="manage_products.php?delete_image=${img.id}" class="delete-image" data-img-id="${img.id}" data-project-id="${data.id}">x</a>`;
                        imgContainer.appendChild(div);
                    });
                    makeImagesDraggable();
                });
        }

        // Populate edit_category select with room categories (lowercase values)
        document.addEventListener('DOMContentLoaded', function() {
            const categories = [
                'kitchen',
                'bedroom',
                'pooja-mandir',
                'hall',
                'tv-units',
                'utility-area',
                'balcony',
                'door-entrance',
                'corridor',
                'false-ceiling',
                'wardrobe',
                'wall-design',
                'others'
            ];
            const editSelect = document.getElementById('edit_category');
            const addSelect = document.getElementById('add_category');
            if (editSelect) {
                editSelect.innerHTML = '<option value="">Select category</option>' + categories.map(c => `<option value="${c}">${c.charAt(0).toUpperCase()+c.slice(1)}</option>`).join('');
                editSelect.style.display = '';
            }
            if (addSelect) {
                // ensure addSelect uses same categories (in case patch missed it)
                addSelect.innerHTML = '<option value="">Select category</option>' + categories.map(c => `<option value="${c}">${c.charAt(0).toUpperCase()+c.slice(1)}</option>`).join('');
            }
        });

        function makeImagesDraggable() {
            const container = document.getElementById('existing_images');
            let dragged;
            container.querySelectorAll('.draggable-img').forEach(item => {
                item.addEventListener('dragstart', e => {
                    dragged = e.target;
                    e.target.style.opacity = 0.5;
                });
                item.addEventListener('dragend', e => {
                    e.target.style.opacity = '';
                });
            });
            container.addEventListener('dragover', e => e.preventDefault());
            container.addEventListener('drop', e => {
                e.preventDefault();
                if (e.target.closest('.draggable-img') && e.target.closest('.draggable-img') !== dragged) {
                    container.insertBefore(dragged, e.target.closest('.draggable-img').nextSibling);
                }
            });
        }

        function saveOrder() {
            const order = [];
            document.querySelectorAll('#existing_images .draggable-img').forEach((div, i) => {
                order.push(div.dataset.id);
            });
            fetch('manage_products.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'save_order=1&' + new URLSearchParams({
                    order: order
                })
            }).then(res => res.text()).then(txt => alert(txt));
        }
    </script>

    <script>
        // Add event listener for the toggle buttons
        document.querySelectorAll('.toggle-details').forEach(button => {
            button.addEventListener('click', function() {
                this.parentElement.classList.toggle('open');
            });
        });
    </script>

    <script>
        // Delegate click handler for any delete-image links (links with "delete_image=" in href)
        // Delegated handler for delete-image links. Uses AJAX to delete and updates UI without reloading.
        document.addEventListener('click', function(e) {
            const a = e.target.closest('a.delete-image');
            if (!a) return;
            e.preventDefault();

            if (!confirm('Delete this image?')) return;

            // Visual feedback: Start loading
            const wrapper = a.closest('.draggable-img') || a.closest('div');
            if (wrapper) wrapper.style.opacity = '0.3';

            const href = a.getAttribute('href');
            // send AJAX GET to delete endpoint
            fetch(href, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(resp => resp.json().catch(() => ({
                    success: false
                })))
                .then(data => {
                    if (data && data.success) {
                        const deletedId = String(data.deleted_id || a.dataset.imgId);
                        const projectId = String(data.project_id || a.dataset.projectId || '');

                        // remove element from modal (draggable-img wrapper) or gallery
                        if (wrapper) wrapper.remove();

                        // remove any matching image card in galleryGrid
                        const galleryGrid = document.getElementById('galleryGrid');
                        if (galleryGrid) {
                            galleryGrid.querySelectorAll('div').forEach(card => {
                                const delLink = card.querySelector('a');
                                if (delLink && delLink.href && delLink.href.indexOf('delete_image=' + deletedId) !== -1) {
                                    card.remove();
                                }
                            });
                        }

                        // update the product-card's data-images attribute and images-count-btn
                        if (projectId) {
                            const productCard = document.querySelector(`.product-card[data-project-id="${projectId}"]`);
                            if (productCard) {
                                try {
                                    let images = JSON.parse(productCard.getAttribute('data-images') || '[]');
                                    images = images.filter(img => String(img.id) !== deletedId);
                                    productCard.setAttribute('data-images', JSON.stringify(images));
                                    const imgCountBtn = productCard.querySelector('.images-count-btn');
                                    if (imgCountBtn) imgCountBtn.textContent = images.length + ' image' + (images.length == 1 ? '' : 's');
                                } catch (err) {
                                    // ignore
                                }
                            }
                        }

                        // keep edit modal open; no reload
                    } else {
                        alert('Unable to delete image. Try again.');
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Request failed. Check console.');
                });
        });
    </script>

    <script>
        // Admin project filter functionality (Search + Category)
        function applyAdminFilters() {
            const searchTerm = document.getElementById('adminSearchInput').value.toLowerCase().trim();
            const categoryFilter = document.getElementById('adminCategoryFilter').value.toLowerCase();
            const cards = document.querySelectorAll('.product-card');

            cards.forEach(card => {
                const projectName = card.querySelector('.name').textContent.toLowerCase();
                const projectDesc = card.querySelector('.description').textContent.toLowerCase();
                const projectCategory = card.querySelector('.category').textContent.toLowerCase().trim();

                const matchesSearch = projectName.includes(searchTerm) || projectDesc.includes(searchTerm);
                const matchesCategory = categoryFilter === 'all' || projectCategory === categoryFilter;

                if (matchesSearch && matchesCategory) {
                    card.style.display = ''; // Reverts to CSS default (grid or block)
                } else {
                    card.style.display = 'none';
                }
            });
        }

        document.getElementById('adminSearchInput').addEventListener('input', applyAdminFilters);
        document.getElementById('adminCategoryFilter').addEventListener('change', applyAdminFilters);
    </script>

</body>

</html>