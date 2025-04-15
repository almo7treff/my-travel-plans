<?php
require 'config.php';

$search_results = [];
$query = '';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['query'])) {
    $query = $_GET['query'];
    $like_query = "%" . $query . "%";

    $stmt = $conn->prepare("
        SELECT * FROM inventory 
        WHERE serial_number LIKE ? 
        OR weight LIKE ? 
        OR added_at LIKE ?
    ");

    $stmt->bind_param("sss", $like_query, $like_query, $like_query);
    $stmt->execute();
    $search_results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <h1>Search Inventory</h1>
        <form method="GET" class="mb-4">
            <input type="text" name="query" class="form-control" placeholder="Search by serial number, weight, or date" value="<?= htmlspecialchars($query) ?>">
            <button type="submit" class="btn btn-primary mt-2">Search</button>
        </form>
        <?php if (!empty($search_results)): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Metal</th>
                        <th>Weight</th>
                        <th>Fineness</th>
                        <th>Serial Number</th>
                        <th>Added By</th>
                        <th>Added At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($search_results as $item): ?>
                        <tr>
                            <td><?= $item['metal'] ?></td>
                            <td><?= $item['weight'] ?></td>
                            <td><?= $item['fineness'] ?></td>
                            <td>
                                <a href="print_certificate.php?serial_number=<?= urlencode($item['serial_number']) ?>">
                                    <?= $item['serial_number'] ?>
                                </a>
                            </td>
                            <td><?= $item['added_by'] ?></td>
                            <td><?= $item['added_at'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No results found.</p>
        <?php endif; ?>
    </div>
</body>
</html>