<!DOCTYPE HTML>
<html>
<title>Metropolitana</title>

<head>
    <?php
    session_start();
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
                <a href="#" class="brand-logo"><img class="logo-img" src="https://i.imgur.com/oJTV2bZ.png"
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
    <!-- Container for form and map -->
    <div id="form-container">
        <form>
            <div class="input-field">
                <i class="material-icons prefix">location_on</i>
                <select>
                    <option value="" disabled selected>Scegli un'opzione</option>
                    <?php
                    $query = "SELECT * FROM stazione";

                    $result = $conn->execute_query($query);
                    $stazioni = $result->fetch_all();
                    foreach ($stazioni as $staz) {
                        echo '<option value="' . $staz[0] . '">' . $staz[0] . '</option>';
                    }
                    ?>
                </select>
                <label>Da</label>
            </div>
            <div class="input-field">
                <i class="material-icons prefix">where_to_vote</i>
                <select>
                    <option value="" disabled selected>Scegli un'opzione</option>
                    <?php
                    foreach ($stazioni as $staz) {
                        echo '<option value="' . $staz[0] . '">' . $staz[0] . '</option>';
                    }
                    ?>
                </select>
                <label>A</label>
            </div>
            <div class="input-field">
                <i class="material-icons prefix">schedule</i>
                <input id="timepicker" type="text" class="timepicker" value="00:00">
                <label>Data</label>
            </div>
            <div class="input-field">
                <i class="material-icons prefix">assignment_ind</i>
                <input id="cf" type="text" class="validate" name="cf" required>
                <label for="cf">Codice Fiscale</label>
            </div>
            <div class="input-field">
                <i class="material-icons prefix">123</i>
                <input id="age" type="number" class="validate" name="age" min="18" required>
                <label for="age">Età</label>
            </div>
            <div class="input-field">
                <i class="material-icons prefix">work</i>
                <input id="job" type="text" class="validate" name="job">
                <label for="job">Professione</label>
            </div>
            <div class="input-field">
                <i class="material-icons prefix">lock</i>
                <input id="password" type="password" class="validate" name="password">
                <label for="password">Password</label>
            </div>
            <div class="input-field">
                <i class="material-icons prefix">lock</i>
                <input id="password2" type="password" class="validate" name="password2">
                <label for="password2">Conferma Password</label>
            </div>
            <div class="col s12 m12 l12">
                <label>
                    <input type="checkbox" class="filled-in" checked="checked" name="remember" value="yes" />
                    <span>Ricordami</span>
                </label>
            </div>
            <br>
            <a style="border-radius: 15px" class="waves-effect waves-red btn-flat" href="login.php">Fai
                il Login
                <i class="material-icons right">login</i>
            </a>
            <br>
            <br>
            <button style="border-radius: 15px" class="btn waves-effect waves-light" type="submit"
                name="action">Registrati
                <i class="material-icons right">person_add</i>
            </button>

        </form>
    </div>
    <div id="map">

    </div>


    <!-- Your custom JS -->
    <script>
        // Replace these coordinates with the desired location
        //TODO replace with location of station
        const defaultCenter = [41.8905358, 12.4742367]; // Rome
        const defaultZoom = 20; // Choose the appropriate zoom level

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

        var marker = L.marker([41.8905358, 12.4742367], {
            label: "Prova",
            labelOptions: {
                noHide: true,
                textSize: "16px"
            }
        }).addTo(map);
        marker.bindPopup("<b>Hello world!</b><br>I am a popup.").openPopup();
        var latlngs = [
            [45.51, -122.68],
            [37.77, -122.43],
            [34.04, -118.2]
        ];

        var polyline = L.polyline(latlngs, { color: 'red' }).addTo(map);

        // zoom the map to the polyline
        map.fitBounds(polyline.getBounds());

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

    </script>
    <!-- Change primary color -->
    <script src="../scripts/changeColor.js"></script>
</body>

</html>