<!-- TODO handle logout logic here -->
<?php
//Unset cookie and session data
session_start();
unset($_SESSION["Username"]);
setcookie('Username', '', time() - 3600, '/');
header("Location:login.php");
?>