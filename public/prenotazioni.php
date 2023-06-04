<!DOCTYPE HTML>
<html>
<title>Metropolitana - Prenotazioni</title>

<head>
    <?php
    require '../src/dbObjects/utente.php';

    //Set default zone to Rome, since it's rome subway
    date_default_timezone_set('Europe/Rome');

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

    //Get the user that will be later used 
    $query = "SELECT * FROM utente WHERE Username = ?";
    $result = $conn->execute_query($query, array($username));
    $row = $result->fetch_assoc();
    $utente = new Utente($row['idUtente'], $row['Nome'], $row['Cognome'], $row['CF'], $row['Eta'], $row['Professione'], $row['PasswordHash'], $row['Username']);

    //if delete is set, that means that the user has chosen to delete the prenotazione
    if (isset($_POST["delete"])) {
        $idPrenotazione = $_POST["id_prenotazione"];
        //We first check if the user has made any malicious manipulation to delete someone else's prenotazione, so we must be sure that we are deleting a prenotazione owned by the user
        $query = "SELECT * FROM prenotazione WHERE idPrenotazione = ? AND Utente_idUtente = ?";
        $result = $conn->execute_query($query, array($idPrenotazione, $utente->idUtente));
        if ($result->num_rows == 0) {
            echo '<script type="text/javascript">';
            echo 'alert("Pensavi di aver hackerato la metro? ATAC-CATE AR C***O!!");';
            echo '</script>';
            goto endDelete;
        }

        //Delete first all of the prenotazione has transiti where the user has made a prenotazione
        $query = "DELETE FROM prenotazione_has_transiti WHERE Prenotazione_idPrenotazione = ?";
        $conn->execute_query($query, array($idPrenotazione));

        //Delete then the prenotazione
        $query = "DELETE FROM prenotazione WHERE idPrenotazione = ?";
        $conn->execute_query($query, array($idPrenotazione));
    }
    endDelete:
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

        #toast-container {
            top: auto !important;
            bottom: 7%;
            left: 50%;
            transform: translateX(-50%);
        }

        .collection {}

        .collection-item .route-number {
            margin-right: 30px;
            cursor: pointer;
        }

        .custom-collection-item {
            padding-left: 16px !important;
            display: flex;
            align-items: center;
            justify-content: flex-start;
        }

        .id-container {
            font-size: 24px;
            font-weight: bold;
            margin-right: 30px;
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
    <div id="form-container">
        <center>
            <h4><b>Le tue prenotazioni</b></h4>
        </center>
        <br>
        <ul class="collection" style="max-height: 600px; overflow-y: auto;">
            <?php

            $query = "SELECT * FROM prenotazione WHERE Utente_idUtente = ?";
            $result = $conn->execute_query($query, array($utente->idUtente));
            foreach ($result as $row) {
                ?>
                <li class="collection-item avatar custom-collection-item" style="cursor: pointer;"
                    onclick="submitForm(event, this)">
                    <form action="prenotazione.php" method="POST" style="width: 100%; display: flex; align-items: center;">
                        <div class="id-container">
                            id:
                            <?= $row['idPrenotazione'] ?>
                        </div>
                        <div style="flex-grow: 1;">
                            <div style="font-size: 16px;">
                                <p><b>Data Prenotazione: </b>
                                    <?= date("d/m/Y H:i:s", strtotime($row['DataPrenotazione'])) ?>
                                </p>
                                <p><b>Ora Partenza: </b>
                                    <?= date("H:i", strtotime($row['OraPartenza'])) ?>
                                </p>
                                <p><b>Ora Arrivo: </b>
                                    <?= date("H:i", strtotime($row['OraArrivo'])) ?>
                                </p>
                                <p><b>Costo:</b> €
                                    <?= $row['Costo'] ?>
                                </p>
                            </div>
                            <input type="hidden" name="id_prenotazione" value="<?= $row['idPrenotazione'] ?>">
                            <button type="submit" id="submitBtn" style="display: none;"></button>
                        </div>
                    </form>
                    <a href="#!" class="secondary-content center-align" style="align-self: center;"
                        onclick="deleteItem(event, <?= $row['idPrenotazione'] ?>)"><i
                            class="material-icons medium">delete</i></a>
                </li>
                <?php
            }
            ?>
        </ul>

        <script>
            // This function is called when a list item is clicked, but not on the delete icon.
            function submitForm(event, element) {
                // If the clicked element is not the delete icon, submit the form.
                if (!event.target.classList.contains('material-icons')) {
                    element.querySelector('#submitBtn').click();
                }
            }

            // This function is called when the delete icon is clicked.
            function deleteItem(event, idPrenotazione) {
                // Stop the event from propagating to the parent element to prevent calling the submitForm function.
                event.stopPropagation();

                // Create a new form element with the POST method and set its action to prenotazione.php.
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'prenotazioni.php';

                // Create a hidden input for id_prenotazione and set its value.
                const inputId = document.createElement('input');
                inputId.type = 'hidden';
                inputId.name = 'id_prenotazione';
                inputId.value = idPrenotazione;
                form.appendChild(inputId);

                // Create a hidden input for the delete action and set its value to an empty string.
                const inputDelete = document.createElement('input');
                inputDelete.type = 'hidden';
                inputDelete.name = 'delete';
                inputDelete.value = '';
                form.appendChild(inputDelete);

                // Append the form to the document body and submit it.
                document.body.appendChild(form);
                form.submit();
            }
        </script>

    </div>
    <!-- Change primary color -->
    <script src="../scripts/changeColor.js"></script>
    <script>
        //Initialize the dropdown trigger for account menu
        $('.dropdown-trigger').dropdown();
    </script>
</body>

</html>