<?php
session_start();
include "Database.php";
include "Cus.php";
include "Head1.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Login Page</title>
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
            margin: auto; /* Center horizontally */
        }

        #welcome-section {
            text-align: center;
            margin: 50px auto; /* Center vertically and horizontally */
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            width: 80%; /* Limit width */
            max-width: 400px; /* Maximum width */
        }

        #welcome-section h2 {
            margin-bottom: 20px;
            font-size: 1.5em;
        }

        h1, h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }

        input[type="text"], input[type="password"], button {
            display: block;
            width: 95%;
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 1em;
            border-radius: 4px;
        }

        button:hover {
            background-color: #45a049;
        }

        .password-container {
            position: relative;
        }

        #password {
            padding-right: 15px;
        }

        #img1 {
            position: absolute;
            right: 10px;
            top: 65%;
            transform: translateY(-50%);
            cursor: pointer;
        }

        .register-link {
            display: block;
            margin-top: 20px;
            text-align: center;
        }

        .register-link a {
            text-decoration: none;
            color: #4CAF50;
            font-size: 1em;
        }

        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<section id="welcome-section">
    <h2>YQ Restaurant Customer Login</h2>
    <div class="login-container">
        <h2>Customer Login</h2>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $database = new Database();
            $db = $database->getConnection();

            $customer = new Customer($db);
            $customer->username = $_POST['username'];
            $customer->password = $_POST['password'];

            if ($customer->login()) {
                setcookie("username", $customer->username, time() + 86400 * 30);
                header("Location: Cus_SelectTable.php");
                exit;
            } else {
                echo "<p class='error'>Username or password incorrect</p>";
            }
        }
        ?>

        <form method="POST" action="Cus_Login.php">
            Username: <input type="text" name="username" required><br>
            <div class="password-container">
                Password: 
                <input type="password" name="password" id="password" required>
                <img id="img1" src="eyesclose.jpeg" width="20" onclick="togglePasswordVisibility()">
            </div>
            <br>
            <button type="submit" value="Login">Login</button>
        </form>
        
        <div class="register-link">
            <a href="Cus_register.php">Need to register? Click here!!!</a>
        </div>

        <script>
            function togglePasswordVisibility() {
                let passwordField = document.getElementById("password");
                let eyeIcon = document.getElementById("img1");

                if (passwordField.type === "password") {
                    passwordField.type = "text";
                    eyeIcon.src = "eyesopen.png";
                } else {
                    passwordField.type = "password";
                    eyeIcon.src = "eyesclose.jpeg";
                }
            }
        </script> 
    </div>
</section>
</body>
</html>
