<?php
include 'Database.php';
$database = new Database();
$db = $database->getConnection();

function uploadImage($file) {
    $targetDir = "uploads/";
    // Check if the uploads directory exists, if not, create it
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    $targetFile = $targetDir . basename($file["name"]);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    $check = getimagesize($file["tmp_name"]);
    if ($check !== false) {
        if (move_uploaded_file($file["tmp_name"], $targetFile)) {
            return $targetFile;
        }
    }
    return null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete'])) {
        $itemId = $_POST['item_id'];
        $query = "DELETE FROM menu_items WHERE id = :item_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':item_id', $itemId);
        $stmt->execute();
    } elseif (isset($_POST['edit'])) {
        $itemId = $_POST['item_id'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $imagePath = !empty($_FILES['image']['name']) ? uploadImage($_FILES['image']) : $_POST['existing_image'];
        $query = "UPDATE menu_items SET name = :name, description = :description, price = :price, image_path = :image_path WHERE id = :item_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':item_id', $itemId);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':image_path', $imagePath);
        $stmt->execute();
    } elseif (isset($_POST['add'])) {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $imagePath = uploadImage($_FILES['image']);
        $query = "INSERT INTO menu_items (name, description, price, image_path) VALUES (:name, :description, :price, :image_path)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':image_path', $imagePath);
        $stmt->execute();
    }
}

$query = "SELECT id, name, description, price, image_path FROM menu_items";
$stmt = $db->prepare($query);
$stmt->execute();
$menuItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Menu</title>
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

        .menu-item {
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

        img {
            max-width: 100px;
            display: block;
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
        <h1>Admin - Manage Menu</h1>
    </header>

    <section>
        <div class="back-button">
            <a href="Admin_Main.php">Back to Admin Main</a>
        </div>
        <h2>Menu Items</h2>
        <?php foreach ($menuItems as $item): ?>
            <div class="menu-item">
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                    <?php if (!empty($item['image_path'])): ?>
                        <img src="<?php echo htmlspecialchars($item['image_path']); ?>" alt="Menu Image">
                    <?php endif; ?>
                    <input type="hidden" name="existing_image" value="<?php echo $item['image_path']; ?>">
                    <p>Image: <input type="file" name="image"></p>
                    <p>Name: <input type="text" name="name" value="<?php echo htmlspecialchars($item['name']); ?>"></p>
                    <p>Description: <input type="text" name="description" value="<?php echo htmlspecialchars($item['description']); ?>"></p>
                    <p>Price: RM<input type="text" name="price" value="<?php echo htmlspecialchars($item['price']); ?>"></p>
                    <button type="submit" name="edit">Edit</button>
                    <button type="submit" name="delete" class="delete-button">Delete</button>
                </form>
            </div>
        <?php endforeach; ?>
        
        <h2>Add New Menu Item</h2>
        <div class="menu-item">
            <form method="POST" enctype="multipart/form-data">
                <p>Image: <input type="file" name="image" required></p>
                <p>Name: <input type="text" name="name" required></p>
                <p>Description: <input type="text" name="description" required></p>
                <p>Price: RM<input type="text" name="price" required></p>
                <button type="submit" name="add">Add</button>
            </form>

        </div>
    </section>
</body>
</html>
