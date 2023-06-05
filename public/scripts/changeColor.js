var themeColor = "#822433";

// Check browser support
if (typeof (Storage) !== "undefined") {
    // Store
    themeColor = localStorage.getItem("themeColor");
    if (themeColor == undefined) {
        localStorage.setItem("themeColor", "blue");
        themeColor = 'blue';
    }

} else {
    Materialize.toast("Sorry, your browser does not support Web Storage...", 4000)
}

$(".nav-wrapper").css("background-color", themeColor);
$(".secondary-content>.material-icons").css("color", themeColor);
$(".btn").css("background-color", themeColor);
$(".page-footer").css("background-color", themeColor);
$(".input-field").css("color", themeColor);
$(".input-field>.material-icons").css("color", themeColor);
$(".input-field>label").css("color", themeColor);
$(".dropdown-content>li>a").css("color", themeColor);
$('.dropdown-content li span').css('color', themeColor);
// Change the color of the timepicker
$('.timepicker-digital-display').css('background-color', themeColor);
$('.timepicker-close').css('color', themeColor);
// Change the clock hand color
$('.timepicker-hour-hand').css('background-color', themeColor);
$('.timepicker-minute-hand').css('background-color', themeColor);
$('.timepicker-second-hand').css('background-color', themeColor);
$('.tabs .tab>a.active').css('color', themeColor);
$('.tabs .indicator').css('background-color', themeColor);

$(document).ready(function () {


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
    $('.dropdown-content li span').css('color', themeColor);
    // Change the color of the timepicker
    $('.timepicker-digital-display').css('background-color', themeColor);
    $('.timepicker-close').css('color', themeColor);
    // Change the clock hand color
    $('.timepicker-hour-hand').css('background-color', themeColor);
    $('.timepicker-minute-hand').css('background-color', themeColor);
    $('.timepicker-second-hand').css('background-color', themeColor);
    $('.tabs .tab a.active').css('color', themeColor);
    $('.tabs .indicator').css('background-color', themeColor);

    // Update Theme Color
    if (typeof (Storage) !== "undefined") {
        // Store
        localStorage.setItem("themeColor", themeColor);

    } else {
        Materialize.toast("Sorry, your browser does not support Web Storage...", 4000)
    }

});