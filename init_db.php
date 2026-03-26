<?php
include 'db_connect.php';

// Initialize site_stats table with default values if empty
$check = $conn->query("SELECT COUNT(*) as count FROM site_stats");
if ($check && $check->fetch_assoc()['count'] == 0) {
    $conn->query("INSERT INTO site_stats (total_projects, completed_projects, ongoing_projects) VALUES (650, 600, 15)");
    echo "Default statistics inserted successfully!<br>";
}

// Initialize partners table with existing logos if empty
$check_partners = $conn->query("SELECT COUNT(*) as count FROM partners");
if ($check_partners && $check_partners->fetch_assoc()['count'] == 0) {
    // You can add existing partner logos here if needed
    echo "Partners table is empty. Upload logos via admin panel.<br>";
}

echo "Database initialization complete!";
?>