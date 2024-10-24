<?php
session_start();
$admin = "admin";
// check if the user is logged in
if (!isset($_SESSION['username'])) {
    // if the user is not logged in, move back to login page
    echo "<script>window.location.href='index.php';</script>";
    exit();
}
// if the user is an admin, redirect to the admin page
if ($_SESSION['username'] === $admin){
    echo "<script>window.location.href='adminpage.php';</script>";
    exit();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Userpage</title>
    <link rel="stylesheet" type="text/css" href="styles/styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="user-page"> 
        <?php
        // Handling form submission for registration
        echo("<h1>Welcome Back, " . htmlspecialchars($_SESSION['username']) . "</h1>");
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['logout'])) {
            session_destroy();
            echo "<script>window.location.href='index.php';</script>";
            exit();
        }
        ?>

        <form method="post" action="">
            <button type="submit" name="logout" class="button">Logout</button>
        </form>
    </div>
</body>
</html>
