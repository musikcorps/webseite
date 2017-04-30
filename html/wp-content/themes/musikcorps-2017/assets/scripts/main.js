(function (window, document, $, undefined) {

    "use strict";

    // kick off Foundation
    $(document).foundation();

    $(function() {
        // only fire on gallery slide page
        if($('.lg_gallery.slide').length === 0) return;
        // attach key listener to switch slides
        $(document).keydown(function(e) {
            switch (e.which) {
                case 37: // left arrow
                    location.href = $('.prev-page').attr('href');
                    break;
                case 39: // right arrow
                    location.href = $('.next-page').attr('href');
                    break;
                default:
                    return;
            }
            e.preventDefault();
        });
    });

})( window, document, jQuery );
