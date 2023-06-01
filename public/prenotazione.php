<!DOCTYPE HTML>
<html>
<title>Metropolitana</title>

<head>
    <?php
    include '../src/dbObjects/transiti.php';

    session_start();
    //Check if user is logged in
    $username = NULL;
    if (isset($_SESSION["Username"])) {
        $username = $_SESSION["Username"];
    } else if (isset($_COOKIE["Username"])) {
        $username = $_COOKIE["Username"];
    } else {
        header('Location:login.php');
    }


    $conn = mysqli_connect("127.0.0.1", "root", "", "metro");
    $conn->set_charset('utf8');

    ?>
    <style>
        .logo-img {
            max-height: 64px;
            padding: 15px 0;
            display: inline-block;
        }

        nav ul li a {
            display: flex;
            align-items: center;
            font-size: 18px;
        }

        nav ul li a i {
            margin-right: 5px;
        }

        nav ul li a p {
            margin: 0;
        }


        /* Increase the font size of the logo */
        .brand-logo {
            font-size: 24px;
        }

        /* Hide the page content by default */
        #page-content {
            display: none;
        }

        #dropdown1 li {
            white-space: nowrap;
        }

        #dropdown1 li>a {
            width: auto !important;
            display: block;
            padding: 12px 16px;
            white-space: nowrap;
        }

        .dropdown-trigger {
            width: auto;
        }

        #not-allowed-message {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 36px;
            color: black;
            text-align: center;
        }


        .card-content {
            width: 100%;
            text-align: center;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .divider {
            margin: 10px 0;
        }

        .list-unstyled {
            margin-bottom: 20px;
            padding-left: 0;
        }

        .row {
            display: flex;
            justify-content: center;
            width: 100%;
        }

        /* Position form on top of map */
        #form-container {
            width: 70%;
            position: absolute;
            background-color: white;
            top: 54%;
            transform: translateY(-50%);
            left: 50px;
            z-index: 1;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
            margin: 0px auto;
            left: 0;
            right: 0;

        }

        tbody {
            display: block;
            height: 450px;
            /* Set the desired height */
            overflow-y: auto;
            /* Enable vertical scrolling */
        }

        thead,
        tbody tr {
            display: table;
            width: 100%;
            table-layout: fixed;
        }


        table {
            width: 100%;
        }

        .info-item {
            font-size: 18px;
        }
    </style>

    <!-- Accents -->
    <meta charset="UTF-8">
    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../images/favicon.png">

    <!-- Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!--Import jQuery before materialize.js-->
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>

    <!-- Compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>


    <!-- Leaflet CSS & JavaScript -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

</head>

<body>
    <!-- Navbar -->
    <nav>
        <div class="nav-wrapper">
            <div class="container">
                <a href="index.php" class="brand-logo"><img class="logo-img" src="https://i.imgur.com/oJTV2bZ.png"
                        alt="Logo"></a>
                <ul id="nav-mobile" class="right hide-on-med-and-down">
                    <li><a href="prenotazioni.php"><i class="large material-icons">confirmation_number</i>
                            <p>Prenotazioni</p>
                        </a></li>
                    <li><a href="page2.html">Page 2</a></li>
                    <li>
                        <a class="dropdown-trigger" href="#" data-target='dropdown1'><i
                                class="large material-icons">person</i>
                            <p>Account</p>
                        </a>
                        <!-- Dropdown Structure -->
                        <ul id='dropdown1' class='dropdown-content'>
                            <li><a href="account.php">Impostazioni</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li><a href="logout.php"><i class="material-icons">logout</i>Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <?php
    //Check if user came from index.php after having confirmed a prenotation or if he came from prenotazioni.php, or if he came writing the file.
    $idPren = null;
    $transiti = null;
    if (isset($_POST["id_prenotazione"])) {
        //came from prenotazioni
        $idPren = $_POST["id_prenotazione"];
    } else if (isset($_SESSION["transiti"])) {
        //came from index
        $transiti = $_SESSION["transiti"];
    } else {
        //came writing the file in the tab
        //write large black text in center of screen saying: not allowed
        echo '<div id="not-allowed-message"><b>Non consentito:</b><br>Crea prima una prenotazione o leggine una.</div>';
    }


    if ($transiti) {
        //First get the used trains from the database for each of the trip
        foreach ($transiti as $transito) {
            $query = "SELECT * FROM treno INNER JOIN viaggio ON treno.idTreno = viaggio.Treno_idTreno WHERE viaggio.idViaggio = " . $transito->IdViaggio;
            $result = $conn->execute_query($query);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $transito->Treno = $row["idTreno"] . " " . $row["Marca"] . " " . $row["Modello"];
            }
        }
        //TODO add prenotation and also prenotation has transiti, so that we can then insert the correct id and date fields
        //TODO calculate changes, total cost, and number of stops
        ?>
        <div id="form-container">
            <center>
                <h4><b>ID Prenotazione: 0</b></h4>
                <h7 style="color: grey"><i>Creata il 17/04/2022 alle 10:56:44</i></h7>
                <div class="divider"></div>
                <table class="responsive" style="max-height: 700px; overflow-y: auto;">
                    <thead>
                        <tr>
                            <th>Ora Partenza</th>
                            <th>Ora Arrivo</th>
                            <th>Stazione Partenza</th>
                            <th>Stazione Arrivo</th>
                            <th>Treno</th>
                        </tr>
                    <tbody>
                        <?php
                        foreach ($transiti as $transito) {
                            echo "<tr><td>" . $transito->OraPartenza->format("H:i") . "</td><td>" . $transito->OraArrivo->format("H:i") . "</td><td>" . $transito->StazionePartenza . "</td><td>" . $transito->StazioneArrivo . "</td><td>" . $transito->Treno . "</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <br>
                <br>
                <div class="row">
                    <div class="col s3 info-item">
                        <b>Costo:</b> €40,34
                    </div>
                    <div class="col s3 info-item">
                        <b>Numero di Cambi:</b> 4
                    </div>
                    <div class="col s3 info-item">
                        <b>Numero di fermate totali:</b> 16
                    </div>
                    <div class="col s4 info-item">
                        <b>Tempo Impiegato:</b> 4h 30m
                    </div>
                </div>
            </center>

            <?php
    }
    ?>

        </script>
        <!-- Change primary color -->
        <script src="../scripts/changeColor.js"></script>
</body>

</html>