<?php
session_start();
include "Database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
    $email = htmlspecialchars($_POST['email']);

    // Basic validation
    if (!empty($username) && !empty($password) && !empty($email)) {
        try {
            // Create a new database connection
            $database = new Database();
            $db = $database->getConnection();

            // Prepare the SQL query
            $query = "INSERT INTO cus (Username, Cus_pss, Cus_email) VALUES (:username, :password, :email)";
            $stmt = $db->prepare($query);

            // Bind parameters
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':email', $email);

            // Execute the query
            if ($stmt->execute()) {
                $message = "Registration successful!";
            } else {
                $message = "Registration failed. Please try again.";
            }
        } catch (PDOException $e) {
            $message = "Error: " . $e->getMessage();
        }
    } else {
        $message = "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f2f2f2;
        }

        .container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        .container h2 {
            margin-bottom: 20px;
            font-size: 1.5em;
            text-align: center;
        }

        .container label {
            display: block;
            margin-bottom: 10px;
        }

        .container input[type="text"],
        .container input[type="password"],
        .container input[type="email"] {
            width: 100%;
            padding: 9px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .container button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1em;
        }

        .container button:hover {
            background-color: #45a049;
        }

        .message {
            text-align: center;
            margin-top: 20px;
            color: red;
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
        }

        .login-link a {
            color: #4CAF50;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Customer Registration</h2>
        <form action="Cus_register.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            
            <button type="submit">Register</button>
        </form>
        <?php if (!empty($message)): ?>
            <p class="message"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>
        <div class="login-link">
             <a href="Cus_Login.php">Already have an account?Login</a></p>
        </div>
    </div>
</body>
</html>
