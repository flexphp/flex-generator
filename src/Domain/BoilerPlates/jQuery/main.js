jQuery(document).ready(function ($) {
    'use strict';
    if ($(".notification-list").length) {
        $('.notification-list').slimScroll({
            height: '100%'
        });
    }
    if ($(".menu-list").length) {
        $('.menu-list').slimScroll({
            height: '100%'
        });
    }
    if ($(".sidebar-nav-fixed a").length) {
        $('.sidebar-nav-fixed a').click(function (event) {
            if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '')
                && location.hostname == this.hostname
            ) {
                var target = $(this.hash);
                target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                if (target.length) {
                    event.preventDefault();
                    $('html, body').animate({
                        scrollTop: target.offset().top - 90
                    }, 1000, function () {
                        var $target = $(target);
                        $target.focus();
                        if ($target.is(":focus")) {
                            return false;
                        }

                        $target.attr('tabindex', '-1');
                        $target.focus();

                        return true;
                    });
                }
            };

            $('.sidebar-nav-fixed a').each(function () {
                $(this).removeClass('active');
            })

            $(this).addClass('active');
        });
    }

    if ($('[data-toggle="tooltip"]').length) {
        $('[data-toggle="tooltip"]').tooltip()
    }

    if ($('[data-toggle="popover"]').length) {
        $('[data-toggle="popover"]').popover()
    }

    $(document).on('submit', 'form[data-confirmation]', function (event) {
        var $form = $(this),
            $confirm = $('#confirmationModal');

        if ($confirm.data('result') !== 'yes') {
            //cancel submit event
            event.preventDefault();

            $confirm
                .off('click', '#btnYes')
                .on('click', '#btnYes', function () {
                    $confirm.data('result', 'yes');
                    $form.find('input[type="submit"]').attr('disabled', 'disabled');
                    $form.submit();
                })
                .modal('show');
        }
    });

    $(document).on('submit', 'form:not([data-confirmation])', function () {
        $('.overlay').show();
    });

    $(document).on('click', '.show-overlay', function () {
        $('.overlay').show();
    });
});
