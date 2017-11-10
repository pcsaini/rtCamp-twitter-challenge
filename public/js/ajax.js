$(document).ready(function () {

    $('#loader').hide();

    var url = "http:localhost:8888/twitter/";

    $('.follower').click(function () {
        var user_screen_name = $(this).data('id');
        $.ajax({
            type: "GET",
            url: '/twitter/' + user_screen_name,
            success: function (responce) {
                $('.twitter').html(responce);
            }

        });

        return false;
    });

    $('.slider').bxSlider({
        auto: true,
        autoControls: true,
        stopAutoOnClick: true,
        pager: false,
        speed:700
    });

    $('#sendMail').submit(function () {
        var email = $('#email').val();

        $('#sendMail').hide();
        $('#loader').show();
        $.ajax({
            type: 'GET',
            url: '/twitter/mail/'+ email,
            success: function (response) {
                if (response.done == true) {
                    $('#loader').hide();
                    $('.response').html('<div class="alert bg-danger alert-dismissable" role="alert"><em class="fa fa-lg fa-warning">&nbsp;</em>Mail Successfully Sent.</div>')
                } else {
                    $('.response').html('<div class="alert bg-danger alert-dismissable" role="alert"><em class="fa fa-lg fa-warning">&nbsp;</em>Some Problem in Create PDF or sending mail</div>');
                }
            }
        });

        return false;
    });

});

function myFunction() {
    // Declare variables
    var input, filter, ul, li, a, i;
    input = document.getElementById('myInput');
    filter = input.value.toUpperCase();
    ul = document.getElementById("myUL");
    li = ul.getElementsByTagName('li');

    // Loop through all list items, and hide those who don't match the search query
    for (i = 0; i < li.length; i++) {
        a = li[i].getElementsByTagName("a")[0];
        if (a.innerHTML.toUpperCase().indexOf(filter) > -1) {
            li[i].style.display = "";
        } else {
            li[i].style.display = "none";
        }
    }
}