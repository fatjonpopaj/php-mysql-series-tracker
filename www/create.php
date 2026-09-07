<!DOCTYPE html>

<?php
    setcookie('userID', $_COOKIE['userID']);
?>

<html lang="de">

    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="stylesheet" type="text/css" href="create.css">
        <title>Create Series</title>
    </head>

    <body>


        <?php
            if ($_SERVER["REQUEST_METHOD"] == "GET") {
                $userID = $_COOKIE['userID'];
            }
            elseif ($_SERVER["REQUEST_METHOD"] == "POST") {

                $title = $_POST['title'];
                $seasons = $_POST['seasons'];
                $genre = $_POST['genre'];
                $plattform = $_POST['plattform'];

                $dbServername = "localhost";
                $dbUsername = "root";
                $dbPassword = "";
                $dbName = "webtechDBGruppe20";
                
                $conn = new mysqli($dbServername, $dbUsername, $dbPassword, $dbName);
                
                if ($conn->connect_error) {
                    die("Verbindung fehlgeschlagen: " . $conn->connect_error);
                }

                $conn->autocommit(FALSE);

                $conn->begin_transaction();

                try {

                    $userID = $_COOKIE['userID'];

                    $seriesID = 0;

                    $sql = "SELECT seriesID FROM series WHERE (title='$title') AND (seasons='$seasons') AND (genre='$genre') AND (plattform='$plattform')";

                    $result = $conn->query($sql);
        
                    if ($result->num_rows > 0) {
                        echo "<p>Datesatz existiert bereits</p>";
                        $seriesID = $result->fetch_assoc()["seriesID"];
                    }
                    else {

                        $sql = "INSERT INTO series (title, seasons, genre, plattform) VALUES ('$title', '$seasons', '$genre', '$plattform')";

                        if ($conn->query($sql) == TRUE) {
                            echo "Neuer Datensatz erfolgreich erstellt.<br>";
                        } else {
                            echo "Fehler: " . $sql . "<br>" . $conn->error;
                        }

                        $sql = "SELECT seriesID FROM series WHERE (title='$title') AND (seasons='$seasons') AND (genre='$genre') AND (plattform='$plattform')";

                        $result = $conn->query($sql);
            
                        $seriesID = $result->fetch_assoc()["seriesID"];

                    }

                    $sql = "SELECT * FROM userSeries WHERE (seriesID='$seriesID') AND (userID='$userID')";

                    $result = $conn->query($sql);
        
                    if ($result->num_rows == 0) {

                        $sql = "INSERT INTO userSeries (userID, seriesID) VALUES ('$userID', '$seriesID')";

                        if ($conn->query($sql) == TRUE) {
                            echo "Neuer Datensatz erfolgreich erstellt 2.<br>";
                        } else {
                            echo "Fehler: " . $sql . "<br>" . $conn->error;
                        }
                    }

                    $conn->commit();

                    header("Location: list.php");

                } catch (mysqli_sql_exception $exception) {

                    echo "Fehler: <br>" . $conn->error;

                    $conn->rollback();
                
                    throw $exception;
                }
                
                $conn->close();
            }
        ?>

<h1 class="titel">Neue Serie anlegen</h1>

<form action="create.php" method="POST">
    <div class="form-row">
        <label for="title">Titel:</label>
        <input type="text" name="title" id="title" required>
    </div>
    <div class="form-row">
        <label for="seasons">Anzahl der Staffeln:</label>
        <input type="number" min="0" name="seasons" id="seasons" required>
    </div>
    <div class="form-row">
        <label for="genre">Genre:</label>
        <input type="text" name="genre" id="genre" required>
    </div>
    <div class="form-row">
        <label for="plattform">Streaming-Plattform:</label>
        <input type="text" name="plattform" id="plattform" required>
    </div>
    <div class="form-row">
        <input type="submit" value="Submit">
    </div>
    <div class="form-row">
        <input type="reset" value="Clear">
    </div>
</form>

<a href="list.php" method="GET">
<button class="exit">Exit</button>
        </a>
</html>
