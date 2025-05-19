<?php

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $name = $_POST["name"];
    $password = $_POST["password"];

    $servername = "localhost";
    $username = "postsApp";
    $db_password = "1234";
    $db = "posts";

    $conn = new mysqli($servername, $username, $db_password, $db);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO users(name, password) VALUES ('$name', '$password')";

    if ($conn->query($sql) === TRUE) {
        echo "New user added succesfully";
        setcookie("name", $name);

        header("Location: /posts/");
        die();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
        echo "<a href='posts/login.html'>Go back</a>";
    }

    $conn->close();
}
?>