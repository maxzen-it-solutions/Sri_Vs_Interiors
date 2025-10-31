<?php
if (session_status() == PHP_SESSION_NONE) session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// ----------------- Handle Add Project -----------------
if (isset($_POST['add_product'])) {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $client_name = trim($_POST['client_name']);
    $address = trim($_POST['address']);
    $category = $_POST['category'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("INSERT INTO products (name, description, client_name, address, category, status) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $description, $client_name, $address, $category, $status);
    $stmt->execute();

    $project_id = $stmt->insert_id;

    // Upload multiple images
    if (!empty($_FILES['images']['name'][0])) {
        $targetDir = "uploads/";
        if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);

        foreach ($_FILES['images']['tmp_name'] as $index => $tmpName) {
            $fileName = $targetDir . basename($_FILES['images']['name'][$index]);
            move_uploaded_file($tmpName, $fileName);
            $imgStmt = $conn->prepare("INSERT INTO project_images (project_id, image_path, order_index) VALUES (?, ?, ?)");
            $order_index = $index;
            $imgStmt->bind_param("isi", $project_id, $fileName, $order_index);
            $imgStmt->execute();
        }
    }
    header("Location: manage_products.php");
    exit;
}

// ----------------- Handle Delete Project -----------------
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $res = $conn->query("SELECT image_path FROM project_images WHERE project_id=$id");
    while ($row = $res->fetch_assoc()) {
        if (file_exists($row['image_path'])) unlink($row['image_path']);
    }
    $conn->query("DELETE FROM products WHERE id=$id");
    header("Location: manage_products.php");
    exit;
}

// ----------------- Handle Delete Individual Image -----------------
if (isset($_GET['delete_image'])) {
    $img_id = intval($_GET['delete_image']);
    $res = $conn->query("SELECT image_path FROM project_images WHERE id=$img_id");
    if ($row = $res->fetch_assoc()) {
        if (file_exists($row['image_path'])) unlink($row['image_path']);
    }
    $conn->query("DELETE FROM project_images WHERE id=$img_id");
    header("Location: manage_products.php");
    exit;
}

// ----------------- Handle Edit Project -----------------
if (isset($_POST['edit_product'])) {
    $id = intval($_POST['id']);
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $client_name = trim($_POST['client_name']);
    $address = trim($_POST['address']);
    $category = $_POST['category'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE products SET name=?, description=?, client_name=?, address=?, category=?, status=? WHERE id=?");
    $stmt->bind_param("ssssssi", $name, $description, $client_name, $address, $category, $status, $id);
    $stmt->execute();

    if (!empty($_FILES['images']['name'][0])) {
        $targetDir = "uploads/";
        if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);
        foreach ($_FILES['images']['tmp_name'] as $index => $tmpName) {
            $fileName = $targetDir . basename($_FILES['images']['name'][$index]);
            move_uploaded_file($tmpName, $fileName);
            $imgStmt = $conn->prepare("INSERT INTO project_images (project_id, image_path, order_index) VALUES (?, ?, ?)");
            $order_index = time() + $index;
            $imgStmt->bind_param("isi", $id, $fileName, $order_index);
            $imgStmt->execute();
        }
    }
    header("Location: manage_products.php");
    exit;
}

// ----------------- Handle Save Image Order -----------------
if (isset($_POST['save_order'])) {
    foreach ($_POST['order'] as $order_index => $img_id) {
        $stmt = $conn->prepare("UPDATE project_images SET order_index=? WHERE id=?");
        $stmt->bind_param("ii", $order_index, $img_id);
        $stmt->execute();
    }
    echo 'Order Saved';
    exit;
}

