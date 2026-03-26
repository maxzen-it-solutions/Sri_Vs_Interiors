<?php
include 'db_connect.php';
include 'admin_sidebar.php';

$result = mysqli_query($conn, "SELECT * FROM project_reviews ORDER BY created_at DESC");
?>

<div class="main-content">

<h2>Project Reviews</h2>

<table border="1" width="100%" cellpadding="10">
<tr>
<th>ID</th>
<th>Client Name</th>
<th>Rating</th>
<th>Review</th>
<th>Date</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)) { ?>

<tr>
<td><?= $row['id'] ?></td>
<td><?= htmlspecialchars($row['client_name']) ?></td>
<td><?= $row['rating'] ?> ⭐</td>
<td><?= htmlspecialchars($row['review']) ?></td>
<td><?= $row['created_at'] ?></td>
</tr>

<?php } ?>

</table>

</div>