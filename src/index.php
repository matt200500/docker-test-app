<!DOCTYPE html>
<html>
<head>
    <title>My PHP Web Page</title>
</head>
<body>
    <h1>Welcome to my PHP Web Page!</h1>
    
    <?php
    // Your PHP code goes here
    echo "Hello, world!";
    $database = 'db'; // database service from docker compose
    $databasename = getenv('DATABASE'); // database name
    $user = getenv('USER'); // user name    
    $rootpassword = getenv('ROOT_PASSWORD'); // root password
    $connection = new mysqli($database, $user, $rootpassword, $databasename);


    // connecting to the database
    if (!$databasename || !$user || !$rootpassword) {
        echo "Environment variables are not set.";
    } else {
        // Create a new mysqli connection
        $connection = new mysqli($database, $user, $rootpassword, $databasename);
        
        // Check connection to the database
        if ($connection->connect_error) {
            die("Connection failed: " . $connection->connect_error);
        }
        echo "Connected successfully to the database.";
    }


    

    ?>
    
</body>
</html>
