<?php

$message = '';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if ($_POST["realMethod"] == "delete") { // <- Bullshit detected 🚨🚨🚨
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

        $imgSrcToDelete = null;

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $imgSrcToDelete = $row["imgSrc"];

            if ($imgSrcToDelete && file_exists($imgSrcToDelete)) {
                if (unlink($imgSrcToDelete)) {
                    $message .= " Plik obrazu usunięty.";
                } else {
                    $message .= " Błąd podczas usuwania pliku: " . error_get_last()['message'];
                }
            } elseif ($imgSrcToDelete) {
                $message .= " Plik obrazu nie istnieje.";
            }
        } else {
            $message .= "Nie znaleziono posta o podanym ID: " . $id;
        }

        if ($result && $result->num_rows > 0) {
            $sql_delete = "DELETE FROM posts WHERE id = $id";
            if ($conn->query($sql_delete) === TRUE) {
                $message .= " Post was deleted";
                header("Location: /posts/");
                die();
            } else {
                $message .= " Błąd podczas usuwania posta z bazy danych: " . $conn->error;
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

        $sql = "UPDATE posts SET title = '$newTitle' WHERE id = $id";
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
            if (!isset($_COOKIE["name"])) {
                echo '
            <a href="login.html">Login</a>';
            } else {
                echo '<a href="./addPost.php">Add post</a>';
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
                while ($row = $result->fetch_assoc()) {
                    $id = $row["id"];
                    $title = $row["title"];
                    $imgSrc = $row["imgSrc"];
                    $author = $row["author"];


                    echo "<div class='post'> <h1>$title</h1>";

                    if (isset($imgSrc)) {
                        echo "<img src='$imgSrc'>";
                    }

                    if (isset($_COOKIE["name"])) {
                        if ($_COOKIE["name"] == $author) {
                            echo "<form method='post'>
                            <input type='hidden' name='realMethod' value='delete'>
                            <input type='hidden' name='post' value='$id'>
                            <button> Delete </button>
                            </form>"; // <- Jebane gówno detected
            
                            echo "<form method='post'>
                            <input type='hidden' name='realMethod' value='put'>
                            <input type='hidden' name='post' value='$id'>
                            <label for='newTitle'>
                                New Title:
                                <input type='text' name='newTitle'>
                            </label>
                            <button> Modify </button>
                            </form>"; // <- Jebane gówno detected
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