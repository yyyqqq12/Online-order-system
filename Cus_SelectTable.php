<?php
session_start();
include "Database.php";

$database = new Database();
$db = $database->getConnection();

$query = "SELECT * FROM tables";
$stmt = $db->prepare($query);
$stmt->execute();
$tables = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Table</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        header {
            background-color: #333;
            color: white;
            padding: 10px 0;
            text-align: center;
        }

        section {
            padding: 20px;
        }

        .table-item {
            border: 1px solid #ddd;
            padding: 10px;
            margin: 10px 0;
            cursor: pointer;
            text-align: center;
        }

        .table-item:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
    <header>
        <h1>Select Table</h1>
    </header>

    <section>
        <h2>Please Select a Table Number</h2>
        <?php foreach ($tables as $table): ?>
            <div class="table-item" onclick="selectTable(<?php echo $table['id']; ?>)">
                <p>Table Number: <?php echo htmlspecialchars($table['table_number']); ?></p>
            </div>
        <?php endforeach; ?>
    </section>

    <script>
        function selectTable(tableId) {
            window.location.href = 'Main.html?table_id=' + tableId;
        }
    </script>
</body>
</html>
