<?php 
    session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>My PHP Web Page</title>
</head>
<body>
    <h1>Welcome to my PHP Web Page!</h1>
    
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
    echo "Connected successfully to the database.<br>";


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

    <div className="login-stuff">
        <h1> Login</h1>
        <h2></h2>
        <p className="login-text">Input your Username and Password Below</p>
        <form method="post" action="">
            <input
                type="text"
                name="username"
                placeholder="username"
                required
            />
            <input
                type="password"
                name="password"
                placeholder="password"
                required
            />
            <button type="submit" name="login">Login</button>
        </form>
    </div>

    <div className="signup-stuff">
        <h1> Signup</h1>
        <h2></h2>
        <p className="signup-text">Input a username and password below to signup</p>
        <form method="post" action="">
            <input
                type="text"
                name="new_username"
                placeholder="username"
                required
            />
            <input
                type="password"
                name="new_password"
                placeholder="password"
                required
            />
            <button type="submit" name="register">signup</button>
        </form>
    </div>
</body>
</html>
