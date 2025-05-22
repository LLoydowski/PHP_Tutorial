<?php

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $name = $_POST["name"];
    $password = $_POST["password"];

    $servername = "localhost";
    $username = "postsApp";
    $db_password = "1234";
    $db = "myapp";

    $conn = new mysqli($servername, $username, $db_password, $db);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT name FROM users WHERE name = '$name' and password = '$password' LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        setcookie("name", $name);
        header("Location: /myApp/");
        die();
    } else {
        echo "Wrong name or password <a href='myApp/login.html'>Go back</a>";
    }

    $conn->close();
}
?>