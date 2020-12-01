/**
 *    akModal-  simplest alternative to thickbox
 *    author: Amit Kumar Singh
 *    project url : http://amiworks.co.in/talk/akmodal-simplest-alternative-to-thickbox/
 *    inspired from early versions of thickbox
 *
 **/
/**
 * Version 2.0.0
 *  @param String  navurl             url to dispaly in the ifame
 *  @param String  title      title of the pop up box
 *  @param  Numeric  box_width    width of the box in pixels
 *  @param  Numeric  box_height    height of the box in pixels
 *
 **/

jQuery.extend({

    showAkModal: function (navurl, title, box_width, box_height) {
        var offset = {};
        var options = {
            margin: 1,
            border: 1,
            padding: 1,
            scroll: 0
        };

        var win_width = $(window).width();
        var scrollToLeft = $(window).scrollLeft();
        var win_height = $(window).height();
        var scrollToBottom = $(window).scrollTop();


        $('body').append("<div id='ak_modal_div'><div id='intro_naglowek'><span>" + title + "</span><img src='doc/intro/img/cross.png' id='close'></div>" + navurl + "</div>");

        $('#ak_modal_div').css({
            left: (((win_width / 2 - box_width / 2)) + scrollToLeft - 200) + 'px',
            top: (((win_height / 2 - box_height / 2)) + scrollToBottom - 100) + 'px'
        });

        $('#close').click(function () {
            //
            $('#ak_modal_div').fadeOut(500);
            $('#ak_modal_div').remove();
            $.dimScreenStop();
        });

        $.dimScreen(500, 0.7, function () {
            $('#ak_modal_div').fadeIn(500);
        });

        var offset = {}
        offset = $("#ak_modal_div").offset({scroll: false})

        X_left = offset.left + box_width - 16;
        X_top = offset.top;

        $('#close').css({left: X_left, top: X_top});

    },

    akModalRemove: function () {
        $('#ak_modal_div').fadeOut(500);
        $.dimScreenStop();
    },

    akModalHideAndRedirect: function (redirect_url) {
        $('#ak_modal_div').fadeOut(500);
        $.dimScreenStop();
        window.location = redirect_url;
    }
});	


