<?php
session_start();
if (!isset($_SESSION['username'])) {
    // if the user is not logged in, move back to login page
    echo "<script>window.location.href='index.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Userpage</title>
</head>
<body>
    <h1>Welcome to the user page</h1>
    
    <?php
    // Handling form submission for registration
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['logout'])) {

        session_destroy();
        echo "<script>window.location.href='index.php';</script>";
        exit();
    }
    ?>

    <form method="post" action="">
        <button type="submit" name="logout">Logout</button>
    </form>
</body>
</html>
