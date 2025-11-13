$(function() {

    $('#side-menu').metisMenu();

});

//Loads the correct sidebar on window load,
//collapses the sidebar on window resize.
$(function() {
    $(window).bind("load resize", function() {
        width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
        if (width < 768) {
            $('div.sidebar-collapse').addClass('collapse')
        } else {
            $('div.sidebar-collapse').removeClass('collapse')
        }
    })
})
$(document).ready(function(){
    $('.login-panel').hide().fadeIn(1000);
    $('.btn-login').hover(function(){
        $(this).css('background-color', '#0056b3');
    }, function(){
        $(this).css('background-color', '#007bff');
    });
});

