<?php
session_start();

require_once "database.php";

if (isset($_SESSION["user"])) {
    header("Location: user_dashboard.php");
    exit();
}

$loginError = "";

if (isset($_POST["login"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $place = $_POST["place"];

    // Check if the email ends with "admin@gmail.com"
    if (endsWith(trim($email), "admin@gmail.com")) {
        // Admin login
        $sqlAdmin = "SELECT * FROM admins WHERE email = ? AND place = ?";
        $stmtAdmin = mysqli_prepare($conn, $sqlAdmin);

        if ($stmtAdmin) {
            mysqli_stmt_bind_param($stmtAdmin, "ss", $email, $place);
            mysqli_stmt_execute($stmtAdmin);
            $resultAdmin = mysqli_stmt_get_result($stmtAdmin);
            $admin = mysqli_fetch_assoc($resultAdmin);

            if ($admin && password_verify($password, $admin["password"])) {
                // Admin login successful, redirect to index.php
                $_SESSION["user"] = true;
                header("Location: index.php");
                exit();
            } else {
                $loginError = "Invalid admin credentials";
            }

            mysqli_stmt_close($stmtAdmin);
        } else {
            die("Error preparing admin statement: " . mysqli_error($conn));
        }
    } else if ($email === "manglrwaterr@gmail.com") {
        // User login for manglrwaterr@gmail.com
        $sqlMescom = "SELECT * FROM wateradmin WHERE email = ?";
        $stmtMescom = mysqli_prepare($conn, $sqlMescom);

        if ($stmtMescom) {
            mysqli_stmt_bind_param($stmtMescom, "s", $email);
            mysqli_stmt_execute($stmtMescom);
            $resultMescom = mysqli_stmt_get_result($stmtMescom);
            $mescom = mysqli_fetch_assoc($resultMescom);

            if ($mescom && $password === $mescom["password"]) {
                // User login successful, redirect to waterbill.php
                $_SESSION["user"] = true;
                header("Location: waterbill.php");
                exit();
            } else {
                $loginError = "Invalid user credentials";
            }

            mysqli_stmt_close($stmtMescom);
        } else {
            die("Error preparing mescom statement: " . mysqli_error($conn));
        }
    } else {
        // Regular user login
        $sqlUser = "SELECT * FROM users WHERE email = ? AND place = ?";
        $stmtUser = mysqli_prepare($conn, $sqlUser);

        if ($stmtUser) {
            mysqli_stmt_bind_param($stmtUser, "ss", $email, $place);
            mysqli_stmt_execute($stmtUser);
            $resultUser = mysqli_stmt_get_result($stmtUser);
            $user = mysqli_fetch_assoc($resultUser);

            if ($user && password_verify($password, $user["password"])) {
                // Regular user login successful, redirect to user_dashboard.php
                $_SESSION["user"] = true;
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["user_place"] = $user["place"];
                header("Location: user_dashboard.php");
                exit();
            } else {
                $loginError = "Invalid user credentials";
            }

            mysqli_stmt_close($stmtUser);
        } else {
            die("Error preparing user statement: " . mysqli_error($conn));
        }
    }
}

mysqli_close($conn);

// Custom function to check if a string ends with a specific substring
function endsWith($haystack, $needle) {
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }
    return (substr($haystack, -$length) === $needle);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="css/log.css"> <!-- Include log.css -->

    <script>
        <?php if ($loginError !== ""): ?>
            // Use JavaScript to display an alert if login fails
            window.onload = function() {
                alert("<?php echo $loginError; ?>");
            };
        <?php endif; ?>
    </script>
</head>
<body>
    <div class="image-container">
        <!-- Use a relative path for your image -->
        <img src="image\waterlogin.jpeg" alt="Your Image" class="img-fluid">
    </div>

    <div class="container">
        <form action="login.php" method="post">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="place">Place:</label>
                <input type="text" name="place" class="form-control" required>
            </div>

            <div class="form-btn">
                <input type="submit" name="login" value="Login" class="btn btn-primary">
            </div>
        </form>

        <div><p>Not registered yet <a href="registration.php">Register Here</a></p></div>
    </div>
</body>
</html>
