<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['reviewer_name'] ?? '');
    $rating = intval($_POST['rating'] ?? 0);
    $review = trim($_POST['review'] ?? '');

    // Validation
    if (empty($name) || $rating < 1 || $rating > 5 || empty($review)) {
        header("Location: index.php?feedback=error");
        exit;
    }

    // Prepare and execute insert statement
    $stmt = $conn->prepare("
        INSERT INTO project_reviews
        (client_name, rating, review, created_at)
        VALUES (?, ?, ?, NOW())
    ");

    if (!$stmt) {
        header("Location: index.php?feedback=db_error");
        exit;
    }

    $stmt->bind_param("sis", $name, $rating, $review);

    if ($stmt->execute()) {
        $stmt->close();
        header("Location: index.php?feedback=success#how-we-work");
        exit;
    } else {
        $stmt->close();
        header("Location: index.php?feedback=error");
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
?>
