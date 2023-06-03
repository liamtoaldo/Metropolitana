<!DOCTYPE HTML>
<html>
<title>Metropolitana - Impostazioni</title>

<head>
    <?php
    require '../src/dbObjects/utente.php';

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

    $usernameNew = NULL;
    $name = NULL;
    $surname = NULL;
    $cf = NULL;
    $age = NULL;
    $job = NULL;
    $passwordHash = NULL;

    //TODO handle password change logic
    //At this point the variables are already set, since the fields are required, but we check anyway
    if (isset($_POST['username']) && isset($_POST['name']) && isset($_POST['surname']) && isset($_POST["cf"]) && isset($_POST["age"])) {
        $usernameNew = $_POST['username'];
        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $cf = $_POST["cf"];
        $age = $_POST["age"];


        $queryCheck = "SELECT * FROM utente WHERE username = ?";

        $result = $conn->execute_query($queryCheck, array($usernameNew));

        if ($result->num_rows != 0 && $usernameNew != $username) {
            echo '<script type="text/javascript">';
            echo 'alert("Lo username da te inserito è già esistente. Utilizzane un altro.");';
            echo '</script>';
        } else {
            $result = NULL;
            //Check the job separately since it's not required from the start
            if (isset($_POST["job"])) {
                $job = $_POST['job'];
                $query = "UPDATE utente SET Nome=?, Cognome=?, CF=?, Eta=?, Professione=?, Username=? WHERE Username=?";
                $result = $conn->execute_query($query, array($name, $surname, $cf, $age, $job, $usernameNew, $username));
            } else {
                $query = "UPDATE utente SET Nome=?, Cognome=?, CF=?, Eta=?, Username=? WHERE Username=?";
                $result = $conn->execute_query($query, array($name, $surname, $cf, $age, $usernameNew, $username));
            }

            if (!$result) {
                echo "Errore MYSQL:" . $conn->error;
                return;
            }

            //Since the username was probably changed, update the cookie and session variables accordingly
            $username = $usernameNew;
            $_SESSION["Username"] = $usernameNew;
            // Create cookie with duration 7 days to remember the user
            setcookie("Username", $usernameNew, time() + (86400 * 7), "/");
        }

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

        #toast-container {
            top: auto !important;
            bottom: 7%;
            left: 50%;
            transform: translateX(-50%);
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

        .material-icons {
            vertical-align: middle;
        }

        .tabs .tab>a.active {
            color: #822433 !important;
        }

        .tabs .indicator {
            background-color: #822433 !important;
        }

        .value-row {
            margin-bottom: 0;
        }

        .button-container {
            display: flex;
            justify-content: flex-end;
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
    <?php
    $query = "SELECT * FROM utente WHERE Username = ?";
    $result = $conn->execute_query($query, array($username));
    $row = $result->fetch_assoc();
    $utente = new Utente($row['idUtente'], $row['Nome'], $row['Cognome'], $row['CF'], $row['Eta'], $row['Professione'], $row['PasswordHash'], $row['Username']);

    ?>
    <div id="form-container">
        <div class="row">
            <div class="col s12">
                <ul class="tabs">
                    <li class="tab col s6">
                        <a href="#general-tab">
                            <i class="material-icons">settings</i>
                            Generali
                        </a>
                    </li>
                    <li class="tab col s6">
                        <a href="#delete-account-tab">
                            <i class="material-icons">report</i>
                            Avanzate
                        </a>
                    </li>
                </ul>
            </div>
            <div id="general-tab" class="col s12">
                <!-- General tab content goes here -->
                <br>
                <h5><b>Modifica il tuo account</b>
                    <a id="edit-btn" onclick="toggleEdit()"
                        class="btn-floating btn-small waves-effect waves-light right">
                        <i class="material-icons">edit</i>
                    </a>
                </h5>
                <br>
                <form action="<?php echo $_SERVER["PHP_SELF"] ?>" method="post">
                    <div class="input-field">
                        <i class="material-icons prefix">account_circle</i>
                        <input id="username" type="text" class="validate" name="username" disabled required
                            value="<?= $utente->Username ?>">
                        <label for="username">Username</label>
                    </div>
                    <div class="input-field">
                        <i class="material-icons prefix">face</i>
                        <input id="name" type="text" class="validate" name="name" value="<?= $utente->Nome ?>" disabled
                            required>
                        <label for="name">Nome</label>
                    </div>
                    <div class="input-field">
                        <i class="material-icons prefix">face</i>
                        <input id="surname" type="text" class="validate" name="surname" value="<?= $utente->Cognome ?>"
                            disabled required>
                        <label for="surname">Cognome</label>
                    </div>
                    <div class="input-field">
                        <i class="material-icons prefix">assignment_ind</i>
                        <input id="cf" type="text" class="validate" name="cf" value="<?= $utente->CF ?>" disabled
                            required>
                        <label for="cf">Codice Fiscale</label>
                    </div>
                    <div class="input-field">
                        <i class="material-icons prefix">123</i>
                        <input id="age" type="number" class="validate" name="age" value="<?= $utente->Eta ?>" min="18"
                            disabled required>
                        <label for="age">Età</label>
                    </div>
                    <div class="input-field">
                        <i class="material-icons prefix">work</i>
                        <input id="job" type="text" class="validate" name="job" disabled
                            value="<?= $utente->Professione ?>">
                        <label for="job">Professione</label>
                    </div>
                    <br>
                    <div class="button-container">
                        <a id="cancel-btn" style="border-radius: 15px" class="waves-effect waves-red btn-flat"
                            href="account.php" disabled>Annulla<i class="material-icons right">cancel</i>
                        </a>
                        <button id="confirm-btn" style="border-radius: 15px" class="btn waves-effect waves-light"
                            type="submit" name="action" disabled>Conferma
                            <i class="material-icons right">check</i>
                        </button>
                    </div>
                </form>
            </div>
            <div id="delete-account-tab" class="col s12">
                <!-- Delete Account tab content goes here -->
                <h5><b>Modifica la tua password</b></h5>
                <form action="logout.php" method="post" id="advanced-form">
                    <div class="input-field">
                        <i class="material-icons prefix">lock_clock</i>
                        <input id="password-old" type="password" class="validate" name="password-old">
                        <label for="password-old">Vecchia Password</label>
                    </div>
                    <div class="input-field">
                        <i class="material-icons prefix">lock</i>
                        <input id="password" type="password" class="validate" name="password">
                        <label for="password">Nuova Password</label>
                    </div>
                    <div class="input-field">
                        <i class="material-icons prefix">lock</i>
                        <input id="password2" type="password" class="validate" name="password2">
                        <label for="password2">Conferma Nuova Password</label>
                    </div>
                    <div class="button-container">
                        <a id="cancel-btn" style="border-radius: 15px" class="waves-effect waves-red btn-flat"
                            href="account.php">Annulla<i class="material-icons right">cancel</i>
                        </a>
                        <button id="confirm-btn" style="border-radius: 15px" class="btn waves-effect waves-light"
                            type="submit" name="action">Conferma
                            <i class="material-icons right">check</i>
                        </button>
                    </div>
                </form>
                <h5><b>Elimina il tuo account</b></h5>
                <form action="logout.php" method="post" id="delete-form">
                    <button name="delete" id="delete-account-btn" style="border-radius: 15px"
                        class="btn waves-effect waves-light red darken-2" type="submit" name="action">Elimina
                        <i class="material-icons right">person_off</i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Change primary color -->
    <script src="../scripts/changeColor.js"></script>
    <script>
        //Initialize the dropdown trigger for account menu
        $('.dropdown-trigger').dropdown();
        // Initialize the tabs
        $(document).ready(function () {
            $('ul.tabs').tabs();
        });
        function toggleEdit() {
            const inputs = document.querySelectorAll('input');
            inputs.forEach(input => input.disabled = !input.disabled);
            const cancelButton = document.querySelector('#cancel-btn');
            cancelButton.toggleAttribute('disabled');
            const confirmButton = document.querySelector('#confirm-btn');
            confirmButton.disabled = !confirmButton.disabled;
            const editButton = document.querySelector('#edit-btn');
            const icon = editButton.querySelector('i');
            if (inputs[0].disabled) {
                icon.textContent = 'edit';
            } else {
                icon.textContent = 'edit_off';
            }
        }
        const accountForm = document.getElementById('delete-form');

        accountForm.addEventListener('submit', function (event) {
            const confirmed = confirm('Sei sicuro di voler eliminare il tuo account?');

            if (!confirmed) {
                // If the user clicks "Cancel", prevent the form from submitting
                event.preventDefault();
            }
        });
    </script>
</body>

</html>