// Fetch Projects
$projects = $conn->query("SELECT * FROM products ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Projects | Admin Panel</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<!-- Make sure to include this for the toggle icon -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<link href="img/logo-white.png" rel="icon" sizes="32x32" type="image/png">

<style>
.main-content { margin-left: 250px; padding: 40px; transition: margin-left 0.3s; background-color: #f4f6f8; }
h1 { font-size: 2rem; font-weight: 600; margin-bottom: 20px; }
.action-buttons { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 20px; }
.btn-add { background-color: #2563eb; color: #fff; border: none; padding: 10px 15px; border-radius: 6px; cursor: pointer; }

/* Product List - Desktop (Table-like) */
.product-list-header, .product-card { display: grid; grid-template-columns: 50px 1fr 120px 250px 1fr 100px 150px; align-items: center; gap: 15px; padding: 12px 15px; border-bottom: 1px solid #e5e7eb; }
.product-list-header { font-weight: 600; background: #f9fafb; border-top-left-radius: 10px; border-top-right-radius: 10px; }
.product-card { background: #fff; }
.product-card:last-child { border-bottom: none; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px; }
.product-card .description { white-space: pre-wrap; word-break: break-word; }
.product-card .images { display: flex; flex-wrap: wrap; gap: 4px; }
.product-card .actions { display: flex; gap: 8px; }
.toggle-details { display: none; } /* Hide toggle on desktop */

/* General Styles */
.btn-edit, .btn-delete { padding: 6px 12px; border: none; border-radius: 5px; cursor: pointer; font-size: 0.9rem; }
.btn-edit { background-color: #2563eb; color: white; }
.btn-delete { background-color: #dc2626; color: white; text-decoration: none; }
.project-img { width: 60px; margin:2px; cursor:pointer; border-radius:5px; border:1px solid #d1d5db; }

/* Modal Styles */
.modal { display: none; position: fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.6); justify-content: center; align-items: center; z-index:10; }
.modal-content { background:#fff; padding:25px; border-radius:10px; width: 95%; max-width:550px; max-height: 90vh; overflow-y: auto; }
.modal-content input, .modal-content select, .modal-content textarea { width:100%; padding:10px; margin-bottom:15px; border-radius:5px; border:1px solid #d1d5db; }
.btn-save { background-color:#2563eb; color:white; border:none; padding:10px 15px; border-radius:5px; cursor:pointer; }
.btn-cancel { background-color:#6b7280; color:white; border:none; padding:10px 15px; border-radius:5px; margin-left:10px; cursor:pointer; }
#existing_images { display: flex; flex-wrap: wrap; gap: 5px; margin-bottom: 10px; }
.draggable-img { position: relative; width:60px; height:60px; cursor: move; border-radius:5px; overflow:hidden; }
.draggable-img img { width:100%; height:100%; object-fit:cover; }
.draggable-img a { position:absolute; top:2px; right:2px; background:rgba(220,38,38,0.8); color:#fff; padding:0 5px; border-radius:4px; font-size:12px; font-weight:bold; text-decoration:none; z-index:2; }
.draggable-img:hover { transform:scale(1.05); box-shadow:0 2px 6px rgba(0,0,0,0.2); }

/* Responsive - Mobile (Toggle/Card view) */
@media (max-width: 768px) {
    .main-content { margin-left:0; padding:20px; }
    .action-buttons { flex-direction: column; }
    .btn-add { width: 100%; margin-right: 0; }

    .product-list-header { display: none; } /* Hide table header on mobile */
    .product-card {
        display: block; /* Change from grid to block */
        padding: 15px;
        margin-bottom: 10px;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    .product-card > div { padding: 5px 0; }
    .product-card .id, .product-card .category, .product-card .description, .product-card .images, .product-card .actions { display: none; } /* Hide details by default */
    .product-card.open .id, .product-card.open .category, .product-card.open .description, .product-card.open .images, .product-card.open .actions { display: block; } /* Show on toggle */
    .product-card.open .images { display: flex; }

    .product-card .name { font-size: 1.1rem; font-weight: 600; }
    .product-card .status { font-size: 0.9rem; color: #6b7280; }

    /* Show truncated description on mobile by default */
    .product-card .short-desc { display: inline; }
    .product-card .full-desc { display: none; }

    .images-count-btn { background: transparent; border: none; color: #374151; margin-left:8px; }

    /* Add labels for context */
    .product-card .id:before, .product-card .category:before, .product-card .description:before, .product-card .status:before {
        font-weight: 600;
        display: block;
        margin-bottom: 4px;
        color: #374151;
    }
    .product-card .id:before { content: "ID:"; }
    .product-card .category:before { content: "Category:"; }
    .product-card .description:before { content: "Description:"; }
    .product-card .status:before { content: "Status:"; }

    .toggle-details { display: inline-block; margin-top: 10px; background-color: #e5e7eb; color: #374151; border: none; padding: 8px 12px; border-radius: 5px; cursor: pointer; }
    
    /* When sidebar is open on mobile, push content */
    body.sidebar-open .main-content { margin-left: 240px; }
    body.sidebar-open .sidebar-toggle { left: 255px; }
}

@media (min-width: 769px) {
    .product-card .short-desc { display: none; }
    .product-card .full-desc { display: inline; }
    .images-count-btn { display: none; }
}
</style>
</head>
<body>
<?php include 'admin_sidebar.php'; ?>

<div class="main-content">
<h1>Manage Projects</h1>

<!-- Category Buttons -->
<div class="action-buttons">
    <button class="btn-add" onclick="openAddModal('Past')">+ Add Past Project</button>
    <button class="btn-add" onclick="openAddModal('Present')">+ Add Present Project</button>
    <button class="btn-add" onclick="openAddModal('Future')">+ Add Future Project</button>
</div>

<div class="product-list">
    <div class="product-list-header">
        <div class="id">ID</div>
        <div class="name">Name</div>
        <div class="category">Category</div>
        <div class="description">Description</div>
        <div class="images">Images</div>
        <div class="status">Status</div>
        <div class="actions">Actions</div>
    </div>
    <?php while ($row = $projects->fetch_assoc()):
        // fetch images for this project
        $res = $conn->query("SELECT id, image_path FROM project_images WHERE project_id=".$row['id']." ORDER BY order_index ASC");
        $images = [];
        while ($img = $res->fetch_assoc()) $images[] = $img;
        $imgCount = count($images);
        $short = implode(' ', array_slice(preg_split('/\s+/', trim($row['description'])), 0, 2));
        if (str_word_count($row['description']) > 2) $short .= '...';
    ?>
    <div class="product-card" data-project-id="<?= $row['id']; ?>" data-images='<?= htmlspecialchars(json_encode($images), ENT_QUOTES); ?>'>
        <div class="id"><?= $row['id']; ?></div>
        <div class="name"><?= htmlspecialchars($row['name']); ?></div>
        <div class="category"><?= htmlspecialchars($row['category']); ?></div>
        <div class="description">
            <span class="full-desc"><?= htmlspecialchars($row['description']); ?></span>
            <span class="short-desc"><?= htmlspecialchars($short); ?></span>
        </div>
        <div class="images">
            <?php foreach ($images as $img) { echo '<img src="'.$img['image_path'].'" class="project-img" data-img-id="'.$img['id'].'">'; } ?>
            <button type="button" class="images-count-btn" data-project-id="<?= $row['id']; ?>"><?= $imgCount; ?> image<?= $imgCount==1?'':'s' ?></button>
        </div>
        <div class="status"><?= htmlspecialchars($row['status']); ?></div>
        <div class="actions">
            <button class="btn-edit" onclick="openEditModal(<?= $row['id']; ?>)">Edit</button>
            <a href="?delete=<?= $row['id']; ?>" class="btn-delete" onclick="return confirm('Delete this project?')">Delete</a>
        </div>
        <button class="toggle-details">Details</button>
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
    // Show short-desc on mobile, full-desc on desktop. Clicking short-desc expands the card.
    document.addEventListener('click', function(e){
        if (e.target && e.target.classList.contains('short-desc')) {
            const card = e.target.closest('.product-card');
            if (card) card.classList.toggle('open');
        }
    });

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
            const i = document.createElement('img');
            i.src = img.image_path;
            i.style.width = '100%'; i.style.height = '100%'; i.style.objectFit = 'cover'; i.style.borderRadius = '6px';
            const del = document.createElement('a');
            del.href = 'manage_products.php?delete_image=' + img.id;
            del.textContent = 'x';
            del.style.position = 'absolute'; del.style.top='6px'; del.style.right='6px'; del.style.background='rgba(220,38,38,0.85)'; del.style.color='#fff'; del.style.padding='2px 6px'; del.style.borderRadius='4px'; del.style.textDecoration='none';
            del.onclick = function(evt){ if(!confirm('Delete this image?')) { evt.preventDefault(); } };
            wrap.appendChild(i); wrap.appendChild(del); grid.appendChild(wrap);
        });
        document.getElementById('galleryModal').style.display = 'flex';
    }

    // attach handlers to images-count buttons
    document.addEventListener('click', function(e){
        if (e.target && e.target.classList.contains('images-count-btn')) {
            openGalleryFor(e.target);
        }
    });

    document.getElementById('galleryClose').addEventListener('click', function(){ document.getElementById('galleryModal').style.display = 'none'; });
    // Close modal when clicking outside content
    document.getElementById('galleryModal').addEventListener('click', function(e){ if (e.target === this) this.style.display = 'none'; });
    // Close on ESC
    document.addEventListener('keydown', function(e){ if (e.key === 'Escape') document.getElementById('galleryModal').style.display = 'none'; });
    </script>
</div>
</div>

<!-- Add Project Modal -->
<div class="modal" id="addModal">
<div class="modal-content">
<h2>Add Project</h2>
<form method="post" enctype="multipart/form-data">
<input type="text" name="name" placeholder="Project Name" required>
<input type="text" name="client_name" placeholder="Client Name" required>
<input type="text" name="address" placeholder="Address" required>
<textarea name="description" placeholder="Project Description" required></textarea>
<select name="category" id="add_category" required>
<option value="Past">Past</option>
<option value="Present">Present</option>
<option value="Future">Future</option>
</select>
<input type="file" name="images[]" accept="image/*" multiple>
<select name="status" required>
<option value="active">Active</option>
<option value="inactive">Inactive</option>
</select>
<button type="submit" name="add_product" class="btn-save">Save</button>
<button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
</form>
</div>
</div>

<!-- Edit Project Modal -->
<div class="modal" id="editModal">
<div class="modal-content">
<h2>Edit Project</h2>
<form method="post" enctype="multipart/form-data">
<input type="hidden" name="id" id="edit_id">
<input type="text" name="name" id="edit_name" placeholder="Project Name" required>
<input type="text" name="client_name" id="edit_client_name" placeholder="Client Name" required>
<input type="text" name="address" id="edit_address" placeholder="Address" required>
<textarea name="description" id="edit_description" placeholder="Project Description" required></textarea>
<select name="category" id="edit_category" required>
<option value="Past">Past</option>
<option value="Present">Present</option>
<option value="Future">Future</option>
</select>
<div id="existing_images"></div>
<input type="file" name="images[]" accept="image/*" multiple>
<select name="status" id="edit_status" required>
<option value="active">Active</option>
<option value="inactive">Inactive</option>
</select>
<button type="submit" name="edit_product" class="btn-save">Update</button>
<button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
</form> 
</div>
</div>

<script>
function openAddModal(category) {
    document.getElementById('addModal').style.display = 'flex';
    document.getElementById('add_category').value = category;
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
        document.getElementById('edit_client_name').value = data.client_name;
        document.getElementById('edit_address').value = data.address;
        document.getElementById('edit_description').value = data.description;
        document.getElementById('edit_category').value = data.category;
        document.getElementById('edit_status').value = data.status;

        let imgContainer = document.getElementById('existing_images');
        imgContainer.innerHTML = '';
        data.images.forEach(img => {
            let div = document.createElement('div');
            div.className = 'draggable-img';
            div.draggable = true;
            div.dataset.id = img.id;
            div.innerHTML = `<img src="${img.image_path}"><a href="manage_products.php?delete_image=${img.id}">x</a>`;
            imgContainer.appendChild(div);
        });
        makeImagesDraggable();
    });
}

function makeImagesDraggable() {
    const container = document.getElementById('existing_images');
    let dragged;
    container.querySelectorAll('.draggable-img').forEach(item => {
        item.addEventListener('dragstart', e => { dragged = e.target; e.target.style.opacity=0.5; });
        item.addEventListener('dragend', e => { e.target.style.opacity=''; });
    });
    container.addEventListener('dragover', e => e.preventDefault());
    container.addEventListener('drop', e => {
        e.preventDefault();
        if(e.target.closest('.draggable-img') && e.target.closest('.draggable-img') !== dragged) {
            container.insertBefore(dragged, e.target.closest('.draggable-img').nextSibling);
        }
    });
}

function saveOrder() {
    const order = [];
    document.querySelectorAll('#existing_images .draggable-img').forEach((div,i) => { order.push(div.dataset.id); });
    fetch('manage_products.php', {
        method:'POST',
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body:'save_order=1&'+new URLSearchParams({order:order})
    }).then(res=>res.text()).then(txt=>alert(txt));
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
</body>
</html>
