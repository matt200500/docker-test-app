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

        $database = 'db'; // database service from docker compose
        $databasename = getenv('DATABASE'); // database name
        $user = getenv('USER'); // user name    
        $rootpassword = getenv('ROOT_PASSWORD'); // root password
        $connection = new mysqli($database, $user, $rootpassword, $databasename); // create connection to database

        // Check connection to the database
        if ($connection->connect_error) {
            die("Connection failed: " . $connection->connect_error);
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['password_change'])) {
            if (empty($_POST['new_password'])){ // if there is no password
            }else{ // if there is a pasword
                $new_password = $_POST['new_password'];
                echo "Password entered: " . htmlspecialchars($new_password); // Debugging output
                $hashed_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT); // has new password
                $sql_statement = $connection->prepare("UPDATE users SET password = ? WHERE username = ?");
                $sql_statement->bind_param("ss", $hashed_password, $_SESSION['username']);
                
                if ($sql_statement->execute()){
                    echo "<script>alert('Successfully changed password to ');</script>";
                }
                else{
                    echo "<script>alert('could not change password');<script>";
                }
                
                $sql_statement->close();
            }
        }

        $connection->close();
        ?>

        <div class="change_password">
            <h2 id="password_header"> Change Password</h2>
            <p id="password_text">Type a new password below to change it</p>
            <form class="form" method="post" action="">
                <input
                    id="signup_password"
                    type="password"
                    name="new_password"
                    placeholder="password"
                    required
                />
                <div class="button-container">
                    <button class="button" type="submit" id="password_button" name="password_change">Change Password</button>
                </div>
            </form>
        </div>
        <form method="post" action="">
            <button type="submit" name="logout" class="button">Logout</button>
        </form>
    </div>
</body>
</html>
