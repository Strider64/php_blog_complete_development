$(function () {
    var $registerBtn = $('#registerBtn'),
            $register = $('#register'),
            $loginBtn = $('#loginBtn'),
            $login = $('#login'),
            $enterBlogBtn = $('#enterBlogBtn'),
            $enterBlog = $('#mySimpleBlogForm');

    $register.css('position', 'absolute');
    $register.hide();

    $registerBtn.on('click', function (e) {
        e.preventDefault();
        $register.slideToggle('slow');
    });

    $login.hide();

    $loginBtn.on('click', function (e) {
        e.preventDefault();
        $login.animate({width: 'toggle'}, 'slow');
    });

    $enterBlog.css('position', 'absolute');
    $enterBlog.hide();

    $enterBlogBtn.on('click', function (e) {
        e.preventDefault();
        $enterBlog.slideToggle('slow');
    });

}); // End of Document Ready Function:
