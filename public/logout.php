<?php
session_start();
//If the user clicked delete account, delete it
if (isset($_POST["delete"])) {
    require '../src/dbObjects/utente.php';
    //Check if user is logged in and get the username
    $username = NULL;
    if (isset($_SESSION["Username"])) {
        $username = $_SESSION["Username"];
    } else if (isset($_COOKIE["Username"])) {
        $username = $_COOKIE["Username"];
    } else {
        header('Location:login.php');
    }

    //Start the connection
    $conn = mysqli_connect("127.0.0.1", "root", "", "metro");
    $conn->set_charset('utf8');

    //Get the user that will be later used 
    $query = "SELECT * FROM utente WHERE Username = ?";
    $result = $conn->execute_query($query, array($username));
    $row = $result->fetch_assoc();
    $utente = new Utente($row['idUtente'], $row['Nome'], $row['Cognome'], $row['CF'], $row['Eta'], $row['Professione'], $row['PasswordHash'], $row['Username']);

    //Delete first all of the prenotazione has transiti where the user has made a prenotazione
    $query = "SELECT idPrenotazione FROM prenotazione WHERE Utente_idUtente = ?";
    $result = $conn->execute_query($query, array($utente->idUtente));
    foreach ($result as $row) {
        $query = "DELETE FROM prenotazione_has_transiti WHERE Prenotazione_idPrenotazione = ?";
        $conn->execute_query($query, array($row['idPrenotazione']));

        //Delete then all of the prenotaziones associated with the user, otherwise the sql query to delete the user will fail because of foreign keys constraint
        $query = "DELETE FROM prenotazione WHERE idPrenotazione = ?";
        $conn->execute_query($query, array($row['idPrenotazione']));
    }

    //Finally delete the user
    $query = "DELETE FROM utente WHERE username = ?";
    $result = $conn->execute_query($query, array($username));
}
//Unset cookie and session data
unset($_SESSION["Username"]);
setcookie('Username', '', time() - 3600, '/');
header("Location:login.php");
?>