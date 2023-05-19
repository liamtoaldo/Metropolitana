<!DOCTYPE HTML>
<html>
<title>Metropolitana - Login</title>

<head>
    <?php
    session_start();
    $username = NULL;
    if (isset($_SESSION["Username"])) {
        $username = $_SESSION["Username"];
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
        }

        .logo-img {
            max-height: 64px;
            padding: 15px 0;
            display: inline-block;
        }

        /* Increase the font size of the navigation links */
        nav ul li a {
            font-size: 18px;
        }

        /* Increase the font size of the logo */
        .brand-logo {
            font-size: 24px;
        }
    </style>

    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="https://i.imgur.com/oJTV2bZ.png">

    <!-- Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!--Import jQuery before materialize.js-->
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>

    <!-- Compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>

    <!-- Change primary color -->
    <script src="scripts/changeColor.js"></script>

    <script>
        // Show the spinner element when the page starts loading
        window.addEventListener('load', function () {
            const spinner = document.getElementById('loading-spinner');
            spinner.style.display = 'block';
        });

        // Hide the spinner element when the page finishes loading
        window.addEventListener('DOMContentLoaded', function () {
            const spinner = document.getElementById('loading-spinner');
            spinner.style.display = 'none';
        });
    </script>
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
                    <li><a href="page1.html">Page 1</a></li>
                    <li><a href="page2.html">Page 2</a></li>
                    <li><a href="page3.html">Page 3</a></li>
                </ul>
            </div>
        </div>
    </nav>

</body>

</html>