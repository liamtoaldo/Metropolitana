<?php
//If the user clicked delete account, delete it
if(isset($_POST["delete"])) {
    //TODO handle delete logic here
}
session_start();
//Unset cookie and session data
unset($_SESSION["Username"]);
setcookie('Username', '', time() - 3600, '/');
header("Location:login.php");
?>