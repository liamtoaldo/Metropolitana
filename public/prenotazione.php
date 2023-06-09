<!DOCTYPE HTML>
<html>
<title>Metropolitana</title>

<head>
    <?php
    include '../src/dbObjects/transiti.php';
    require '../src/config.php';

    //Set default zone to Rome, since it's rome subway
    date_default_timezone_set('Europe/Rome');

    $conn = mysqli_connect(Config::$DBUrl, Config::$DBUsername, Config::$DBPassword, Config::$DBName);
    $conn->set_charset('utf8');

    session_start();
    //Check if user is logged in
    $username = NULL;
    if (isset($_SESSION["Username"])) {
        $username = $_SESSION["Username"];
    } else if (isset($_COOKIE["Username"])) {
        //If user has a cookie, we check if he edited the username to act like he was another user, so we check through the password
        $username = $_COOKIE["Username"];
        $passwordHash = $_COOKIE["PasswordHash"];
        $query = "SELECT * FROM utente WHERE Username = ? AND PasswordHash = ?";
        $result = $conn->execute_query($query, array($username, $passwordHash));
        if ($result->num_rows == 0) {
            //The user did something malicious, so let's log him out and remove the cookies
            setcookie('Username', '', time() - 3600, '/');
            setcookie('PasswordHash', '', time() - 3600, '/');
            header('Location:login.php');
        }
    } else {
        header('Location:login.php');
    }


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

        #toast-container {
            top: auto !important;
            bottom: 7%;
            left: 50%;
            transform: translateX(-50%);
        }
    </style>

    <!-- Accents -->
    <meta charset="UTF-8">
    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="images/favicon.png">

    <!-- Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!--Import jQuery before materialize.js-->
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>

    <!-- Compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>


</head>

