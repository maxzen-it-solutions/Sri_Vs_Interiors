<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $category = trim($_POST['category'] ?? '');
    $name = trim($_POST['reviewer_name'] ?? '');
    $rating = intval($_POST['rating'] ?? 0);
    $review = trim($_POST['review'] ?? '');

    // Validation
    if (empty($category) || empty($name) || $rating < 1 || $rating > 5 || empty($review)) {
        header("Location: services.php?error=invalid");
        exit;
    }

    // Prepare and execute insert statement
    $stmt = $conn->prepare("
        INSERT INTO service_reviews
        (category, reviewer_name, rating, review, created_at)
        VALUES (?, ?, ?, ?, NOW())
    ");

    if (!$stmt) {
        header("Location: services.php?error=db");
        exit;
    }

    $stmt->bind_param("ssis", $category, $name, $rating, $review);

    if ($stmt->execute()) {
        $stmt->close();
        header("Location: services.php?success=1");
        exit;
    } else {
        $stmt->close();
        header("Location: services.php?error=execute");
        exit;
    }
} else {
    header("Location: services.php");
    exit;
}
?>
