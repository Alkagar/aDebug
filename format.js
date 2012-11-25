$(document).ready(function() {
    function closeLevel(element) {
        element.children().each(
            function() {
                if($(this).is('.title')) {} else {
                    $(this).hide();
                }
            });
    }

    var opener = $('<span></span>').text('+ ');
    opener.click(function() {
        $(this).parent().parent().parent().children().each(function() {
            $(this).show();
        }); 
    });
    $('body').prepend(opener);
    $('.title .adebug-element').prepend(opener);
    $('.root').each(function() {
        closeLevel($(this)); 
    });
});
