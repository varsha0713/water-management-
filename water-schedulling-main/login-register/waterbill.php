<?php
session_start();
require_once "database.php";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if it's a regular form submission
    if (isset($_POST["submit_water_bill"])) {
        // Retrieve form data
        $house_id = $_POST["house_id"];
        $amount = $_POST["amount"];
        $due_date = $_POST["due_date"];
        $month_of = $_POST["month_of"];

        // Call the stored procedure to insert the water bill
        $sqlInsertWaterBill = "CALL InsertWaterBill(?, ?, ?, ?)";
        $stmtInsertWaterBill = mysqli_prepare($conn, $sqlInsertWaterBill);

        if ($stmtInsertWaterBill) {
            mysqli_stmt_bind_param($stmtInsertWaterBill, "siss", $house_id, $amount, $due_date, $month_of);
            mysqli_stmt_execute($stmtInsertWaterBill);

            // Check for errors
            if (mysqli_stmt_error($stmtInsertWaterBill)) {
                // Handle the error if needed
                // For example, log the error or display a generic error message
            } else {
                // The data was inserted successfully
                echo "<script>alert('Data inserted successfully!');</script>";
            }

            mysqli_stmt_close($stmtInsertWaterBill);
        } else {
            // Handle the error if the statement preparation fails
            // For example, display an error message
            echo "<script>alert('Error preparing statement!');</script>";
        }
    }
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Water Bill Submission</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="css/waterbill.css">
</head>
<body>
    <div class="container">
        <h1>Water Bill Submission</h1>
        <!-- Water Bill Submission Form -->
        <form action="waterbill.php" method="post">
            <div class="form-group">
                <label for="house_id">House ID:</label>
                <input type="text" id="house_id" name="house_id" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="amount">Water Bill Amount:</label>
                <input type="number" id="amount" name="amount" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="due_date">Due Date:</label>
                <input type="date" id="due_date" name="due_date" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="month_of">Month:</label>
                <input type="text" id="month_of" name="month_of" class="form-control" placeholder="Enter month" required>
            </div>
            <div class="form-btn">
                <button type="submit" name="submit_water_bill" class="btn btn-primary">Submit Water Bill</button>
            </div>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </form>
    </div>
</body>
</html>
