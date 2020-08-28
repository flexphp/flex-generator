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

    $('.money-format').each(function () {
        $(this).html(getMoneyFormat($(this).html()));
    });

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
    }).on('submit', 'form:not([data-confirmation])', function () {
        $('.overlay').show();
    }).on('click', '.show-overlay', function () {
        $('.overlay').show();
    }).on('change', '.money-format', function () {
        const $html = $(this);
        const isInput = $html.is('input');
        const number = isInput ? $html.val() : $html.html();
        const money = getMoneyFormat(number);

        $html.data('mf-amount', number);

        isInput ? $html.val(money) : $html.html(money);
    });

    /** global: InfiniteScroll */
    if (typeof InfiniteScroll === 'function') {
        const infScroll = new InfiniteScroll('.dashboard-content', {
            path: function () {
                /** global: URLSearchParams */
                const page= (parseInt((new URLSearchParams(window.location.search)).get('page') || this.pageIndex) + 1);
                const form = document.querySelector('.dashboard-sidebar form');
                let queryFilters = '';

                if (form) {
                    /** global: FormData */
                    queryFilters = '&' + new URLSearchParams(new FormData(form)).toString();
                }

                return '?page=' + page + queryFilters;
            },
            responseType: 'html',
            status: '.infinite-scroll-status',
            history: false,
        }).on('load', function (html) {
            if (html === '') {
                infScroll.dispatchEvent('last');

                return;
            }

            document.querySelector('.dashboard-content .table > tbody').innerHTML += html;

            const moneyFormats = document.querySelectorAll('.dashboard-content .table > tbody > tr > td.money-format');

            [].forEach.call(moneyFormats, function(moneyFormat) {
                if (isNaN(moneyFormat.innerHTML)) {
                    return undefined;
                }

                moneyFormat.innerHTML = getMoneyFormat(moneyFormat.innerHTML);
            });
        });

        window.infScroll = infScroll;
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

function getMoneyFormat(number)
{
    if (isNaN(number)) {
        return 0;
    }

    /** global: Intl */
    if (!(typeof Intl == 'object' && Intl && typeof Intl.NumberFormat == 'function')) {
        return number;
    }

    return (number * 1).toLocaleString('es-CO', {
        style: 'currency',
        currency: 'COP',
        minimumFractionDigits: 0,
        maximumFractionDigits: 2
    });
}
