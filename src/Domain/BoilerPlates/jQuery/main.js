/** global: Intl */
/** global: FormData */
/** global: InfiniteScroll */
/** global: URLSearchParams */
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
        // Used in template without AJAX
        $(this).data('mf-amount', Math.round($(this).html()));
        $(this).html(getMoneyFormat($(this).html()));
    });

    $('.date-format').each(function () {
        // Used in template without AJAX
        $(this).attr('title', getDate($(this).html()));
        $(this).html(getDateFormat($(this).html()));
    });

    $('.datetime-format').each(function () {
        // Used in template without AJAX
        $(this).attr('title', getDateTime($(this).html()));
        $(this).html(getDateTimeFormat($(this).html()));
    });

    $('.timeago-format').each(function () {
        // Used in template without AJAX
        $(this).attr('title', getDateTime($(this).html()));
        $(this).html(getTimeAgo($(this).html()));
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
    }).on('click', '.show-overlay', function (e) {
        if (!window.event.ctrlKey) {
            $('.overlay').show();
        }
    }).on('change', '.money-format', function () {
        // Used in dashboard view
        const isInput = $(this).is('input');
        const number = isInput ? $(this).val() : $(this).html();
        const money = getMoneyFormat(number);

        $(this).data('mf-amount', Math.round(number));

        isInput ? $(this).val(money) : $(this).html(money);
    });

    // @see https://github.com/stefangabos/Zebra_Datepicker
    if ($(document).Zebra_DatePicker) {
        $('.date-picker').Zebra_DatePicker_i18n({
            readonly_element: false,
            format: window.flex.defaultDateFormat,
        });

        $('.datetime-picker').Zebra_DatePicker_i18n({
            readonly_element: false,
            format: window.flex.defaultDateTimeFormat,
        });

        $('.time-picker').Zebra_DatePicker_i18n({
            readonly_element: false,
            format: window.flex.defaultTimeFormat,
        });

        $('.date-start-picker').Zebra_DatePicker_i18n({
            readonly_element: false,
            pair: $('.date-finish-picker'),
            format: window.flex.defaultDateFormat,
        });

        $('.date-finish-picker').Zebra_DatePicker_i18n({
            readonly_element: false,
            format: window.flex.defaultDateFormat,
        });
    }

    if (typeof InfiniteScroll === 'function') {
        const infScroll = new InfiniteScroll('.dashboard-content', {
            path: function () {
                const page= (parseInt((new URLSearchParams(window.location.search)).get('page') || this.pageIndex) + 1);
                const form = document.querySelector('.dashboard-sidebar form');
                let queryFilters = '';

                if (form) {
                    queryFilters = '&' + new URLSearchParams(new FormData(form)).toString();
                }

                return '?page=' + page + queryFilters;
            },
            responseType: 'document',
            fetchOptions: {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                },
            },
            domParseResponse: false,
            status: '.infinite-scroll-status',
            history: false,
        }).on('load', function (html) {
            if (html === '') {
                infScroll.dispatchEvent('last');

                return;
            }

            document.querySelector('.dashboard-content .table > tbody').innerHTML += html;

            updateTableFormats();

            infScroll.dispatchEvent('append');
        });

        $('[name$=_filter_form]').on('submit', function (e) {
            e.preventDefault();

            const url = $(this).attr('action');
            const method = $(this).attr('method');
            const data = $(this).serialize();

            $.ajax({
                url: url,
                method: method,
                dataType: 'html',
                data: data,
                headers: {
                    'X-XSRF-Token': getCookie('XSRF-Token')
                },
                beforeSend: function () {
                    $('.overlay').show();
                }
            }).always(function () {
                $('.overlay').hide();
            }).done(function (html) {
                infScroll.pageIndex = 1;

                $('.dashboard-content .table > tbody').empty().html(html);

                updateTableFormats();

                infScroll.dispatchEvent('append');
            });
        });

        window.infScroll = infScroll;
    }
});

