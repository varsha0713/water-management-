<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

session_start();

if (isset($_SESSION["user"])) {
    header("Location: index.php");
    exit();
}

require_once "database.php";

if (isset($_POST["submit"])) {
    $fullName = $_POST["fullname"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $passwordRepeat = $_POST["repeat_password"];
    $place = $_POST["place"];
    $house_id = $_POST["house_id"]; // Added house_id field

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $errors = array();

    if (empty($fullName) || empty($email) || empty($password) || empty($passwordRepeat) || empty($place) || empty($house_id)) {
        array_push($errors, "All fields are required");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        array_push($errors, "Email is not valid");
    }
    if (strlen($password) < 8) {
        array_push($errors, "Password must be at least 8 characters long");
    }
    if ($password !== $passwordRepeat) {
        array_push($errors, "Password does not match");
    }

    // Check if the email already exists
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_stmt_init($conn);

    if ($stmt) {
        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $rowCount = mysqli_num_rows($result);

        if ($rowCount > 0) {
            array_push($errors, "Email already exists!");
        }

        mysqli_stmt_close($stmt);
    } else {
        die("Error preparing statement: " . mysqli_error($conn));
    }

    // If no errors, proceed with registration
    if (count($errors) > 0) {
        foreach ($errors as $error) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
    } else {
        $sql = "INSERT INTO users (full_name, email, password, place, house_id) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_stmt_init($conn);

        if ($stmt) {
            mysqli_stmt_prepare($stmt, $sql);
            mysqli_stmt_bind_param($stmt, "sssss", $fullName, $email, $passwordHash, $place, $house_id);
            if (mysqli_stmt_execute($stmt)) {
                echo "<div class='alert alert-success'>You are registered successfully.</div>";

                // Redirect to login page or another appropriate page
                header("Location: login.php");
                exit();
            } else {
                die("Error executing statement: " . mysqli_error($conn));
            }

            mysqli_stmt_close($stmt);
        } else {
            die("Error preparing statement: " . mysqli_error($conn));
        }
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
    <title>Registration Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="css/reg.css"> <!-- Include reg.css -->
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <!-- The entire form is now within the container, which is the background image -->
    <div class="image-container">
        <img src="image/register.jpg" alt="Registration Image" class="img-fluid">
    </div>
    <div class="container">
        <form action="registration.php" method="post">
            <div class="form-group">
                <label for="fullname">Full Name:</label>
                <input type="text" class="form-control" name="fullname" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <div class="form-group">
                <label for="repeat_password">Repeat Password:</label>
                <input type="password" class="form-control" name="repeat_password" required>
            </div>
            <div class="form-group">
                <label for="place">Place:</label>
                <input type="text" class="form-control" name="place" required>
            </div>
            <div class="form-group">
                <label for="house_id">House ID:</label>
                <input type="text" class="form-control" name="house_id" required>
            </div>
            <div class="form-btn">
                <input type="submit" class="btn btn-primary" value="Register" name="submit">
            </div>
        </form>
        <div>
            <div><p>Already Registered <a href="login.php">Login Here</a></p></div>
        </div>
    </div>
</body>

</html>
