<!DOCTYPE HTML>
<html>
<title>Metropolitana - Registrati</title>

<head>
    <style>
        html,
        body,
        .my-wrapper {
            height: 100%;
        }

        .backimg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
            filter: blur(5px);
        }

        .logo {
            width: 40%;
            height: 40%;
        }

        .card {
            width: 35vw;
        }
    </style>

    <?php
    session_start();
    if (isset($_SESSION["Username"]) || isset($_COOKIE["Username"])) {
        header('Location:index.php');
    }

    //create the connection
    $conn = mysqli_connect("127.0.0.1", "root", "", "metro");
    $conn->set_charset('utf8');

    $username = NULL;
    $name = NULL;
    $surname = NULL;
    $cf = NULL;
    $age = NULL;
    $job = NULL;
    $passwordHash = NULL;

    //At this point the variables are already set, since the fields are required, but we check anyway
    if (isset($_POST['username']) && isset($_POST['name']) && isset($_POST['surname']) && isset($_POST["cf"]) && isset($_POST["age"]) && isset($_POST["password"])) {
        $username = $_POST['username'];
        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $cf = $_POST["cf"];
        $age = $_POST["age"];
        $passwordHash = hash("sha256", $_POST["password"]);


        $queryCheck = "SELECT * FROM utente WHERE username = ?";

        $result = $conn->execute_query($queryCheck, array($username));

        if ($result->num_rows != 0) {
            echo '<script type="text/javascript">';
            echo 'alert("Lo username da te inserito è già esistente. Fai il login.");';
            echo '</script>';
        } else {
            $result = NULL;
            //Check the job separately since it's not required from the start
            if (isset($_POST["job"])) {
                $job = $_POST['job'];
                $query = 'INSERT INTO utente (Nome, Cognome, CF, Eta, Professione, PasswordHash, Username) VALUES (?, ?, ?, ?, ?, ?, ?)';
                $result = $conn->execute_query($query, array($name, $surname, $cf, $age, $job, $passwordHash, $username));
            } else {
                $query = 'INSERT INTO utente (Nome, Cognome, CF, Eta, PasswordHash, Username) VALUES (?, ?, ?, ?, ?, ?)';
                $result = $conn->execute_query($query, array($name, $surname, $cf, $age, $passwordHash, $username));
            }

            if (!$result) {
                echo "Errore MYSQL:" . $conn->error;
                return;
            }

            //start the session
            session_start();
            $_SESSION["Username"] = $username;

            //see if the user toogled "remember me"
            if (isset($_POST["remember"])) {
                // Create cookie with duration 7 days to remember the user
                setcookie("Username", $username, time() + (86400 * 7), "/");
                setcookie("PasswordHash", $passwordHash, time() + (86400 * 7), "/");
            }
            //redirect to home
            header("Location: index.php");
        }
    }
    ?>

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

    <!-- Change primary color -->
    <script src="scripts/changeColor.js"></script>

</head>

<body class="dark">
    <div class="my-wrapper container valign-wrapper">
        <div class="row">
            <!-- <div class="col s12 m6">
                <img src="https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fwww.metropolitanadiroma.it%2Fimages%2Fnotizie%2F2019-06%2Fmetro-b-stazione-repubblica-riapre.jpg" alt="Your image description" class="responsive-img">
            </div> -->
            <div class="col s12 m12">
                <div class="card" style="border-radius: 15px">
                    <div class="card-content center-align">
                        <img src="https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fwww.ilprimatonazionale.it%2Fwp-content%2Fuploads%2F2014%2F12%2Fatac-logo.png"
                            class="prefix logo">
                        <span class="card-title">
                            <h5><b>Metropolitana - Registrati</b></h5>
                        </span>
                        <br>
                        <form action="<?php echo $_SERVER["PHP_SELF"] ?>" method="post" id="mainform">
                            <div class="input-field">
                                <i class="material-icons prefix">account_circle</i>
                                <input id="username" type="text" class="validate" name="username" required>
                                <label for="username">Username</label>
                            </div>
                            <div class="input-field">
                                <i class="material-icons prefix">face</i>
                                <input id="name" type="text" class="validate" name="name" required>
                                <label for="name">Nome</label>
                            </div>
                            <div class="input-field">
                                <i class="material-icons prefix">face</i>
                                <input id="surname" type="text" class="validate" name="surname" required>
                                <label for="surname">Cognome</label>
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
                                    <input type="checkbox" class="filled-in" checked="checked" name="remember"
                                        value="yes" />
                                    <span>Ricordami</span>
                                </label>
                            </div>
                            <br>
                            <a style="border-radius: 15px" class="waves-effect waves-red btn-flat" href="login.php">Fai
                                il Login
                                <i class="material-icons right">login</i>
                            </a>
                            <button style="border-radius: 15px" class="btn waves-effect waves-light" type="submit"
                                name="action">Registrati
                                <i class="material-icons right">person_add</i>
                            </button>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <img class="backimg" src="https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fi.redd.it%2Fdbjatwwqsyg41.jpg"
        alt="Background image">

    <!-- Check if the two passwords are the same -->
    <script>
        $(document).ready(function () {
            const password1 = document.querySelector('#password');
            const password2 = document.querySelector('#password2');
            const passwordForm = document.querySelector('#mainform');

            function checkPasswords() {
                const helperText = password2.nextElementSibling;
                if (password1.value === password2.value) {
                    helperText.classList.remove('invalid');
                    helperText.classList.add('valid');
                } else {
                    helperText.classList.remove('valid');
                    helperText.classList.add('invalid');
                }
            }

            password1.addEventListener('input', checkPasswords);
            password2.addEventListener('input', checkPasswords);

            passwordForm.addEventListener('submit', (event) => {
                if (password1.value !== password2.value) {
                    event.preventDefault();
                    alert('Le password non sono uguali. Riprova');
                }
            });

        });
    </script>

</body>

</html>