function updateTableFormats()
{
    const moneyFormats = document.querySelectorAll('.dashboard-content .table > tbody > tr > td.money-format');

    [].forEach.call(moneyFormats, function (moneyFormat) {
        if (!isNaN(moneyFormat.innerText)) {
            moneyFormat.setAttribute('data-mf-amount', moneyFormat.innerText);
        }
        moneyFormat.innerHTML = getMoneyFormat(moneyFormat.innerHTML);
    });

    const timeAgos = document.querySelectorAll('.dashboard-content .table > tbody > tr > td.timeago-format');

    [].forEach.call(timeAgos, function (timeAgo) {
        timeAgo.title = getDateTime(timeAgo.innerHTML);
        timeAgo.innerHTML = getTimeAgo(timeAgo.innerHTML);
    });
}

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
        return number;
    }

    if (!(typeof Intl === 'object' && Intl && typeof Intl.NumberFormat === 'function')) {
        return number;
    }

    return (number * 1).toLocaleString(window.flex.defaultLocale, {
        style: 'currency',
        currency: window.flex.defaultCurrency,
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    });
}

function isValidDateTimeFormat(datetime)
{
    return !(!datetime || datetime === undefined || datetime === null || datetime === '');

}

function getDate(date)
{
    if (!isValidDateTimeFormat(date)) {
        return date;
    }

    try {
        return (new Date((new Date(date)).setMilliseconds(getTimeZoneOffset() * 2))).toJSON().slice(0, 19).replace('T', ' ');
    } catch (e) {
        return date;
    }
}

function getDateFormat(date)
{
    if (!isValidDateTimeFormat(date)) {
        return date;
    }

    try {
        return (new Date((new Date(date)).setMilliseconds(getTimeZoneOffset()))).toLocaleDateString(window.flex.defaultLocale, {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
        });
    } catch (e) {
        return date;
    }
}

function getDateTime(datetime)
{
    if (!isValidDateTimeFormat(datetime)) {
        return datetime;
    }

    try {
        return (new Date((new Date(datetime)).setMilliseconds(getTimeZoneOffset() * 2))).toJSON().slice(0, 19).replace('T', ' ');
    } catch (e) {
        return datetime;
    }
}

function getDateTimeFormat(datetime)
{
    if (!isValidDateTimeFormat(datetime)) {
        return datetime;
    }

    try {
        return (new Date((new Date(datetime)).setMilliseconds(getTimeZoneOffset()))).toLocaleDateString(window.flex.defaultLocale, {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: 'numeric',
            minute: 'numeric',
            second: 'numeric',
        });
    } catch (e) {
        return datetime;
    }
}

const MINUTE = 60;
const HOUR = MINUTE * 60;
const DAY = HOUR * 24;
const WEEK = DAY * 7;
const MONTH = DAY * 30;
const YEAR = DAY * 365;

function getTimeAgo(datetime)
{
    if (!isValidDateTimeFormat(datetime)) {
        return datetime;
    }

    const secondsAgo = Math.round((+new Date() - (new Date((new Date(datetime)).setMilliseconds(getTimeZoneOffset())))) / 1000);

    let divisor = null
    let unit = null

    if (isNaN(secondsAgo)) {
        return datetime;
    } else if (secondsAgo < MINUTE) {
        return 'hace ' + secondsAgo + ' segundos'
    } else if (secondsAgo < HOUR) {
        [divisor, unit] = [MINUTE, 'minuto']
    } else if (secondsAgo < DAY) {
        [divisor, unit] = [HOUR, 'hora']
    } else if (secondsAgo < WEEK) {
        [divisor, unit] = [DAY, 'día']
    } else if (secondsAgo < MONTH) {
        [divisor, unit] = [WEEK, 'semana']
    } else if (secondsAgo < YEAR) {
        [divisor, unit] = [MONTH, 'semana']
    } else if (secondsAgo > YEAR) {
        [divisor, unit] = [YEAR, 'año']
    }

    count = Math.floor(secondsAgo / divisor)

    return  `hace ${count} ${unit}${(count > 1)?'s':''}`
}

function getTimeZone()
{
    if (!(typeof Intl === 'object' && Intl && typeof Intl.DateTimeFormat === 'function')) {
        return window.flex.defaultTimezone;
    }

    return Intl.DateTimeFormat().resolvedOptions().timeZone;
}

function getTimeZoneOffset()
{
    return -1  * ((new Date()).getTimezoneOffset() * 60 * 1000);
}
