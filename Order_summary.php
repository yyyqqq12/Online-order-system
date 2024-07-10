<?php
session_start();
include 'Database.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: Cus_Login.php");
    exit;
}

$customerId = $_SESSION['customer_id'];

$database = new Database();
$db = $database->getConnection();

$query = "SELECT o.id, o.quantity, o.total_price, m.name 
          FROM orders o 
          JOIN menu_items m ON o.item_id = m.id
          WHERE o.customer_id = :customer_id";
$stmt = $db->prepare($query);
$stmt->bindParam(':customer_id', $customerId);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Summary</title>
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
            padding: 20px 0;
            text-align: center;
            font-size: 2em;
            margin: auto;
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
    </style>
</head>
<body>
    <header>
        <h1>Order Summary</h1>
    </header>

    <section>
        <h2>Your Orders</h2>
        <?php foreach ($orders as $order): ?>
            <div class="order-item">
                <p><strong><?php echo htmlspecialchars($order['name']); ?></strong></p>
                <p>Quantity: <?php echo $order['quantity']; ?></p>
                <p>Total Price: RM<?php echo $order['total_price']; ?></p>
            </div>
        <?php endforeach; ?>
    </section>
</body>
</html>
