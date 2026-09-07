<!DOCTYPE html>

<?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            setcookie('userID', 1);
        }
?>

<html lang="de">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="login.css">
</head>

<body>
    <h1 class="titel">Steam - Streaming Series Memory Steam</h1><br>

    <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $username = $_POST['username'];
            $password = $_POST['password'];

            $dbServername = "localhost";
            $dbUsername = "root";
            $dbPassword = "";
            $dbName = "webtechDBGruppe20";
            
            // Verbindung zur Datenbank erstellen
            $conn = new mysqli($dbServername, $dbUsername, $dbPassword, $dbName);
            
            // Verbindung überprüfen
            if ($conn->connect_error) {
                die("Verbindung fehlgeschlagen: " . $conn->connect_error);
            }

            $conn->autocommit(FALSE);

            $conn->begin_transaction();

            try {

            $sql = "SELECT userID FROM user WHERE (username = '$username') AND (password = '$password')";
            
            $result = $conn->query($sql);
        
            if ($result->num_rows == 0) {
                echo "<p class=\"error\"> Ungültige Anmeldedaten. Versuchen Sie es noch einmal! </p>";
                $conn->commit();

            } else {

                setcookie('userID', $result->fetch_assoc()["userID"]);
                $conn->commit();
                header("Location: list.php");
            }
        }
        catch (mysqli_sql_exception $exception) {

            echo "Fehler: <br>" . $conn->error;

            $conn->rollback();
        
            throw $exception;
        }
            
        $conn->close();

        }
    ?>

    <form action="login.php" method="POST">

        <h1 class="gerahmt">Benutzername: <input type="text" name="username"></h1><br>
        <h1 class="gerahmt">Passwort: <input type="password" name="password"></h1><br>
        <br>
        <input type="submit" value="Login">
        <br>
        <br>
        <input type="reset" value="Clear">
    </form>
</body>
</html>
