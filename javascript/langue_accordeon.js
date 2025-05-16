$(document).ready (function (){

    // Accordéon
    $(".langue-accordeon").click(function () {
        $(this).next(".langues-options").slideToggle();
    });

});