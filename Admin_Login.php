<?php
session_start();
include "Database.php";
include "Admin.php";
include "Head1.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
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
        button {
        display: block;
            width: 100%;
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="text"], input[type="password"]{
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
            background-color: #0056b3;
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
    </style>

    



</head>
<body>
<section id="welcome-section">
    <h2>YQ Restaurant Admin Login </h2>
    <div class="login-container">
        <h2>Admin Login</h2>

        <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $database = new Database();
                $db = $database->getConnection();

                $admin = new Admin($db);
                $admin->username = $_POST['username'];
                $admin->password = $_POST['password'];

                if ($admin->login()) {
                    setcookie("username", $admin->username, time() + 86400*30);
                    header("Location: Admin_Main.php");
                    exit;
                } else {
                    echo "<p class='error'>Username or password incorrect</p>";
                }
            }
        ?>

        <form method="POST" action="Admin_Login.php">
            Username: <input type="text" name="username" required><br>
            <div class="password-container">
                Password: 
                <input type="password" name="password" id="password" required>
                <img id="img1" src="eyesclose.jpeg" width="20" onclick="togglePasswordVisibility()">
            </div>
            <br>
            <button type="submit" value="Login">Login</button>
        </form>
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
