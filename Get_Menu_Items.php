
<?php
include 'Database.php';
$database = new Database();
$db = $database->getConnection();

$query = "SELECT id, name, description, price, image_path FROM menu_items";
$stmt = $db->prepare($query);
$stmt->execute();
$menuItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($menuItems);
?>
