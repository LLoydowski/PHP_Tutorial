<?php

$message = ''; // Creates variable containing message

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if ($_POST["realMethod"] == "delete") { // Checks what method was really used (because PHP doesnt support more), it was set in one of the hidden inputs
        $id = $_POST["post"];


        $servername = "localhost";
        $username = "postsApp";
        $db_password = "1234";
        $db = "posts";

        $conn = new mysqli($servername, $username, $db_password, $db);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql_select = "SELECT imgSrc FROM posts WHERE id = $id LIMIT 1";
        $result = $conn->query($sql_select);

        $imgSrcToDelete = null; // Creates variable storing path to the image if one exists (now is blank)

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $imgSrcToDelete = $row["imgSrc"]; // Setting the variable with path to the image

            if ($imgSrcToDelete && file_exists($imgSrcToDelete)) { // Checking if the file exists
                if (unlink($imgSrcToDelete)) { // Removing the file
                    $message .= "File deleted.";
                } else {
                    $message .= "Error deleting file: " . error_get_last()['message']; // Handling errors
                }
            } elseif ($imgSrcToDelete) {
                $message .= "File doesn't exist.";
            }
        } else {
            $message .= "Image of this id doesn't exist: " . $id;
        }

        if ($result && $result->num_rows > 0) { // Removing the record from database
            $sql_delete = "DELETE FROM posts WHERE id = $id";
            if ($conn->query($sql_delete) === TRUE) {
                $message .= " Post was deleted";
                header("Location: /posts/");
                die();
            } else {
                $message .= "Error removing post from databse: " . $conn->error;
                $message .= "<a href='posts/'>Go back</a>";
            }
        }

        $conn->close();
    }
    if ($_POST["realMethod"] == "put") {
        $id = $_POST["post"];
        $newTitle = $_POST["newTitle"];


        $servername = "localhost";
        $username = "postsApp";
        $db_password = "1234";
        $db = "posts";

        $conn = new mysqli($servername, $username, $db_password, $db);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "UPDATE posts SET title = '$newTitle' WHERE id = $id"; // This query modifies the data
        if ($conn->query($sql) === TRUE) {
            $message .= "New user added succesfully";
            header("Location: /posts/");
            die();
        } else {
            $message .= "Error: " . $sql . "<br>" . $conn->error;
            $message .= "<a href='posts/login.html'>Go back</a>";
        }

    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <style>
        .post {
            max-width: 10vw;
        }

        .post img {
            width: 100%;
        }
    </style>
</head>

<body>

    <div class="container">
        <nav>
            <?php
            if (!isset($_COOKIE["name"])) { // Checks if user is logged in
                echo '
            <a href="login.html">Login</a>'; // If not display link to login page
            } else {
                echo '<a href="./addPost.php">Add post</a>'; // Otherwise displays link to add post page.
            }
            ?>
            <form method="post">
                <label>
                    Search:
                    <input required type="text" name="search">
                </label>
                <button type="submit">Search</button>
            </form>
            <?php

            echo $message;

            ?>
        </nav>
        <main>
            <?php
            $servername = "localhost";
            $username = "postsApp";
            $db_password = "1234";
            $db = "posts";

            $conn = new mysqli($servername, $username, $db_password, $db);
            $sql = "SELECT * from posts";

            if ($_SERVER['REQUEST_METHOD'] == "POST") {
                $querry = $_POST["search"];

                $sql = "SELECT * from posts WHERE title LIKE '%$querry%'";
            }

            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) { // Iterates through every posts and displays it
                    $id = $row["id"];
                    $title = $row["title"];
                    $imgSrc = $row["imgSrc"];
                    $author = $row["author"];


                    echo "<div class='post'> <h1>$title</h1>";

                    if (isset($imgSrc)) {
                        echo "<img src='$imgSrc'>";
                    }

                    if (isset($_COOKIE["name"])) {
                        if ($_COOKIE["name"] == $author) { // If the author is the user creates delete and modify buttons
                            echo "<form method='post'>
                            <input type='hidden' name='realMethod' value='delete'>
                            <input type='hidden' name='post' value='$id'>
                            <button> Delete </button>
                            </form>";

                            echo "<form method='post'>
                            <input type='hidden' name='realMethod' value='put'>
                            <input type='hidden' name='post' value='$id'>
                            <label for='newTitle'>
                                New Title:
                                <input type='text' name='newTitle'>
                            </label>
                            <button> Modify </button>
                            </form>";
                        }
                    }

                    echo "</div>";
                }
            }

            ?>
        </main>
    </div>
</body>

</html>