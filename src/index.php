<?php 
    session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>My PHP Web Page</title>
    <link rel="stylesheet" type="text/css" href="styles/styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="login-page">
        <?php
        // Your PHP code goes here
        $database = 'db'; // database service from docker compose
        $databasename = getenv('DATABASE'); // database name
        $user = getenv('USER'); // user name    
        $rootpassword = getenv('ROOT_PASSWORD'); // root password
        $connection = new mysqli($database, $user, $rootpassword, $databasename); // create connection to database


        // Check connection to the database
        if ($connection->connect_error) {
            die("Connection failed: " . $connection->connect_error);
        }

        // Check if the user is already logged in
        if (isset($_SESSION['username'])) {
            echo "<script>window.location.href='userpage.php';</script>"; // Redirect to user page
            exit();
        }

        // for logging in to the web application
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];

            // check if username already exists within database
            $sql = "SELECT * FROM users WHERE username='$username'";
            $result = $connection->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                if (password_verify($password, $row['password'])) {
                    $_SESSION['username'] = $username; // Store username of active user
                    echo "<script>window.location.href='userpage.php';</script>";
                    exit();
                } else{
                    echo "<script>alert('Invalid username or password');</script>";
                    echo "<script>window.location.href='index.php';</script>";
                    exit();
                }
            } else {
                echo "<script>alert('Invalid username or password');</script>";
                echo "<script>window.location.href='index.php';</script>";
                exit();
            }
        }

        // Handling form submission for registration
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
            $new_username = $_POST['new_username'];
            $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);

            // checks if the username already exists
            $check_sql = "SELECT * FROM users WHERE username='$new_username'";
            $check_result = $connection->query($check_sql);

            if ($check_result->num_rows > 0){ // if username already exists
                echo "<script>alert('Username already exists');</script>";
            }else{ // if username doesnt exist
                $sql = "INSERT INTO users (username, password) VALUES ('$new_username', '$new_password')"; // insert new user
                if ($connection->query($sql) === TRUE) { // it it was successful
                    echo "<script>alert('Registration Successful');</script>";
                    echo "<script>window.location.href='index.php';</script>";
                    exit();
                } else { // if it wasnt successful
                    echo "<script>alert('Could not register account');</script>";
                    echo "<script>window.location.href='index.php';</script>";
                    exit();
                }
            }
        }

        ?>
        <h1> SENG513 User Login/Signup Page</h1>
        <div class="login_stuff">
            <h2 id="login_header">Login</h2>
            <p id="login_text">Input your Username and Password below to login</p>
            <form class="form" method="post" action="">
                <input
                    id="login_username"
                    type="text"
                    name="username"
                    placeholder="username"
                    required
                />
                <input
                    id="login_password"
                    type="password"
                    name="password"
                    placeholder="password"
                    required
                />
                <div class="button-container">
                    <button class="button" id="login_button" type="submit" name="login">Login</button>
                </div>
            </form>
        </div>
        
        <div class="signup_stuff">
            <h2 id="signup_header"> Signup</h2>
            <p id="signup_text">Input a username and password below to signup</p>
            <form class="form" method="post" action="">
                <input
                    id="signup_username" 
                    type="text"
                    name="new_username"
                    placeholder="username"
                    required
                />
                <input
                    id="signup_password"
                    type="password"
                    name="new_password"
                    placeholder="password"
                    required
                />
                <div class="button-container">
                    <button class="button" type="submit" id="signup_button" name="register">signup</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
