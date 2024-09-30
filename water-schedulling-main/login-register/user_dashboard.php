<?php
session_start();

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

require_once "database.php";

$userPlace = $_SESSION["user_place"];

$userSchedules = array();
if (!empty($userPlace)) {
    $sqlSchedules = "SELECT * FROM user_schedules WHERE place_name = ?";
    $stmtSchedules = mysqli_stmt_init($conn);

    if ($stmtSchedules) {
        mysqli_stmt_prepare($stmtSchedules, $sqlSchedules);
        mysqli_stmt_bind_param($stmtSchedules, "s", $userPlace);
        mysqli_stmt_execute($stmtSchedules);
        $resultSchedules = mysqli_stmt_get_result($stmtSchedules);

        while ($schedule = mysqli_fetch_assoc($resultSchedules)) {
            $userSchedules[] = $schedule;
        }

        mysqli_stmt_close($stmtSchedules);
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
    <title>User Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="css/dash.css"> <!-- Include reg.css -->
</head>
<body>
    <div class="image-container">
        <img src="image/register.jpg" alt="Registration Image" class="img-fluid">
    </div>
    <div class="container">
        <h2>User Dashboard</h2>
        <p>Welcome :</p>

        <?php if (!empty($userSchedules)) : ?>
            <h3>Your Schedules:</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Place Name</th>
                        <th>Schedule Date</th>
                        <th>Delay Reason</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($userSchedules as $schedule) : ?>
                        <tr>
                            <td><?php echo $schedule["place_name"]; ?></td>
                            <td><?php echo $schedule["schedule_date"]; ?></td>
                            <td><?php echo $schedule["delay_reason"]; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <div class="alert alert-warning">No schedules found for your place.</div>
        <?php endif; ?>

        <!-- Button to see water bill -->
        <a href="user.php" class="btn btn-primary">See Water Bill</a>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</body>
</html>
