<!DOCTYPE HTML>
<html>
<title>
Metropolitana - Registrati
</title>
<head>
    <style>
        html, body, .my-wrapper {
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
            width:35vw;
        }

    </style>

    <?php

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
            
    <script>
        
        var themeColor ;

        // Check browser support
        if (typeof(Storage) !== "undefined") {
            // Store
            themeColor = localStorage.getItem("themeColor");
        if(themeColor == undefined)
        {localStorage.setItem("themeColor", "blue");
            themeColor = 'blue';
            } 
        
        } else {
        Materialize.toast("Sorry, your browser does not support Web Storage...", 4000) 
        }

        $(".nav-wrapper").css("background-color", themeColor);
        $(".secondary-content>.material-icons").css("color", themeColor);
        $(".btn").css("background-color", themeColor);
        $(".page-footer").css("background-color",themeColor);
        $(".input-field").css("color", themeColor);
        $(".input-field>.material-icons").css("color", themeColor);
        $(".input-field>label").css("color",themeColor);
        $(".dropdown-content>li>a").css("color", themeColor);

        $(document).ready(function(){
        
        
        $('.dropdown-button').dropdown({
            inDuration: 300,
            outDuration: 225,
            constrainWidth: false, // Does not change width of dropdown to that of the activator
            hover: true, // Activate on hover
            gutter: 0, // Spacing from edge
            belowOrigin: false, // Displays dropdown below the button
            alignment: 'left', // Displays dropdown with edge aligned to the left of button
            stopPropagation: false // Stops event propagation
            }
        );
        var themeColor = "#822433";
        $(".nav-wrapper").css("background-color", themeColor);
        $(".secondary-content>.material-icons").css("color", themeColor);
        $(".btn").css("background-color", themeColor);
        $(".page-footer").css("background-color", themeColor);
        $(".input-field").css("color", themeColor);
        $(".input-field>.material-icons").css("color", themeColor);
        $(".input-field>label").css("color", themeColor);
        $(".btn-floating").css("background-color", themeColor);
        $(".dropdown-content>li>a").css("color", themeColor);
        
        // Update Theme Color
        if (typeof(Storage) !== "undefined") {
            // Store
            localStorage.setItem("themeColor", themeColor);

        } else {
            Materialize.toast("Sorry, your browser does not support Web Storage...", 4000) 
        }

        });
</script>
</head>
</html>