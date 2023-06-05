<!DOCTYPE HTML>
<html>
<title>Metropolitana</title>

<head>
    <?php
    //Start buffering, so that we can use headers "after" rendering html elements.
    ob_start();

    include '../src/dbObjects/stazione_passa_linea.php';
    include '../src/dbObjects/stazione.php';
    include '../src/dbObjects/transiti.php';
    include '../src/graph/grafo.php';
    include '../src/graph/arco.php';
    include '../src/graph/nodo.php';
    include '../src/graph/objectStorage.php';

    $conn = mysqli_connect("127.0.0.1", "root", "", "metro");
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
        /* Spinner styles */
        /* Hide the navbar by default */
        nav {
            display: none;
        }

        /* Show the navbar when the spinner is hidden */
        #loading-spinner:empty+nav {
            display: block;
        }

        #loading-spinner {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 9999;
            display: none;
            /* Comment if the spinner is needed */
        }

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

        /* Set height of map container to 100% */
        #map {
            height: calc(100% - 0px);
            position: absolute;
            width: 100%;
            z-index: -10;
            bottom: 0;
            top: 0;
        }

        /* Position form on top of map */
        #form-container {
            width: 25%;
            position: absolute;
            background-color: white;
            top: 52%;
            transform: translateY(-50%);
            left: 50px;
            z-index: 1;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
            opacity: 0.8;
        }

        .clockpicker-popover {
            top: 50% !important;
            left: 50% !important;
            transform: translate(-50%, -50%);
        }

        .custom-collection-item {
            padding-left: 16px !important;
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

    <script>
        var latLngs = [
        ];
        var popups = [
        ];
        //Function called from php
        function addStop(lat, lng, popupContent) {
            latLngs.push([lat, lng]);
            popups.push(popupContent);
        }
    </script>

</head>

<body>
    <?php
    if (isset($_GET["from"]) && isset($_GET["to"]) && isset($_GET["when"])) {
        ?>
        <div class="fixed-action-btn" id="fab-button">
            <a class="btn-floating btn-large red pulse" title="Conferma prenotazione">
                <i class="large material-icons">check</i>
            </a>
        </div>
        <?php
    }
    ?>
    <!-- Navbar -->
    <!-- Spinner element -->
    <div id="loading-spinner">
        <div class="preloader-wrapper big active">
            <div class="spinner-layer spinner-blue">
                <div class="circle-clipper left">
                    <div class="circle"></div>
                </div>
                <div class="gap-patch">
                    <div class="circle"></div>
                </div>
                <div class="circle-clipper right">
                    <div class="circle"></div>
                </div>
            </div>
        </div>
    </div>

    <nav>
        <div class="nav-wrapper">
            <div class="container">
                <a href="index.php" class="brand-logo"><img class="logo-img" src="https://i.imgur.com/oJTV2bZ.png"
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
    <!-- Container for form and map -->
    <div id="form-container">
        <?php
        //Set default zone to Rome, since it's rome subway
        date_default_timezone_set('Europe/Rome');

        /* CODICE TRADOTTO DA C# */
        $grafo = new Grafo();
        //Obtain just stations
        $query = "SELECT * FROM stazione";
        $result = $conn->execute_query($query);
        $stazioniArray = $result->fetch_all();
        $stazioni = [];

        $nodi;
        foreach ($stazioniArray as $staz) {
            //Fill teh stazioni array
            $stazioni[] = new Stazione($staz[0], $staz[1], $staz[2]);
            //Add nodes with stations to the graph
            $nodi[] = new Nodo(new Stazione($staz[0], $staz[1], $staz[2]));
        }

        if (isset($_SESSION["grafo"])) {
            $grafo = $_SESSION["grafo"];
        } else {
            $grafo->aggiungiNodi($nodi);
            $grafo = createGraph($grafo, $stazioni);
            $_SESSION["grafo"] = $grafo;
        }


        if (isset($_GET["from"]) && isset($_GET["to"]) && isset($_GET["when"])) {
            $from = $_GET["from"];
            $to = $_GET["to"];
            $when = $_GET["when"];

            //Show the floating action button
            ?>

            <?php
            //Find stations from array that match the ones chosen by the user
            $partenza = array_filter($stazioni, function ($staz) use ($from) {
                return $staz->Nome === $from;
            });
            $partenza = array_values($partenza);
            $arrivo = array_filter($stazioni, function ($staz) use ($to) {
                return $staz->Nome === $to;
            });
            $arrivo = array_values($arrivo);

            //Obtain the list of routes with Dijkstra
            $percorso = $grafo->dijkstra(new Nodo($partenza[0]), new Nodo($arrivo[0]));

            //See if the route was found
            $percorsoNonTrovato = count($percorso) == 0;

            //Calculate costs and timetables after having the routes
            $transiti = [];

            //Get time 
            $oraAttuale = $when;

            for ($i = 0; $i < count($percorso) - 1; $i++) {
                retry:
                $versoOpposto = false;
                //Find a transit with the first time possible
                $transito = getTransitiFromStazioni($percorso[$i]->Stazione->Nome, $percorso[$i + 1]->Stazione->Nome, $oraAttuale);
                if ($transito != null && !$versoOpposto) {
                    // Update the current time to find the train that is after the one
                    $oraAttuale = $transito->OraArrivo->format("H:i:s");
                    $transiti[] = $transito;
                } else {
                    // If no transit is found, it means there is one available the next day (e.g., the reservation is made in the evening)
                    $oraAttuale = (new DateTime("2000-01-01 00:00:00"))->format("H:i:s");
                    goto retry;
                }
            }

            $_SESSION["transiti"] = $transiti;

            ?>
            <ul class="collection" style="max-height: 700px; overflow-y: auto;">
                <?php
                if ($percorsoNonTrovato) {
                    ?>
                    <li class="collection-item avatar custom-collection-item" style="display: flex; align-items: center;">
                        <div>
                            <p><b>Percorso non trovato</b></p>
                        </div>
                    </li>
                    <!-- Also hide the fab button -->
                    <script>
                        function hideFabButton() {
                            var fabButton = document.getElementById("fab-button");
                            fabButton.style.display = "none";
                        }
                        hideFabButton();
                    </script>
                    <?php
                }
                $i = 1;
                foreach ($transiti as $transito) {
                    ?>
                    <li class="collection-item avatar custom-collection-item" style="display: flex; align-items: center;">
                        <div class="route-number" style="font-size: 24px; font-weight: bold; margin-right: 30px;">
                            <?php
                            echo $i . ".";
                            ?>
                        </div>
                        <div>
                            <?php
                            echo '<div><p>' . $transito->StazionePartenza . '<i class="material-icons" style="font-size: 18px;">arrow_forward</i>' . $transito->StazioneArrivo . '</p>';
                            echo '<p>' . $transito->OraPartenza->format("H:i:s") . '-' . $transito->OraArrivo->format("H:i:s") . '</p>';
                            ?>
                            <!-- <p>Stop 1 <i class="material-icons" style="font-size: 18px;">arrow_forward</i> Stop 2</p>
                            <p>10:00-10:30</p> -->
                        </div>
                    </li>
                    <?php
                    //Get the two stations which have the same name as the one in transito
                    $stop1 = array_filter($stazioni, function ($staz) use ($transito) {
                        return $staz->Nome === $transito->StazionePartenza;
                    });
                    $stop1 = array_values($stop1);
                    $stop2 = array_filter($stazioni, function ($staz) use ($transito) {
                        return $staz->Nome === $transito->StazioneArrivo;
                    });
                    $stop2 = array_values($stop2);

                    //Add the marker to the array of latitudes and longitudes that will be added later.
                    echo "<script>addStop(" . $stop1[0]->Latitudine . ", " . $stop1[0]->Longitudine . ", '<b>" . $stop1[0]->Nome . "</b>');</script>";
                    //Also add the last marker if it's the last value in array
                    if ($transito->StazioneArrivo == $to) {
                        echo "<script>addStop(" . $stop2[0]->Latitudine . ", " . $stop2[0]->Longitudine . ", '<b>" . $stop2[0]->Nome . "</b>');</script>";
                    }
                    $i++;
                }
                ?>
            </ul>


            <?php
        } else { ?>
            <span class="card-title">
                <h5><b>Calcola Percorso</b></h5>
            </span>
            <br>
            <form method="get" action="<?php echo $_SERVER["PHP_SELF"] ?>">
                <div class="input-field">
                    <i class="material-icons prefix">location_on</i>
                    <select name="from">
                        <option value="" disabled selected>Scegli un'opzione</option>
                        <?php
                        foreach ($stazioni as $staz) {
                            echo '<option value="' . $staz->Nome . '">' . $staz->Nome . '</option>';
                        }
                        ?>
                    </select>
                    <label>Da</label>
                </div>
                <div class="input-field">
                    <i class="material-icons prefix">where_to_vote</i>
                    <select name="to">
                        <option value="" disabled selected>Scegli un'opzione</option>
                        <?php
                        foreach ($stazioni as $staz) {
                            echo '<option value="' . $staz->Nome . '">' . $staz->Nome . '</option>';
                        }
                        ?>
                    </select>
                    <label>A</label>
                </div>
                <div class="input-field">
                    <i class="material-icons prefix">schedule</i>
                    <input name="when" id="timepicker" type="text" class="timepicker" value="<?php echo date("H:i"); ?>">
                    <label>Quando</label>
                </div>
                <button style="border-radius: 15px; width: 100%" class="btn waves-effect waves-light" type="submit"
                    name="action">Calcola
                    Percorso
                    <i class="material-icons right">route</i>
                </button>

            </form>
        <?php } ?>
    </div>
    <div id="map">

    </div>


    <!-- Your custom JS -->
    <script>
        console.log("prova");
        console.log(latLngs);
        // Replace these coordinates with the desired location
        var defaultCenter = [41.9102088, 12.3711917];
        var defaultZoom = 12; // Choose the appropriate zoom level
        if (latLngs.length > 0) {
            defaultCenter = latLngs[0]; // Rome
            defaultZoom = 14;
        }

        // Create a Leaflet map and add it to the "mapid" div
        var map = L.map('map', {
            center: defaultCenter,
            zoom: defaultZoom,
            zoomControl: false
        });

        // Add a tile layer to the map
        L.tileLayer('https://api.maptiler.com/maps/streets-v2/{z}/{x}/{y}.png?key=PXGDSOhr00eYHoUVbDwz', {
            attribution: '<a href="https://www.maptiler.com/copyright/" target="_blank">&copy; MapTiler</a> <a href="https://www.openstreetmap.org/copyright" target="_blank">&copy; OpenStreetMap contributors</a>'
        }).addTo(map);

        //Here are the icons for the map markers
        var greenIcon = L.icon({
            iconUrl: 'http://clipart-library.com/new_gallery/circle-clipart-72.png',
            iconSize: [20, 20],
            iconAnchor: [10, 10],
            popupAnchor: [0, -20]
        });

        var redIcon = L.icon({
            iconUrl: 'https://i.stack.imgur.com/Ybbvc.png',
            iconSize: [20, 20],
            iconAnchor: [10, 10],
            popupAnchor: [0, -20]
        });
        var customIcon = L.icon({
            iconUrl: '../images/marker.png',
            iconSize: [20, 20],
            iconAnchor: [10, 10],
            popupAnchor: [0, -20]
        });

        if (latLngs.length > 0) {
            // Create a polyline with white circles at each position
            var polyline = L.polyline(latLngs, { color: 'blue' });

            // Add the polyline to the map
            polyline.addTo(map);

            // zoom the map to the polyline
            map.fitBounds(polyline.getBounds());


            //Function called from php
            // Adding markers with custom icons at each position
            console.log("Adding markers with custom icons");
            console.log(latLngs);
            latLngs.forEach(function (latLng, index) {
                var mark = L.marker(latLng).setIcon(index === 0 ? greenIcon : (index === latLngs.length - 1 ? redIcon : customIcon))
                    .addTo(map);
                mark.bindPopup(popups[index]);
                if (index === 0) {
                    mark.openPopup();
                }
            });

        }

    </script>

    <script>
        //Initialize the dropdown trigger for account menu
        $('.dropdown-trigger').dropdown();
        //Initialize dropdown listener
        $(document).ready(function () {
            $('select').formSelect();
        });
        //Initialize time picker
        $(document).ready(function () {
            $('.timepicker').timepicker({
                default: 'now',
                twelvehour: false,
                donetext: 'OK',
                cleartext: 'Clear',
                canceltext: 'Cancel',
                autoclose: false,
                ampmclickable: true,
                aftershow: function () { },
                format: 'dd/MM/yyyy HH:mm' // Italian date format
            });
            $('#timepicker').timepicker({
                container: 'body',
                twelveHour: false,
                i18n: {
                    cancel: 'Annulla',
                    clear: 'Cancella',
                    done: 'OK'
                }
            });
        });

        document.querySelector('.btn-floating').addEventListener('click', function () {
            window.location.href = 'prenotazione.php';
        });

    </script>
    <!-- Change primary color -->
    <script src="../scripts/changeColor.js"></script>
</body>

</html>


<?php

//Function to create a graph who already has nodes
//Parameters: the graph and an array with all the stations inside the database to better query them to add them as nodes
function createGraph(Grafo $grafo, array $stazioni): Grafo
{
    global $conn;
    //Obtain the stazione with the respective lines
    $query = "SELECT * FROM stazione_passa_linea";
    $result = $conn->execute_query($query);
    $stazioniLineate = [];
    foreach ($result as $row) {
        $stazioniLineate[] = new Stazione_passa_linea($row['Stazione_Nome'], $row['Linea_Nome'], $row['Posizione']);
    }

    foreach ($stazioniLineate as $stl) {
        //get station from stazioniArray that has same name as stl->StazioneNome
        $st = array_filter($stazioni, function ($staz) use ($stl) {
            return $staz->Nome === $stl->StazioneNome;
        });
        $st = array_values($st);
        //Find the lines where this station is located
        $lineeDiSt = array_filter($stazioniLineate, function ($s) use ($stl) {
            return $s->StazioneNome === $stl->StazioneNome;
        });

        //Add the edges based on the adjacency of the stations
        foreach ($lineeDiSt as $stLinea) {
            //calculate the stations which have the same line as st and are in the position after the station
            //ES:
            // stazione | linea | posizione
            //	  1			a		0
            //	  2			a		1
            $stazioniLineaDopoSt = array_filter($stazioniLineate, function ($s) use ($stLinea) {
                return ($s->LineaNome == $stLinea->LineaNome) && ($s->Posizione == $stLinea->Posizione + 1);
            });
            //get array of stazione (nome, latitudine, longitudine) that matches the stazioneNome in stazioniLineaDopoSt
            $stazioniDopoSt = array_filter($stazioni, function ($s) use ($stazioniLineaDopoSt) {
                return array_filter($stazioniLineaDopoSt, function ($st) use ($s) {
                    return $st->StazioneNome == $s->Nome;
                });
            });
            $stazioniDopoSt = array_values($stazioniDopoSt);
            foreach ($stazioniDopoSt as $stazioneDopo) {
                $grafo->aggiungiArco(new Arco(new Nodo($st[0]), new Nodo($stazioneDopo), 1));
            }


            //calculate the stations which have the same line as st and are in the position before the station
            $stazioniLineaPrimaSt = array_filter($stazioniLineate, function ($s) use ($stLinea) {
                return ($s->LineaNome == $stLinea->LineaNome) && ($s->Posizione == $stLinea->Posizione - 1);
            });
            //get array of stazione (nome, latitudine, longitudine) that matches the stazioneNome in stazioniLineaPrimaSt
            $stazioniPrimaSt = array_filter($stazioni, function ($s) use ($stazioniLineaPrimaSt) {
                return array_filter($stazioniLineaPrimaSt, function ($st) use ($s) {
                    return $st->StazioneNome == $s->Nome;
                });
            });
            $stazioniPrimaSt = array_values($stazioniPrimaSt);
            foreach ($stazioniPrimaSt as $stazionePrima) {
                $grafo->aggiungiArco(new Arco(new Nodo($stazionePrima), new Nodo($st[0]), 1));
            }
        }

    }
    return $grafo;
}

function getTransitiFromStazioni(string $stPartenza, string $stArrivo, string $ora)
{
    global $conn;
    $stPartenza = str_replace("'", "''", $stPartenza);
    $stArrivo = str_replace("'", "''", $stArrivo);
    $subquery = "SELECT MIN(OraPartenza) FROM metro.transiti WHERE Stazione_Partenza = '$stPartenza' AND Stazione_Arrivo = '$stArrivo' AND OraPartenza >= '" . $ora . "'";
    $query = "SELECT * FROM metro.transiti WHERE Stazione_Partenza = '$stPartenza' AND Stazione_Arrivo = '$stArrivo' AND OraPartenza >='" . $ora . "' HAVING OraPartenza = ($subquery)";

    $result = $conn->execute_query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $transiti = new Transiti(
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
        return $transiti;
    }
    return null;
}



?>