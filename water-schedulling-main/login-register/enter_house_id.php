<?php
session_start();

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST["submit_house_id"])) {
    $house_id = $_POST["house_id"];
    
    // Set the house_id in the session
    $_SESSION["house_id"] = $house_id;
    
    // Redirect the user back to user.php to display water bill
    header("Location: user.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter House ID</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="css/user.css">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h1>Enter House ID</h1>
            <form action="enter_house_id.php" method="post">
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
