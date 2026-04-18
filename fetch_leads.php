<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') exit;

include 'db_connect.php';

// Handle deletion
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM leads WHERE id = $id");
}

// Fetch leads
$result = $conn->query("SELECT * FROM leads ORDER BY created_at DESC");

if ($result && $result->num_rows > 0) {
    echo '<table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Message</th>
                <th>Submitted At</th>
                <th>Action</th>
            </tr>';

    while ($row = $result->fetch_assoc()) {
        echo '<tr>
                <td>' . $row['id'] . '</td>
                <td>' . htmlspecialchars($row['name']) . '</td>
                <td>' . htmlspecialchars($row['email']) . '</td>
                <td>' . nl2br(htmlspecialchars($row['message'])) . '</td>
                <td>' . $row['created_at'] . '</td>
                <td>
                    <a href="fetch_leads.php?delete=' . $row['id'] . '" onclick="return confirm(\'Are you sure?\');" style="color:white; background:#d9534f; padding:5px 10px; border-radius:4px; text-decoration:none;">Delete</a>
                </td>
              </tr>';
    }
    echo '</table>';
} else {
    echo '<p>No leads found.</p>';
}
