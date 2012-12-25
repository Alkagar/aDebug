$(document).ready(function() {
    function closeLevel(element) {
        element.children().each(
            function() {
                if($(this).is('.title')) {} else {
                    $(this).hide();
                }
            });
    }
    function openLevel(element) {
        element.children().each(
            function() {
                $(this).show();
            });
    }

    var opener = $('<span></span>').text('+ ');
    opener.click(function() {
        var element = $(this).parent().parent().parent();
        openLevel(element);
    });

    $('body').prepend(opener);
    $('.title .adebug-element').prepend(opener);
    $('.root').each(function() {
        closeLevel($(this)); 
    });
});
