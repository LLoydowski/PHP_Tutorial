<?php
$message = "";
if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $title = $_POST['title'];


    $servername = "localhost";
    $username = "postsApp";
    $db_password = "1234";
    $db = "posts";

    $conn = new mysqli($servername, $username, $db_password, $db);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if (!isset($_COOKIE["name"])) { // Checks if user is logged in by checking his cookies
        header("Location: /login.html/"); // Redirects the user
        die();
    }

    $name = $_COOKIE["name"];

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) { // Checks if the file was uploaded
        $imageFile = $_FILES['image']; // Gets the file
        $uploadDir = 'imgs/'; // Storest directory with files
        $fileName = time() . '_' . $imageFile['name']; // Creates new filename for the file
        $targetFilePath = $uploadDir . $fileName; // Connects imgs and filename

        if (move_uploaded_file($imageFile['tmp_name'], $targetFilePath)) { // Moves the file to imgs directory
            $sql = "INSERT INTO posts (title, imgSrc, author) VALUES ('$title', '$targetFilePath', '$name')"; // Inserts Title, path and author to the posts table
            if (!$conn->query($sql)) {
                $message = "Error: " . $sql . "<br>" . $conn->error;
            } else {
                $message = "File added succesfully";
            }
        } else {
            $message = "Error while uploading the file";
        }
    } else {
        $sql = "INSERT INTO posts (title, imgSrc, author) VALUES ('$title', NULL, '$name')"; // Also inserts title and author but without image.
        if (!$conn->query($sql)) {
            $message = "Error: " . $sql . "<br>" . $conn->error;
        } else {
            $message = "File added succesfully";
        }
    }

    $conn->close();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <nav>
        <a href="/posts">Go back</a>
    </nav>
    <form method="post" enctype="multipart/form-data">
        <h1>Add post</h1>
        <?php
        echo "<p>$message</p>";
        ?>
        <label for="title">
            Title:
            <input type="text" name="title" required />
        </label>
        <label for="image">
            Image:
            <input type="file" name="image" id="image" />
        </label>
        <br>
        <button type="submit">Add Post</button>
    </form>
</body>

</html>