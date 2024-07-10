<?php
header('Content-Type: application/json');
include 'Database.php';

try {
    $database = new Database();
    $db = $database->getConnection();

    $query = "SELECT * FROM tables";
    $stmt = $db->prepare($query);
    $stmt->execute();

    $tables = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['status' => 'success', 'data' => $tables]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Server error: ' . $e->getMessage()]);
}
?>
