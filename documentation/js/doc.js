SyntaxHighlighter.all();

$(document).ready(function () {
    // Page animation
    $('.js-page-scrolling').click(function (e) {
        let $elementTarget = e.target.tagName.toLowerCase() === 'a' ? $(e.target) : $(e.target.parentNode),
            $scrollingTarget = $($elementTarget.attr('href')),
            scrollingPosition = 0;


        if ($scrollingTarget !== '#top') scrollingPosition = $scrollingTarget.offset().top - 35;
        else return false;

        $('html, body').animate({scrollTop: scrollingPosition}, 500);
    });

    // See more data
    $('.js-see-more-less').click(function (e) {
        let $selector = $(e.target),
            $target = $selector.closest('.js-see-more-parent').find('.js-class-container');

        if ($target.hasClass('see-less')) {
            $target.addClass('see-more').removeClass('see-less');
            $selector.html('See less');
        } else {
            $target.addClass('see-less').removeClass('see-more');
            $selector.html('See more');
        }
    })

    // Toggle sidebar
    $('.toggle-sidebar').click(function (e) {
        $('.toggle-sidebar').toggleClass('change');
        $('.doc-left-menus-panel').toggleClass('open-doc-left-menu');
    });

    // Back to top button
    $(window).scroll(function () {
        if ($(this).scrollTop() > 100) {
            $('.back-to-top').fadeIn();
        } else {
            $('.back-to-top').fadeOut();
        }
    });
});
