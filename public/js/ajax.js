$(document).ready(function () {

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

        $('#Mail').modal('hide');
        $('#response').modal('show');
        $('#loader').show();
        $('.response').html('');
        $.ajax({
            type: 'GET',
            url: '/twitter/mail/'+ email,
            success: function (response) {
                $('#loader').hide();
                if (response.done == true) {
                    $('.response').html('<div class="alert bg-danger alert-dismissable" role="alert"><em class="fa fa-lg fa-warning">&nbsp;</em>Mail Successfully Sent.</div>')
                } else {
                    $('#loader').hide();
                    $('.response').html('<div class="alert bg-danger alert-dismissable" role="alert"><em class="fa fa-lg fa-warning">&nbsp;</em>Some Problem in Create PDF or sending mail</div>');
                }
            }
        });

        return false;
    });

    $('#getFollower').submit(function () {
        var userHandler = $('#userHandler').val();
        var type = $('#type :selected').val();

        $('#Follower').modal('hide');
        $('#response').modal('show');
        $('#loader').show();
        $('.response').html('');
        if (type == 'google') {
            $.ajax({
                method:'GET',
                url: '/twitter/follower/'+userHandler +'/google',
                success: function (response) {
                    $('#loader').hide();
                    if (response.done == true){
                        $('.response').html('<div class="text-center">Google SpreadSheet Successfully Created</a></div>');
                    }else if(response.done == 'login'){
                        window.location.href = response.link;
                        //$('.response').html('<div class="text-center"><a class="btn btn-primary" href="'+ response.link +'" target="_blank">Google Login</a></div>');
                    }else{
                        $('.response').html(response);
                    }
                }
            });
        }else{
            $.ajax({
                method:'GET',
                url: '/twitter/follower/'+userHandler +'/'+ type,
                success: function (response) {
                    $('#loader').hide();
                    if (response.done == true){
                        if(response.type == 'xml' || response.type == 'json' || response.type == 'pdf'){
                            $('.response').html('<div class="text-center"><a class="btn btn-primary" href="upload/'+ response.link +'" target="_blank">Download</a></div>');
                                
                        }else{
                            $('.response').html('<div class="text-center"><a class="btn btn-primary" href="upload/'+ response.link +'" >Download</a></div>');
                        }
                    }else{
                        $('.response').html(response);
                    }
                }
            });
        }
        
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