<body>
    <!-- Navbar -->
    <nav>
        <div class="nav-wrapper">
            <div class="container">
                <a href="index.php" class="brand-logo"><img class="logo-img" src="images/logo.png"
                        alt="Logo"></a>
                <ul id="nav-mobile" class="right hide-on-med-and-down">
                    <li><a href="prenotazioni.php"><i class="large material-icons">confirmation_number</i>
                            <p>Prenotazioni</p>
                        </a></li>
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
    } else if (isset($_SESSION["transiti"]) && isset($_SESSION["id_prenotazione"])) {
        //came from index but id prenotazione is still set in session
        unset($_SESSION["id_prenotazione"]);
        $transiti = $_SESSION["transiti"];
    } else if (isset($_SESSION["id_prenotazione"])) {
        //came refreshing the page, but originally came from index
        $idPren = $_SESSION["id_prenotazione"];
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
        $treni = [];
        $costoTotale = 0;
        $tempoPercorrenza = 0;
        foreach ($transiti as $transito) {
            $query = "SELECT * FROM treno INNER JOIN viaggio ON treno.idTreno = viaggio.Treno_idTreno WHERE viaggio.idViaggio = " . $transito->IdViaggio;
            $result = $conn->execute_query($query);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $transito->Treno = $row["idTreno"] . " " . $row["Marca"] . " " . $row["Modello"];
                //Add train to array for future calculation of changes
                if (!in_array($transito->Treno, $treni)) {
                    $treni[] = $transito->Treno;
                }
                //Calculate the increasing cost
                $costoTotale += $transito->Costo;
            }
        }
        //calculate changes, total cost, and number of stops and time needed
        $numCambi = count($treni);
        $numFermate = count($transiti);

        // Create DateTime objects from the OraPartenza and OraArrivo timestamps
        $partenza = DateTime::createFromFormat('Y-m-d H:i:s', $transiti[0]->OraPartenza->format('Y-m-d H:i:s'));
        $arrivo = DateTime::createFromFormat('Y-m-d H:i:s', $transiti[$numFermate - 1]->OraArrivo->format('Y-m-d H:i:s'));
        // Check if the OraArrivo timestamp is earlier than the OraPartenza timestamp
        if ($arrivo < $partenza) {
            $arrivo->add(new DateInterval('P1D')); // Add one day to the OraArrivo object
        }
        // Calculate the time difference between the two DateTime objects
        $diff = $partenza->diff($arrivo);
        // Calculate the total time in seconds
        $tempoPercorrenza += $diff->s + $diff->i * 60 + $diff->h * 3600;

        //Calculate the time this prenotation was created
        $datetimeString = date('d/m/Y H:i:s', time());
        $datetime = DateTime::createFromFormat('d/m/Y H:i:s', $datetimeString);

        // Output the date in the desired format
        $date = $datetime->format('d/m/Y');
        $time = $datetime->format('H:i:s');

        //User id
        $query = "SELECT idUtente FROM utente WHERE username = ?";
        $result = $conn->execute_query($query, array($username));
        $row = $result->fetch_assoc();
        $idUtente = $row['idUtente'];

        //add prenotation and also prenotation has transiti, so that we can then insert the correct id and date fields
        //Insert new prenotazione
        $query = "INSERT INTO prenotazione (DataPrenotazione, OraPartenza, OraArrivo, Costo, NumCambi, NumFermate, Promozione, TempoPercorrenza, Utente_idUtente)
        VALUES ('" . $datetime->format("Y-m-d H:i:s") . "', '" . $partenza->format("H:i:s") . "', '" . $arrivo->format("H:i:s") . "', $costoTotale, $numCambi, $numFermate, 0, $tempoPercorrenza, $idUtente)"; //TODO handle promozione
        $conn->execute_query($query);
        $idPren = $conn->insert_id;

        //Insert prenotazione has transiti
        $i = 1;
        foreach ($transiti as $transito) {
            $query = "INSERT INTO prenotazione_has_transiti 
            VALUES ($idPren, " . $transito->IdViaggio . ", '" . $transito->StazionePartenza . "', '" . $transito->StazioneArrivo . "', $i)";
            $conn->execute_query($query);
            $i++;
        }


        //Save the prenotation id in the session, so that when the user refreshes the page, we don't create a new prenotazione, but we still show the just created one
        $_SESSION["id_prenotazione"] = $idPren;
        ?>
        <div id="form-container">
            <center>
                <h4><b>ID Prenotazione:
                        <?php echo $idPren ?>
                    </b></h4>
                <h7 style="color: grey"><i>Creata il
                        <?php echo $date ?> alle
                        <?php echo $time ?>
                    </i></h7>
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
                        <b>Costo: </b>€
                        <?php echo $costoTotale ?>
                    </div>
                    <div class="col s3 info-item">
                        <b>Numero di Cambi:</b>
                        <?php echo $numCambi ?>
                    </div>
                    <div class="col s3 info-item">
                        <b>Numero di fermate totali:</b>
                        <?php echo $numFermate ?>
                    </div>
                    <div class="col s4 info-item">
                        <b>Tempo Impiegato:</b>
                        <?php echo $diff->h . "h " . $diff->i . "m"; ?>
                    </div>
                </div>
            </center>
        </div>
        <script>
            M.toast({ html: 'Prenotazione creata con successo!', classes: 'rounded' });
        </script>

        <?php
        unset($_SESSION["transiti"]);
    } else if ($idPren) {
        $query = "SELECT * FROM prenotazione WHERE idPrenotazione = ?";
        $result = $conn->execute_query($query, array($idPren));
        $prenotazione = $result->fetch_assoc();

        $datetime = DateTime::createFromFormat('Y-m-d H:i:s', $prenotazione["DataPrenotazione"]);

        // Output the date in the desired format
        $date = $datetime->format('d/m/Y');
        $time = $datetime->format('H:i:s');

        $transiti = getTransitiFromPrenotazione($idPren);

        //First get the used trains from the database for each of the trip
        foreach ($transiti as $transito) {
            $query = "SELECT * FROM treno INNER JOIN viaggio ON treno.idTreno = viaggio.Treno_idTreno WHERE viaggio.idViaggio = " . $transito->IdViaggio;
            $result = $conn->execute_query($query);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $transito->Treno = $row["idTreno"] . " " . $row["Marca"] . " " . $row["Modello"];
            }
        }
        //calculate changes, total cost, and number of stops and time needed
        $numCambi = $prenotazione["NumCambi"];
        $numFermate = $prenotazione["NumFermate"];

        // Create DateTime objects from the OraPartenza and OraArrivo timestamps
        $partenza = $prenotazione["OraPartenza"];
        $arrivo = $prenotazione["OraArrivo"];
        $tempoPercorrenza = $prenotazione["TempoPercorrenza"];
        $tempoImpiegato_string = gmdate("H:i:s", $tempoPercorrenza);
        $tempoImpiegato = DateTime::createFromFormat("H:i:s", $tempoImpiegato_string);
        $costoTotale = $prenotazione["Costo"];
        ?>
            <div id="form-container">
                <center>
                    <h4><b>ID Prenotazione:
                        <?php echo $idPren ?>
                        </b></h4>
                    <h7 style="color: grey"><i>Creata il
                        <?php echo $date ?> alle
                        <?php echo $time ?>
                        </i></h7>
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
                            <b>Costo: </b>€
                        <?php echo $costoTotale ?>
                        </div>
                        <div class="col s3 info-item">
                            <b>Numero di Cambi:</b>
                        <?php echo $numCambi ?>
                        </div>
                        <div class="col s3 info-item">
                            <b>Numero di fermate totali:</b>
                        <?php echo $numFermate ?>
                        </div>
                        <div class="col s4 info-item">
                            <b>Tempo Impiegato:</b>
                        <?php echo $tempoImpiegato->format("H") . "h " . $tempoImpiegato->format("i") . "m"; ?>
                        </div>
                    </div>
                </center>
            </div>
        <?php
    }
    ?>

    <!-- Change primary color -->
    <script src="scripts/changeColor.js"></script>
    <script>
        //Initialize the dropdown trigger for account menu
        $('.dropdown-trigger').dropdown();
    </script>
</body>

</html>
<?php
function getTransitiFromPrenotazione(int $idPrenotazione): array
{
    global $conn;
    $query = "SELECT t.*, PosizioneInPrenotazione FROM transiti t INNER JOIN prenotazione_has_transiti pt ON t.Viaggio_idViaggio = pt.Transiti_Viaggio_idViaggio AND t.Stazione_Partenza = pt.Transiti_Stazione_Partenza AND t.Stazione_Arrivo = pt.Transiti_Stazione_Arrivo WHERE Prenotazione_idPrenotazione = ? ORDER BY PosizioneInPrenotazione";
    $transiti = [];

    $result = $conn->execute_query($query, array($idPrenotazione));

    if ($result->num_rows > 0) {
        foreach ($result as $row) {
            $transiti[] = new Transiti(
                $row['Viaggio_idViaggio'],
                $row['Stazione_Partenza'],
                $row['Stazione_Arrivo'],
                $row['InizioViaggio'],
                $row['FineViaggio'],
                new DateTime($row['OraPartenza']),
                new DateTime($row['OraArrivo']),
                $row['PosizioneNelViaggio'],
                $row['CostoTransito']
            );
        }
    }
    return $transiti;
}
?>