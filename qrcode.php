<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Codes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            text-align: center;
        }

        .qr-code {
            margin: 20px;
            display: inline-block;
            text-align: center;
        }

        .qr-code img {
            max-width: 200px;
            max-height: 200px;
            width: auto;
            height: auto;
        }

        .qr-code p {
            margin-top: 10px;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <header>
        <h1>QR Codes for Tables</h1>
    </header>

    <section>
        <?php
        // Loop to create QR code entries for tables 1 to 5
        for ($tableId = 1; $tableId <= 5; $tableId++) {
            echo '<div class="qr-code">';
            echo '<a href="Main.html?table_id=' . $tableId . '">';
            echo '<img src="qr' . $tableId . '.jpg" alt="QR Code for Table ' . $tableId . '">';
            echo '</a>';
            echo '<p>Table ' . $tableId . '</p>';
            echo '</div>';
        }
        ?>
    </section>
</body>
</html>
