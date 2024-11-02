<?php
session_start();
$admin = "admin";
// check if the user is logged in
if (!isset($_SESSION['username'])) {
    // if the admin is not logged in, move back to login page
    echo "<script>window.location.href='index.php';</script>";
    exit();
}

// Check if the logged-in user is the admin
if ($_SESSION['username'] !== $admin) {
    // If the user is not admin, redirect to the user page
    echo "<script>window.location.href='userpage.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Adminpage</title>
    <link rel="stylesheet" type="text/css" href="styles/styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="admin-page"> 
        <?php
        // Handling form submission for registration
        echo("<h1>Welcome Back, " . htmlspecialchars($_SESSION['username']) . "</h1>");
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['logout'])) {
            session_destroy();
            echo "<script>window.location.href='index.php';</script>";
            exit();
        }

        // Connect to the database below
        $database = 'db'; 
        $databasename = getenv('MYSQL_DATABASE'); // database name
        $user = getenv('MYSQL_USER');
        $rootpassword = getenv('MYSQL_ROOT_PASSWORD');
        $connection = new mysqli($database, $user, $rootpassword, $databasename);

        // Check connection
        if ($connection->connect_error) {
            die("Connection failed: " . $connection->connect_error);
        }

        $sql= "SELECT username FROM users where username != 'admin'";
        $result = $connection->query($sql);

        if ($result->num_rows > 0){
            $usernames = [];
            while ($row = $result->fetch_assoc()) {
                $usernames[] = $row['username'];
            }
        }
        ?>

        <h2>Delete a User from the Databse</h2>
        <form method="POST" action = "">
            <p>Select a user from the dropdown below:</p>
            <div class="select_container">
                <select name="delete_user" id="delete_user">
                    <?php foreach ($usernames as $username): ?>
                        <option value="<?php echo $username; ?>"><?php echo $username; ?></option>
                    <?php endforeach; ?>
                </select>
                <br>
                <div class="button-container">
                    <button type="submit" name="delete" id="delete_button" class="button">Delete User</button>
                </div>
            </div>
        </form>
        
        <form method="post" action="">
            <button type="submit" name="logout" class="button">Logout</button>
        </form>


        <?php
        // php for deleting the actual users from the database
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
            if (!isset($_POST['delete_user']) || empty($_POST['delete_user'])){
                echo "<script>alert('No user selected');</script>";
                exit();
            }else{
                $user_to_delete = $_POST['delete_user'];
                // ensure the selected user is not the admin
                if ($user_to_delete !== $admin) {
                    $delete_sql = "DELETE FROM users WHERE username = '$user_to_delete'";
                    if ($connection->query($delete_sql) === TRUE) {
                        echo "<script>alert('User deleted successfully');</script>";
                        echo "<script>window.location.href='adminpage.php';</script>";
                        exit();
                    } else {
                        echo "<script>alert('Error deleting user');</script>";
                    }
                } else {
                    echo "<script>alert('Admin cannot be deleted');</script>";
                }
            }
        }

        $connection->close();
        ?>
        
    </div>
</body>
</html>
