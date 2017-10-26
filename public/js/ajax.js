var url = "http:localhost:8888/twitter/";

$('.follower').click(function () {
    var user_screen_name = $(this).data('id');

    console.log(user_screen_name);

    $.ajax({
        type:"POST",
        url: url+user_screen_name;

    });

    return false;
});