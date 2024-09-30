<?php
session_start();

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

require_once "database.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/waterbill.css">
    <title>Water Bill Details</title>
    <style>
        body {
            background: url('../image/sea.jpg') center/cover no-repeat;
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            position: relative; /* Set position relative for absolute positioning */
        }

     
        img {
    width: 100%;
    max-height: 100vh; /* Set a maximum height equal to the viewport height */
    object-fit: cover; /* Ensure the image covers the container while maintaining its aspect ratio */
}
        .container {
            max-width: 600px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h2 {
            color: #007bff;
        }

        p {
            margin: 10px 0;
        }

        .back-link {
            display: block;
            margin-top: 20px;
            text-align: center;
        }

        .back-link a {
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>
<body>
    
    </div>
    <div class="container">
        <?php
        // Check if house_id is provided as a parameter
        if (isset($_GET["house_id"])) {
            $house_id = $_GET["house_id"];

            // Fetch data from water_bills table for the provided house_id
            $sqlWaterBill = "SELECT * FROM water_bills WHERE house_id = ?";
            $stmtWaterBill = mysqli_prepare($conn, $sqlWaterBill);

            if ($stmtWaterBill) {
                mysqli_stmt_bind_param($stmtWaterBill, "s", $house_id);
                mysqli_stmt_execute($stmtWaterBill);
                $resultWaterBill = mysqli_stmt_get_result($stmtWaterBill);

                // Display data for the provided house_id
                if ($row = mysqli_fetch_assoc($resultWaterBill)) {
                    echo "<h2>Water Bill Details for House ID: $house_id</h2>";
                    echo "<p>Amount: $" . $row['amount'] . "</p>";
                    echo "<p>Due Date: " . $row['due_date'] . "</p>";
                    echo "<p>Month: " . $row['month_of'] . "</p>";
                } else {
                    echo "<p>No data found for House ID: $house_id</p>";
                }

                mysqli_stmt_close($stmtWaterBill);
            } else {
                die("Error preparing statement: " . mysqli_error($conn));
            }
        } else {
            echo "<p>House ID not provided.</p>";
        }
        ?>

        <div class="back-link">
            <a href="user.php">&larr; Go back</a>
        </div>
    </div>
</body>
</html>

<?php
mysqli_close($conn);
?>
