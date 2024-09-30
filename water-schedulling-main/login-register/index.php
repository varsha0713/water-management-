<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

session_start();

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

require_once "database.php";

if (isset($_POST["submit"])) {
    $placeName = mysqli_real_escape_string($conn, $_POST["place_name"]);
    $scheduleDate = mysqli_real_escape_string($conn, $_POST["schedule_date"]);
    $reason = mysqli_real_escape_string($conn, $_POST["delay_reason"]);

    // Check if a schedule with the same place name already exists
    $checkQuery = "SELECT id FROM user_schedules WHERE place_name = ?";
    $stmtCheck = mysqli_prepare($conn, $checkQuery);
    
    if ($stmtCheck) {
        mysqli_stmt_bind_param($stmtCheck, "s", $placeName);
        mysqli_stmt_execute($stmtCheck);
        mysqli_stmt_store_result($stmtCheck);
        
        if (mysqli_stmt_num_rows($stmtCheck) > 0) {
            // Update the existing schedule entry
            $updateQuery = "UPDATE user_schedules SET schedule_date = ?, delay_reason = ? WHERE place_name = ?";
            $stmtUpdate = mysqli_prepare($conn, $updateQuery);
            
            if ($stmtUpdate) {
                mysqli_stmt_bind_param($stmtUpdate, "sss", $scheduleDate, $reason, $placeName);
                
                if (mysqli_stmt_execute($stmtUpdate)) {
                    echo "<script>alert('Schedule updated successfully.');</script>";
                } else {
                    echo "Error updating schedule: " . mysqli_stmt_error($stmtUpdate);
                }
                
                mysqli_stmt_close($stmtUpdate);
            } else {
                echo "Error preparing update statement: " . mysqli_error($conn);
            }
        } else {
            // Insert a new schedule entry
            $insertQuery = "INSERT INTO user_schedules (place_name, schedule_date, delay_reason) 
                            VALUES (?, ?, ?)";
            $stmtInsert = mysqli_prepare($conn, $insertQuery);
            
            if ($stmtInsert) {
                mysqli_stmt_bind_param($stmtInsert, "sss", $placeName, $scheduleDate, $reason);
                
                if (mysqli_stmt_execute($stmtInsert)) {
                    echo "<script>alert('Data inserted successfully.');</script>";
                } else {
                    echo "Error inserting schedule: " . mysqli_stmt_error($stmtInsert);
                }
                
                mysqli_stmt_close($stmtInsert);
            } else {
                echo "Error preparing insert statement: " . mysqli_error($conn);
            }
        }

        mysqli_stmt_close($stmtCheck);
    } else {
        echo "Error preparing check statement: " . mysqli_error($conn);
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
    <title>Admin Schedules Update</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="css/ind.css"> <!-- Include user_schedules.css -->
</head>
<body>
    <!-- Background Image Container -->
    <div class="image-container">
        <img src="image/register.jpg" alt="Background Image" class="img-fluid">
    </div>

    <!-- Main Content Container -->
    <div class="container">
        <h1>Admin Schedules Update</h1>

        <h2>Add Schedule:</h2>
        <form action="index.php" method="post">
            <div class="form-group">
                <label for="place_name">Place Name:</label>
                <input type="text" class="form-control" name="place_name" required>
            </div>
            <div class="form-group">
                <label for="schedule_date">Schedule Date:</label>
                <input type="date" class="form-control" name="schedule_date" required>
            </div>
            <div class="form-group">
                <label for="delay_reason">Reason:</label>
                <input type="text" class="form-control" name="delay_reason" required>
            </div>
            <div class="form-btn text-center">
                <input type="submit" class="btn btn-primary" value="Submit" name="submit">
            </div>
        </form>
    </div>
    <a href="logout.php" class="btn btn-danger">Logout</a>
</body>
</html>
