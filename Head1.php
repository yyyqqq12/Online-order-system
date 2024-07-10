<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Header</title>
    <style>
        
            
    </style>
</head>
<body>
    <?php if ($current_page == 'Cus_Login.php'): ?>
        <a href="Admin_Login.php" class="toggle-button">Switch to Admin Login</a>
    <?php elseif ($current_page == 'Admin_Login.php'): ?>
        <a href="Cus_Login.php" class="toggle-button">Switch to Customer Login</a>
    <?php endif; ?>
</body>
</html>
