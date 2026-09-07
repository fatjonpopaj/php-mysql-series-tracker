
<!DOCTYPE html>

<?php
    setcookie('userID', $_COOKIE['userID']);
?>
    
<html lang="de">

    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta charset="UTF-8">
        <title>Login</title>
        <link rel="stylesheet" type="text/css" href="list.css">
    </head>

    <body>

        <form class="Filter" action="list.php" method="POST">
            <b class="filterTitel" >Titel</b>
            <input type="text" name="title">
            <b class="filterGenre" >Genre</b>
            <input type="text" name="genre">
            <b class="filterPlattfom" >Plattform</b>
            <input type="text" name="plattform">
            <button class="logOut">filter</button>
        </form>

        <table class="liste">
            <tr>
                <th class="listeTitel">Titel</th>
                <th class="listeStaffeln">Anzahl der Staffeln</th>
                <th class="listeGenre">Genre</th>
                <th class="listePlattform">Plattform</th>
                <th class="neuerEintrag">
                    <a href="create.php" method="GET">
                        <button>+</button>
                    </a>
                </th>
            </tr>
            <?php
                $title = "";
                $genre = "";
                $plattform = "";
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $title = $_POST['title'];
                    $genre = $_POST['genre'];
                    $plattform = $_POST['plattform'];
                }

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
                    $result = $conn->query("SELECT * FROM series s JOIN userseries u ON (s.seriesID = u.seriesID) WHERE u.userID = \"".$_COOKIE['userID']."\" AND title LIKE \"".$title."%\" AND genre LIKE \"".$genre."%\" AND plattform LIKE \"".$plattform."%\"");
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr><td>".$row["title"]."</td>";
                            echo "<td>".$row["seasons"]."</td>";
                            echo "<td>".$row["genre"]."</td>";
                            echo "<td>".$row["plattform"]."</td></tr>";
                        }
                    }
                    $conn->commit();
                } catch (mysqli_sql_exception $exception) {
                    echo "Fehler: <br>" . $conn->error;
                    $conn->rollback();
                    throw $exception;
                }
                $conn->close();
            ?>
        </table>
        
        <a href="login.php" method="GET">
                <button class="logOut">Log out</button>
        </a>

    </body>
</html>
