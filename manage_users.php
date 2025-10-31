<?php
// Start session safely
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include 'db_connect.php';

// Redirect if admin not logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Handle Add User
if (isset($_POST['add_user'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $password, $role);
    $stmt->execute();
    header("Location: manage_users.php");
    exit;
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: manage_users.php");
    exit;
}

// Handle Edit
if (isset($_POST['edit_user'])) {
    $id = intval($_POST['id']);
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $role = $_POST['role'];

    $stmt = $conn->prepare("UPDATE users SET name=?, email=?, role=? WHERE id=?");
    $stmt->bind_param("sssi", $name, $email, $role, $id);
    $stmt->execute();
    header("Location: manage_users.php");
    exit;
}

// Fetch Users
$users = $conn->query("SELECT * FROM users ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Users | Admin Panel</title>
<!-- Using a more recent bootstrap version for consistency -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"> 
<link href="img/logo-white.png" rel="icon" sizes="32x32" type="image/png">

<style>
.main-content {
    margin-left: 250px;
    transition: margin-left 0.3s;
    padding: 40px;
}

h1 {
    font-size: 2rem;
    font-weight: 600;
    margin-bottom: 20px;
}

.btn-add {
    background-color: #2563eb;
    color: #fff;
    border: none;
    padding: 10px 15px;
    border-radius: 6px;
    cursor: pointer;
    margin-bottom: 20px;
}

.user-table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
}

.user-table th, .user-table td {
    padding: 12px 15px;
    border-bottom: 1px solid #e5e7eb;
    text-align: left;
}

.user-table th {
    background: #f9fafb;
}

.btn-edit, .btn-delete {
    padding: 6px 12px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 0.9rem;
}

.btn-edit {
    background-color: #2563eb;
    color: white;
}

.btn-delete {
    background-color: #dc2626;
    color: white;
    text-decoration: none;
}

/* Modal styling */
.modal {
    display: none;
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.6);
    justify-content: center;
    align-items: center;
    z-index: 10;
}

.modal-content {
    background: #fff;
    padding: 25px;
    border-radius: 10px;
    width: 100%;
    max-width: 400px;
}

.modal-content input, .modal-content select {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border-radius: 5px;
    border: 1px solid #d1d5db;
}

.btn-save {
    background-color: #2563eb;
    color: white;
    border: none;
    padding: 10px 15px;
    border-radius: 5px;
    cursor: pointer;
}

.btn-cancel {
    background-color: #6b7280;
    color: white;
    border: none;
    padding: 10px 15px;
    border-radius: 5px;
    margin-left: 10px;
    cursor: pointer;
}

@media (max-width: 768px) {
    .main-content {
        margin-left: 0;
        padding: 20px;
    }
    body.sidebar-open .main-content {
        margin-left: 240px;
    }
}
</style>
</head>
<body>
<?php include 'admin_sidebar.php'; ?>

<div class="main-content">
    <h1>Manage Users</h1>
    <button class="btn-add" onclick="openAddModal()">+ Add User</button>

    <table class="user-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $users->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id']; ?></td>
                    <td><?= htmlspecialchars($row['name']); ?></td>
                    <td><?= htmlspecialchars($row['email']); ?></td>
                    <td><?= htmlspecialchars($row['role']); ?></td>
                    <td>
                        <button class="btn-edit" onclick="openEditModal(<?= $row['id']; ?>, '<?= htmlspecialchars($row['name'], ENT_QUOTES); ?>', '<?= htmlspecialchars($row['email'], ENT_QUOTES); ?>', '<?= htmlspecialchars($row['role']); ?>')">Edit</button>
                        <a href="?delete=<?= $row['id']; ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Add User Modal -->
<div class="modal" id="addModal">
    <div class="modal-content">
        <h2>Add User</h2>
        <form method="post">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <select name="role" required>
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
            <button type="submit" name="add_user" class="btn-save">Save</button>
            <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
        </form>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal" id="editModal">
    <div class="modal-content">
        <h2>Edit User</h2>
        <form method="post">
            <input type="hidden" name="id" id="edit_id">
            <input type="text" name="name" id="edit_name" required>
            <input type="email" name="email" id="edit_email" required>
            <select name="role" id="edit_role" required>
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
            <button type="submit" name="edit_user" class="btn-save">Update</button>
            <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
        </form>
    </div>
</div>

<script>
function openAddModal() {
    document.getElementById('addModal').style.display = 'flex';
}
function openEditModal(id, name, email, role) {
    document.getElementById('editModal').style.display = 'flex';
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_email').value = email;
    document.getElementById('edit_role').value = role;
}
function closeModal() {
    document.getElementById('addModal').style.display = 'none';
    document.getElementById('editModal').style.display = 'none';
}
</script>
</body>
</html>
