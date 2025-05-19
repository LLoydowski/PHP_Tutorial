<?php
$message = "";
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    try {
        $title = $_POST['title'];
    } catch (Exception $e) {
        die("Error: " . $e);
    }

    $servername = "localhost";
    $username = "postsApp";
    $db_password = "1234";
    $db = "posts";

    $conn = new mysqli($servername, $username, $db_password, $db);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if (!isset($_COOKIE["name"])) {
        header("Location: /login.html/");
        die();
    }

    $name = $_COOKIE["name"];

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageFile = $_FILES['image'];
        $uploadDir = 'imgs/';
        $fileName = time() . '_' . $imageFile['name'];
        $targetFilePath = $uploadDir . $fileName;

        if (move_uploaded_file($imageFile['tmp_name'], $targetFilePath)) {
            $sql = "INSERT INTO posts (title, imgSrc, author) VALUES ('$title', '$targetFilePath', '$name')";
            if (!$conn->query($sql)) {
                $message = "Error: " . $sql . "<br>" . $conn->error;
            } else {
                $message = "File added succesfully";
            }
        } else {
            $message = "Error while uploading the file";
        }
    } else {
        $sql = "INSERT INTO posts (title, imgSrc, author) VALUES ('$title', NULL, '$name')";
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