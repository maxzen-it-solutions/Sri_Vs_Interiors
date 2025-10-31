<?php
session_start();
include 'db_connect.php';  // Your DB connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Basic validation
    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $_SESSION['error'] = "Please fill in all required fields.";
        header('Location: register.php');
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format.";
        header('Location: register.php');
        exit;
    }

    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match.";
        header('Location: register.php');
        exit;
    }

    // Hash the password
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Default role = user (admin role should be assigned manually or via admin interface)
    $role = 'user';

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $_SESSION['error'] = "Email already registered.";
        header('Location: register.php');
        exit;
    }
    $stmt->close();

    // Insert user
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $passwordHash, $role);
    if ($stmt->execute()) {
        $_SESSION['success'] = "Registration successful. Please login.";
        header('Location: login.php');
    } else {
        $_SESSION['error'] = "Error during registration.";
        header('Location: register.php');
    }
    $stmt->close();
    $conn->close();
} else {
    header('Location: register.php');
    exit;
}
?>
