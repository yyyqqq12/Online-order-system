<?php
include 'Database.php';
$database = new Database();
$db = $database->getConnection();

$query = "SELECT id, table_number FROM tables";
$stmt = $db->prepare($query);
$stmt->execute();
$tables = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Tables</title>
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
        }

        a {
            text-decoration: none;
            color: white;
        }

        .view-orders-button, .manage-menu-button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
        }

        .view-orders-button:hover, .manage-menu-button:hover {
            background-color: #45a049;
        }

        .manage-menu-button {
            background-color: #007BFF;
        }
    </style>
</head>
<body>
    <header>
        <h1>Admin - Main</h1>
    </header>

    <section>
        <button class="manage-menu-button">
            <a href="Admin_Manage_Menu.php">Manage Menu</a>
        </button>
        <h2>Orders</h2>
        <?php foreach ($tables as $table): ?>
            <div class="table-item">
                <p>Table Number: <?php echo htmlspecialchars($table['table_number']); ?></p>
                <button class="view-orders-button">
                    <a href="Admin_Check_Order.php?table_id=<?php echo $table['id']; ?>">View Orders</a>
                </button>
            </div>
        <?php endforeach; ?>
        
    </section>
</body>
</html>
