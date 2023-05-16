<!DOCTYPE HTML>
<html>
<title>Metropolitana - Login</title>
<head>
    <?php
        $idUtente = NULL;
        if(isset($_SESSION["idUtente"])) {
            $idUtente = $_SESSION["idUtente"];
        } else {
            header('Location:login.php');
        }

    ?>

    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Flogodownload.org%2Fwp-content%2Fuploads%2F2017%2F03%2Fas-roma-logo-0-1536x1536.png">

    <!-- Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!--Import jQuery before materialize.js-->
     <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>

    <!-- Compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
            
</head>
<body class="dark">

</body>
</html>