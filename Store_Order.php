<?php
header('Content-Type: application/json');

// Include database connection configuration
include 'Database.php';

// Retrieve JSON input data
$data = json_decode(file_get_contents("php://input"), true);

// Check if JSON decoding was successful
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid JSON input']);
    exit;
}

try {
    // Initialize database connection
    $database = new Database();
    $db = $database->getConnection();

    // Extract data from JSON
    $tableId = isset($data['table_id']) ? intval($data['table_id']) : null;
    $orderItems = isset($data['order']) ? $data['order'] : [];

    // Validate table_id
    if ($tableId === null) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Missing table_id']);
        exit;
    }

    // Check if table_id exists in tables table
    $checkTableQuery = "SELECT id FROM tables WHERE id = :table_id";
    $checkTableStmt = $db->prepare($checkTableQuery);
    $checkTableStmt->bindParam(':table_id', $tableId, PDO::PARAM_INT);
    $checkTableStmt->execute();

    if ($checkTableStmt->rowCount() === 0) {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Table not found']);
        exit;
    }

    // Insert order items into orders table
    foreach ($orderItems as $item) {
        $itemId = isset($item['item_id']) ? intval($item['item_id']) : null;
        $quantity = isset($item['quantity']) ? intval($item['quantity']) : null;
        $totalPrice = isset($item['total_price']) ? floatval($item['total_price']) : null;

        if ($itemId === null || $quantity === null || $totalPrice === null) {
            continue; // Skip incomplete order items
        }

        $insertOrderQuery = "INSERT INTO orders (item_id, quantity, total_price, table_id) 
                             VALUES (:item_id, :quantity, :total_price, :table_id)";
        $insertOrderStmt = $db->prepare($insertOrderQuery);
        $insertOrderStmt->bindParam(':item_id', $itemId, PDO::PARAM_INT);
        $insertOrderStmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        $insertOrderStmt->bindParam(':total_price', $totalPrice, PDO::PARAM_STR);
        $insertOrderStmt->bindParam(':table_id', $tableId, PDO::PARAM_INT);
        $insertOrderStmt->execute();
    }

    // Respond with success message
    echo json_encode(['status' => 'success', 'message' => 'Order stored successfully']);

} catch (PDOException $e) {
    // Database error handling
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);

} catch (Exception $e) {
    // General error handling
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Server error: ' . $e->getMessage()]);
}
?>
