<?php
include 'Database.php';
$database = new Database();
$db = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete'])) {
        $orderId = $_POST['order_id'];
        $query = "DELETE FROM orders WHERE id = :order_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':order_id', $orderId);
        $stmt->execute();
    } elseif (isset($_POST['edit'])) {
        $orderId = $_POST['order_id'];
        $quantity = $_POST['quantity'];
        
        // Fetch the item price from menu_items table
        $queryItem = "SELECT m.price FROM orders o JOIN menu_items m ON o.item_id = m.id WHERE o.id = :order_id";
        $stmtItem = $db->prepare($queryItem);
        $stmtItem->bindParam(':order_id', $orderId);
        $stmtItem->execute();
        $item = $stmtItem->fetch(PDO::FETCH_ASSOC);
        
        if ($item) {
            $itemPrice = $item['price'];
            $totalPrice = $quantity * $itemPrice; // Calculate the total price
            $queryUpdate = "UPDATE orders SET quantity = :quantity, total_price = :total_price WHERE id = :order_id";
            $stmtUpdate = $db->prepare($queryUpdate);
            $stmtUpdate->bindParam(':order_id', $orderId);
            $stmtUpdate->bindParam(':quantity', $quantity);
            $stmtUpdate->bindParam(':total_price', $totalPrice);
            $stmtUpdate->execute();
        }
    }
}

$tableId = isset($_GET['table_id']) ? $_GET['table_id'] : 0;

$query = "SELECT o.id, o.quantity, o.total_price, m.name 
          FROM orders o 
          JOIN menu_items m ON o.item_id = m.id 
          WHERE o.table_id = :table_id";
$stmt = $db->prepare($query);
$stmt->bindParam(':table_id', $tableId);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Orders</title>
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

        .order-item {
            border: 1px solid #ddd;
            padding: 10px;
            margin: 10px 0;
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .delete-button {
            background-color: #FF5733;
        }

        .back-button {
            background-color: #333;
            color: white;
            padding: 10px 20px;
            text-align: center;
            margin-top: 20px;
            display: inline-block;
        }

        .back-button a {
            color: white;
            text-decoration: none;
        }

        .back-button:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <header>
        <h1>Edit Orders</h1>
    </header>

    <section>
        <div class="back-button">
            <a href="Admin_Main.php">Back to Admin Main</a>
        </div>
        <h2>Orders for Table ID: <?php echo htmlspecialchars($tableId); ?></h2>
        <?php foreach ($orders as $order): ?>
            <div class="order-item">
                <form method="POST">
                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                    <input type="hidden" name="total_price" value="<?php echo $order['total_price']; ?>"> <!-- Hidden total price -->
                    <p><strong><?php echo htmlspecialchars($order['name']); ?></strong></p>
                    <p>Quantity: <input type="number" name="quantity" value="<?php echo $order['quantity']; ?>" oninput="updateTotalPrice(this)"></p>
                    <p>Total Price: RM<input type="text" name="total_price_display" value="<?php echo $order['total_price']; ?>" readonly></p>
                    <button type="submit" name="edit">Edit</button>
                    <button type="submit" name="delete" class="delete-button">Delete</button>
                </form>
            </div>
        <?php endforeach; ?>
    </section>

    <script>
        function updateTotalPrice(input) {
            const form = input.closest('form');
            const quantity = input.value;
            const itemPrice = <?php echo json_encode($itemPrice); ?>; // Fetch the item price from PHP
            const totalPriceInput = form.querySelector('input[name="total_price_display"]');
            const totalPrice = quantity * itemPrice;
            totalPriceInput.value = totalPrice.toFixed(2);
        }
    </script>
</body>
</html>
