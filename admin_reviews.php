<?php
if (session_status() == PHP_SESSION_NONE) session_start();
include 'db_connect.php';

// Admin check
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Handle Deletion
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM project_reviews WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: admin_reviews.php?status=deleted");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin | Client Reviews</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="img/logo-white.png" rel="icon" sizes="32x32" type="image/png">

    <style>
        :root {
            --admin-bg: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --accent-gold: #c8b16f;
            --accent-red: #ef4444;
            --border-color: #e2e8f0;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            background-color: var(--admin-bg);
            color: var(--text-main);
            margin: 0;
        }

        .main-content {
            padding: 2.5rem;
            margin-left: 240px;
            transition: var(--transition);
        }

        .page-header {
            margin-bottom: 2.5rem;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            flex-wrap: wrap;
            gap: 1.5rem;
        }

        .page-header h1 {
            font-size: 1.875rem;
            font-weight: 800;
            letter-spacing: -0.025em;
            margin: 0 0 0.5rem 0;
        }

        .page-header p {
            color: var(--text-muted);
            margin: 0;
            font-size: 0.95rem;
        }

        .search-container {
            position: relative;
            min-width: 300px;
        }

        .search-container i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
        }

        .search-input {
            width: 100%;
            padding: 0.625rem 1rem 0.625rem 2.5rem;
            border-radius: 0.75rem;
            border: 1px solid var(--border-color);
            background: var(--card-bg);
            font-size: 0.875rem;
            transition: var(--transition);
        }

        .search-input:focus {
            outline: none;
            border-color: var(--accent-gold);
            box-shadow: 0 0 0 4px rgba(200, 177, 111, 0.1);
        }

        .table-wrapper {
            background: var(--card-bg);
            border-radius: 1rem;
            border: 1px solid var(--border-color);
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background: #fcfcfc;
            padding: 1rem 1.25rem;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            border-bottom: 2px solid var(--admin-bg);
        }

        .table tbody td {
            padding: 1.25rem;
            vertical-align: middle;
            color: var(--text-main);
            border-bottom: 1px solid var(--border-color);
        }

        .rating-cell {
            white-space: nowrap;
            color: var(--accent-gold);
            font-size: 0.9rem;
        }

        .text-light-gray {
            color: #e2e8f0;
        }

        .review-text-cell {
            max-width: 400px;
            font-size: 0.875rem;
            color: var(--text-muted);
            display: -webkit-box;
            -webkit-line-clamp: 3;
            line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .date-cell {
            font-size: 0.8rem;
            font-weight: 500;
            color: var(--text-muted);
        }

        .btn-delete-icon {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fef2f2;
            color: var(--accent-red);
            border-radius: 0.5rem;
            border: 1px solid #fee2e2;
            transition: var(--transition);
            text-decoration: none;
        }

        .btn-delete-icon:hover {
            background: var(--accent-red);
            color: #fff;
        }

        @media (max-width: 992px) {
            .main-content {
                margin-left: 0;
                padding: 1.5rem;
            }
        }
    </style>
</head>

<body>

    <?php include 'admin_sidebar.php'; ?>
    <?php $result = mysqli_query($conn, "SELECT * FROM project_reviews ORDER BY created_at DESC"); ?>

    <div class="main-content">
        <div class="page-header">
            <div>
                <h1>Client Reviews</h1>
                <p>Monitor and moderate feedback shared by your project clients.</p>
            </div>
            <div class="search-container">
                <i class="bi bi-search"></i>
                <input type="text" id="reviewSearch" class="search-input" placeholder="Search by name or content...">
            </div>
        </div>

        <div class="table-wrapper">
            <table class="table" id="reviewsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Client</th>
                        <th>Rating</th>
                        <th>Review Feedback</th>
                        <th>Date Submitted</th>
                        <th style="text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr class="review-row">
                            <td style="color: var(--text-muted); font-size: 0.8rem;">#<?= $row['id'] ?></td>
                            <td style="font-weight: 600;"><?= htmlspecialchars($row['client_name']) ?></td>
                            <td class="rating-cell">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="bi bi-star-fill <?= $i <= $row['rating'] ? '' : 'text-light-gray' ?>"></i>
                                <?php endfor; ?>
                            </td>
                            <td>
                                <div class="review-text-cell" title="<?= htmlspecialchars($row['review']) ?>">
                                    <?= htmlspecialchars($row['review']) ?>
                                </div>
                            </td>
                            <td class="date-cell"><?= date('d M Y', strtotime($row['created_at'])); ?></td>
                            <td style="text-align: right;">
                                <a href="?delete=<?= $row['id'] ?>" class="btn-delete-icon" onclick="return confirm('Permanently delete this review?')">
                                    <i class="bi bi-trash3"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Dynamic Search Filter
        document.getElementById('reviewSearch').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            const rows = document.querySelectorAll('.review-row');

            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>

</body>

</html>