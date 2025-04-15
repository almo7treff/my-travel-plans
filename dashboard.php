<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit();
}

require 'config.php';

$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // استقبال البيانات من الفورم
    $metal = $_POST['metal'];
    $weight = $_POST['weight'];
    $fineness = $_POST['fineness'];
    $serial_number = $_POST['serial_number'];
    $added_by = $_SESSION['user'];

    // إضافة البيانات إلى جدول المخزون
    $stmt = $conn->prepare("INSERT INTO inventory (metal, weight, fineness, serial_number, added_by) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sdsss", $metal, $weight, $fineness, $serial_number, $added_by);

    if ($stmt->execute()) {
        $success = "Item added successfully!";
    } else {
        $error = "Error adding item. " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <h1 class="mb-4">Dashboard</h1>
        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php elseif (!empty($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <!-- Form لإضافة عنصر جديد -->
        <form method="POST" class="mb-4">
            <h3>Add New Item</h3>
            <div class="mb-3">
                <label for="metal" class="form-label">Metal</label>
                <input type="text" class="form-control" id="metal" name="metal" required>
            </div>
            <div class="mb-3">
                <label for="weight" class="form-label">Weight (g)</label>
                <input type="number" class="form-control" id="weight" name="weight" step="0.01" required>
            </div>
            <div class="mb-3">
                <label for="fineness" class="form-label">Fineness (%)</label>
                <input type="number" class="form-control" id="fineness" name="fineness" step="0.01" required>
            </div>
            <div class="mb-3">
                <label for="serial_number" class="form-label">Serial Number</label>
                <input type="text" class="form-control" id="serial_number" name="serial_number" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Item</button>
        </form>

        <!-- عرض العناصر الموجودة في المخزون -->
        <h3>Current Inventory</h3>
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
                <?php
                $result = $conn->query("SELECT * FROM inventory");
                while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['metal'] ?></td>
                        <td><?= $row['weight'] ?></td>
                        <td><?= $row['fineness'] ?></td>
                        <td><?= $row['serial_number'] ?></td>
                        <td><?= $row['added_by'] ?></td>
                        <td><?= $row['added_at'] ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>