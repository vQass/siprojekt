$(document).ready(function () {
    $('#singup').click(function () {
        console.log("woo");
        $('#singup_container').css('visibility', 'visible');
    });

    $('#close_singup').click(function () {
        $('#singup_container').css('visibility', 'hidden');
    });

    $('#singin').click(function () {
        $('#singin_container').css('visibility', 'visible');
    });

    $('#close_singin').click(function () {
        $('#singin_container').css('visibility', 'hidden');
    });
});

function showDiv() {
    if (document.getElementById('google').style.display == "block") {
        document.getElementById('google').style.display = "none";
    }
    else {
        document.getElementById('google').style.display = "block";
    }
}

