jQuery(document).ready(function ($) {
    'use strict';
    if ($('.notification-list').length) {
        $('.notification-list').slimScroll({
            height: '100%'
        });
    }
    if ($('.menu-list').length) {
        $('.menu-list').slimScroll({
            height: '100%'
        });
    }
    if ($('.navbar-nav > .nav-item > a').length) {
        $('.navbar-nav > .nav-item > a').click(function () {
            if ($(this).hasClass('active')) {
                $(this).removeClass('active');
                $(this).attr('aria-expanded', 'false');

                return;
            }

            $('.navbar-nav > .nav-item > a').each(function () {
                $(this).removeClass('active');
                $(this).attr('aria-expanded', 'false');

                const target = $(this).data('target');

                $(target).removeClass('show');
                $(target).attr('aria-expanded', 'false');
            })

            $(this).addClass('active');
            $(this).attr('aria-expanded', 'true');
        });
    }
    if ($('.sidebar-nav-fixed a').length) {
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
                        if ($target.is(':focus')) {
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

    if (typeof InfiniteScroll === 'function') {
        const infScroll = new InfiniteScroll('.dashboard-content', {
            path: function () {
                return '?page=' + (parseInt((new URLSearchParams(window.location.search)).get('page') || this.pageIndex) + 1);
            },
            responseType: 'html',
            status: '.infinite-scroll-status',
            history: false,
        }).on('load', function (html) {
            if (html === '') {
                infScroll.destroy();
            }

            document.querySelector('.dashboard-content .table > tbody').innerHTML += html;
        });
    }
});

function getCookie(cname)
{
    const name = cname + '=';
    const ca = document.cookie.split(';');

    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];

        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }

        if (c.indexOf(name) === 0) {
            return c.substring(name.length, c.length);
        }
    }

    return '';
}
