<?php
session_start();

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

require_once "database.php";

$showForm = true; // Variable to control the display of the form

// Check if house_id is submitted
if (isset($_POST["submit_house_id"])) {
    $house_id = $_POST["house_id"];

    // Fetch data from water_bills table for the entered house_id
    $sqlWaterBill = "SELECT * FROM water_bills WHERE house_id = ?";
    $stmtWaterBill = mysqli_prepare($conn, $sqlWaterBill);

    if ($stmtWaterBill) {
        mysqli_stmt_bind_param($stmtWaterBill, "s", $house_id);
        mysqli_stmt_execute($stmtWaterBill);
        $resultWaterBill = mysqli_stmt_get_result($stmtWaterBill);

        // Check if there is any data
        if ($row = mysqli_fetch_assoc($resultWaterBill)) {
            // Redirect to display_water_bill.php with the house_id as a parameter
            header("Location: display_water_bill.php?house_id=$house_id");
            exit();
        } else {
            echo "<script>alert('No data found for House ID: $house_id');</script>";
        }

        mysqli_stmt_close($stmtWaterBill);
    } else {
        die("Error preparing statement: " . mysqli_error($conn));
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>House ID Entry</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="css/user.css">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h1>Enter House ID</h1>
            <form action="user.php" method="post">
                <div class="form-group">
                    <label for="house_id">House ID:</label>
                    <input type="text" id="house_id" name="house_id" class="form-control" required>
                </div>
                <button type="submit" name="submit_house_id" class="btn btn-primary">Submit</button>
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </form>
        </div>
    </div>
</body>
</html>
