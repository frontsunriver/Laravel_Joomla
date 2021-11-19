'use strict';

function whichTransitionEvent() {
    var t;
    var el = document.createElement('fakeelement');
    var transitions = {
        'transition': 'transitionend',
        'OTransition': 'oTransitionEnd',
        'MozTransition': 'transitionend',
        'WebkitTransition': 'webkitTransitionEnd'
    };

    for (t in transitions) {
        if (el.style[t] !== undefined) {
            return transitions[t];
        }
    }
}

Array.prototype.remove = function () {
    var what, a = arguments, L = a.length, ax;
    while (L && this.length) {
        what = a[--L];
        while ((ax = this.indexOf(what)) !== -1) {
            this.splice(ax, 1);
        }
    }
    return this;
};

Object.size = function (obj) {
    let size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
};
jQuery(document).ready(function () {
    setTimeout(function () {
        $('.page-loading').hide();
    }, 500);
});

(function ($) {

    let body = $('body');

    $.fn.hhTabsCalculation = function (options) {
        var settings = $.extend({
            'tabItemMargin': 10
        }, options);

        return this.each(function () {
            let $container = $(this),
                tabs = $('[data-tabs]', $container);
            let containerWidth = $container.width(),
                tabsItemWidth = 0;
            let position = -1;

            if ($('.hh-tabs-toggle .dropdown-menu .nav-item', $container).length) {
                $('.hh-tabs-toggle', $container).remove();
                $('[data-tabs-item]', tabs).show();
            }
            $('[data-tabs-item]', tabs).each(function (index) {
                tabsItemWidth += parseFloat($(this).outerWidth()) + settings.tabItemMargin;
                if (tabsItemWidth > containerWidth) {
                    position = index;
                    return false;
                }
            });

            if (position > 0) {
                $('.hh-tabs-toggle', $container).remove();
                $container.append('<div class="hh-tabs-toggle dropdown"><button class="btn btn-secondary dropdown-toggle" type="button" id="dropdown-tab-option" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fe-align-right"></i>' +
                    '</button><ul class="dropdown-menu dropdown-menu-right " aria-labelledby="dropdown-tab-option"></ul></div>');
                $('[data-tabs-item]', tabs).each(function (index) {
                    if (index >= position) {
                        let html = '<li class="nav-item dropdown-item" data-tabs-item><a href="' + $(this).attr('href') + '" class="nav-link">' + $(this).html() + '</a></li>';
                        $('.hh-tabs-toggle .dropdown-menu', $container).append(html);
                        $(this).hide();
                    }
                });
                $container.find('.hh-tabs-toggle .nav-link').click(function (event) {
                    event.preventDefault();
                    let href = $(this).attr('href');

                    if ($('a[href="' + href + '"]', $container).length) {
                        $('a[href="' + href + '"]', $container).tab('show');
                    }
                });
            }
        });
    };

    $.fn.hhCallbackAction = function (callback) {
        switch (callback) {
            case 'login':
                $('#hh-login-modal', body).modal('show');
                break;
            case 'register':
                $('#hh-register-modal', body).modal('show');
                break;
            case 'reset_password':
                $('#hh-fogot-password-modal', body).modal('show');
                break;
        }
    };

    $(document).ready(function () {
        $('[data-tabs-calculation]').hhTabsCalculation();

        let timeoutResizeTab = false;
        $(window).resize(function () {
            clearTimeout(timeoutResizeTab);
            timeoutResizeTab = setTimeout(function () {
                $('[data-tabs-calculation]').hhTabsCalculation();
            }, 100);
        });
    });


    if (typeof hh_params !== 'undefined')
        moment.tz.setDefault(hh_params.timezone);


    let HHActions = {
        isValidated: {},
        init: function (el) {
            this.initGlobal(el);
            this.initMobileMenu(body);
            this.initSticky(body);
            this.initDatePicker(body);
            this.initUpDownNumber(body);
            this.initMapbox(body);
            this.initCheckboxAction(el);
            this.initSelectAction(el);
            this.initLinkAction(el);
            this.initFormAction(el);
            this.initTable(el);
            this.initValidation(el);
            this.initOnOff(body);
            this.initRangeSlider(body);
            this.initSlider(body);
            this.initOwlCarousel(body);
            this.initModelContent(body);
            this.initMatchHeight(body);
            this.initScroll(body);
            this.initSelect(body);
            this.initIframePopup(body);
            this.initGDPR(body);
            this.initIconSVG(body);
            this.initLazyLoad(body);
            this.initAvailability(body);
        },
        initAvailability: function (el){
            $('.hh-availability-wrapper', el).each(function () {
                var t = $(this);
                var container = $('.hh-availability', t);
                var calendar = $('.calendar_input', container);
                var options = {
                    parentEl: container,
                    showCalendar: true,
                    alwaysShowCalendars: true,
                    singleDatePicker: false,
                    sameDate: true,
                    autoApply: true,
                    position: 'static',
                    dateFlex: true,
                    disabledPast: true,
                    dateFormat: calendar.attr('data-date-format') || 'DD/MM/YYYY',
                    enableLoading: true,
                    showEventTooltip: false,
                    classNotAvailable: ['disabled', 'off'],
                    disableHightLight: true,
                    breakEvent: false,
                    autoResponsive: true,
                    fetchEvents: function (start, end, el, callback) {
                        var events = [];
                        if (el.flag_get_events) {
                            return false;
                        }
                        el.flag_get_events = true;
                        el.container.find('.hh-loading').show();
                        var data = {
                            start: start.format('YYYY-MM-DD'),
                            end: end.format('YYYY-MM-DD'),
                            post_id: calendar.data('id'),
                            post_encrypt: calendar.data('encrypt'),
                            _token: $('meta[name="csrf-token"]').attr('content')
                        };
                        $.post(calendar.data('action'), data, function (respon) {
                            if (typeof respon === 'object') {
                                if (typeof respon.events === 'object') {
                                    events = respon.events;
                                }
                            } else {
                                console.log('Can not get data');
                            }
                            callback(events, el);
                            el.flag_get_events = false;
                            el.container.find('.hh-loading').hide();
                        }, 'json');
                    }
                };
                if (typeof locale_daterangepicker == 'object') {
                    options.locale = locale_daterangepicker;
                }
                calendar.daterangepicker(options, function (start, end, label, elmdate, el) {

                });
                let dp = calendar.data('daterangepicker');
                dp.show();
            });
        },
        initIconSVG: function (el) {
            if (typeof hh_params == 'object' && hh_params.lazy_load === 'on') {
                let icons = localStorage.getItem('hh_icons');
                if (typeof icons == 'string') {
                    return false;
                }
                let data = {
                    '_token': $('meta[name="csrf-token"]').attr('content')
                };
                $.post(hh_params.set_icon_url, data, function (respon) {
                    if (typeof respon == 'object') {
                        localStorage.setItem("hh_icons", JSON.stringify(respon.icons));
                    }
                }, 'json');
            }

        },
        getIconSVG: function (icon_name = '', color = '', width = '', height = '', stroke = 'false') {
            let icons = localStorage.getItem("hh_icons");
            let svg = '';
            if (typeof icons == 'string') {
                icons = JSON.parse(icons);
                if (icons.hasOwnProperty(icon_name)) {
                    svg = icons[icon_name];
                    if (color !== '') {
                        if (stroke === 'true') {
                            svg = svg.replace(/stroke="(.{7})"/gm, 'stroke="' + color + '"');
                            svg = svg.replace('/stroke:(.{7})/m', 'stroke:' + color);
                        } else {
                            svg = svg.replace(/fill="(.{7})"/gm, 'fill="' + color + '"');
                            svg = svg.replace(/fill:(.{7})/gm, 'fill:' + color);
                        }
                    }
                    if (width !== '') {
                        svg = svg.replace(/width="(\d{2}[a-z]{2})"/g, 'width="' + width + '"');
                    }

                    if (height !== '') {
                        svg = svg.replace(/height="(\d{2}[a-z]{2})"/g, 'height="' + height + '"');
                    }
                }
            }
            return svg;
        },
        initLazyLoad: function (el) {
            if (typeof hh_params == 'object' && hh_params.lazy_load === 'on') {
                let base = this;

                $.fn.lazyScrollLoading && el.lazyScrollLoading({
                    lazyItemSelector: ".lazy, .lazy-background, .lazy-svg",
                    onLazyItemFirstVisible: function (e, $lazyItems, $firstVisibleLazyItems) {
                        $firstVisibleLazyItems.each(function () {
                            let item = this;

                            if ($(item).hasClass('lazy')) {
                                base.initLazyImage(item);
                            }
                            if ($(item).hasClass('lazy-svg')) {
                                base.initLazySVG(item);
                            }

                            if ($(item).hasClass('lazy-background')) {
                                base.initLazyBackground(item);
                            }
                        });
                    },
                });
            }
        },
        initLazyBackground: function (item) {
            let base = this;
            item.setAttribute('style', 'background-image: url(' + item.getAttribute('data-src') + ')');
            item.classList.add('loaded');
        },
        initLazyImage: function (item) {
            let base = this;
            item.setAttribute('src', item.getAttribute('data-src'));
            item.classList.add('loaded');
            item.parentElement.classList.remove('parent-lazy');
        },
        initLazySVG: function (item) {
            let base = this;
            let name = item.getAttribute("data-name"),
                color = item.getAttribute("data-color"),
                width = item.getAttribute("data-width"),
                height = item.getAttribute("data-height"),
                stroke = item.getAttribute("data-stroke");
            let svg = base.getIconSVG(name, color, width, height, stroke);
            if (svg === '') {
                let data = {
                    'name': name,
                    'color': color,
                    'width': width,
                    'height': height,
                    'stroke': stroke,
                    '_token': $('meta[name="csrf-token"]').attr('content')
                };
                $.post(hh_params.get_icon_url, data, function (respon) {
                    if (typeof respon == 'object') {
                        svg = respon.icon;
                        item.parentElement.innerHTML = svg;
                    }
                }, 'json');
            } else {
                item.parentElement.classList.remove('parent-lazy-svg');
                item.parentElement.innerHTML = svg;
            }
        },
        initGDPR: function (el) {
            if (typeof hh_params == 'object' && hh_params.gdpr.enable === 'on') {
                gdprCookieNotice({
                    locale: 'en', //This is the default value
                    timeout: 500, //Time until the cookie bar appears
                    expiration: 30, //This is the default value, in days
                    domain: '', //If you run the same cookie notice on all subdomains, define the main domain starting with a .
                    implicit: true, //Accept cookies on scroll
                    statement: hh_params.gdpr.page, //Link to your cookie statement page
                    performance: ['JSESSIONID'], //Cookies in the performance category.
                    analytics: ['ga'], //Cookies in the analytics category.
                    marketing: ['SSID'] //Cookies in the marketing category.
                });
            }
        },
        initSticky: function (el) {
            $.fn.sticky && $('.has-sticky', el).each(function () {
                let t = $(this);
                t.sticky({
                    topSpacing: 0,
                    zIndex: 100
                });
                t.on('sticky-start', function (ev, s) {
                    el.addClass('header-sticky-run');
                });

                t.on('sticky-end', function (ev, s) {
                    el.removeClass('header-sticky-run');
                });


            });
        },
        initIframePopup: function (el) {
            $.fn.magnificPopup && $('.hh-iframe-popup', el).each(function () {
                let t = $(this);
                t.magnificPopup({
                    removalDelay: 500,
                    type: 'iframe',
                    mainClass: 'mfp-zoom-in',
                    fixedContentPos: true
                });
            })
        },

        initSelect: function (el) {
            $.fn.niceSelect && $('[data-plugin="customselect"]', el).niceSelect();
            $.fn.select2 && $('[data-toggle="select2"]', el).select2();
        },
        initScroll: function (el) {
            setTimeout(function () {
                $.fn.slimScroll && $('.hh-slimscroll', el).slimScroll({
                    height: false,
                    alwaysVisible: false,
                    railVisible: false,
                    railOpacity: 0,
                    wheelStep: 10,
                    size: 8,
                    color: "#CCC",
                    allowPageScroll: false,
                    disableFadeOut: false
                });
            }, 500);
        },
        initOwlCarousel: function (el) {
            let base = this;
            $.fn.owlCarousel && $('.hh-carousel', el).each(function () {
                let t = $(this),
                    owl = $('.owl-carousel', t),
                    responsive = JSON.parse(Base64.decode(t.data('responsive'))),
                    margin = t.data('margin'),
                    loop = t.data('loop');

                let options = {
                    margin: (typeof margin != 'undefined') ? margin : 15,
                    loop: loop,
                    rtl: hh_params.rtl
                };
                if (typeof responsive == 'object') {
                    options['responsive'] = responsive;
                    options['onChanged'] = callbackOwl;
                }

                owl.owlCarousel(options);

                $('[data-toggle="tooltip"]').tooltip();

                $('.next', t).click(function () {
                    owl.trigger('next.owl.carousel');
                    $('[data-toggle="tooltip"]').tooltip();
                    base.initLazyLoad(owl);
                });

                $('.prev', t).click(function () {
                    owl.trigger('prev.owl.carousel');
                    $('[data-toggle="tooltip"]').tooltip();
                    base.initLazyLoad(owl);
                });
            });

            function callbackOwl(event) {
                var element = event.target;
                var pages = event.page.count;
                var page = event.page.index;
                if (page == -1) {
                    $(element).closest('.hh-carousel').find('.owl-nav .prev').addClass('disabled');
                } else {
                    $(element).closest('.hh-carousel').find('.owl-nav .prev').removeClass('disabled');
                }
                if (page >= pages) {
                    $(element).closest('.hh-carousel').find('.owl-nav .next').addClass('disabled');
                } else {
                    $(element).closest('.hh-carousel').find('.owl-nav .next').removeClass('disabled');
                }
            }
        },
        initModelContent: function (el) {
            $('.hh-get-modal-content', el).on('show.bs.modal', function (ev) {
                var t = $(this),
                    loader = $('.hh-loading', t),
                    target = $(ev.relatedTarget);

                var data = JSON.parse(Base64.decode(target.attr('data-params')));
                if (typeof data == 'object') {
                    data['_token'] = $('meta[name="csrf-token"]').attr('content');

                    loader.show();
                    $('.modal-body', t).empty();

                    $.post(t.attr('data-url'), data, function (respon) {
                        if (typeof respon == 'object') {
                            if (respon.status === 1) {
                                $('.modal-body', t).html(respon.html);
                                $('body').trigger('hh_modal_render_content', [t]);
                            } else {
                                base.alert(respon);
                            }
                        }
                        loader.hide();
                    }, 'json');
                } else {
                    alert('Have a error when parse the data');
                }
            });
        },
        initSlider: function (el) {
            $.fn.otsSlider && $('[data-slider="ots-slider"]', el).otsSlider({
                autoplay: true,
                control: false,
                pagination: false,
                effect: 'ots-slider-fade'
            });

            $.fn.otsSlider && $('[data-slider="ots-stick-slider"]', el).otsSlicker({
                items: 5,
                margin: 10
            });
        },
        initRangeSlider: function (el) {
            $('[data-plugin="ion-range-slider"]', el).each(function () {
                let elRanger = $(this);
                let min = parseFloat($(this).data().min),
                    max = parseFloat($(this).data().max);
                elRanger.ionRangeSlider({
                    skin: $(this).data('skin'),
                    min: min,
                    max: max,
                    from: parseFloat($(this).data().from),
                    to: parseFloat($(this).data().to),
                    type: "double",
                    prefix: $(this).data().prefix,
                    onFinish: function (data) {
                        elRanger.trigger('hh_ranger_changed');
                    },
                    prettify: function (num) {
                        if (hh_params.rtl) {
                            let tmp_min = min,
                                tmp_max = max,
                                tmp_num = num;

                            if (min < 0) {
                                tmp_min = 0;
                                tmp_max = max - min;
                                tmp_num = num - min;
                                tmp_num = tmp_max - tmp_num;
                                return tmp_num + min;
                            } else {
                                num = max - num;
                                return num;
                            }
                        } else {
                            return num;
                        }
                    }
                });
            });
        },
        initOnOff: function (el) {
            if ($('[data-plugin="switchery"]', el).length) {
                $('[data-plugin="switchery"]', el).each(function () {
                    let el = $(this).get(0);
                    let size = $(this).attr('data-size');
                    new Switchery(el, {
                        color: $(this).data('color'),
                        size: typeof size == 'string' ? size : 'default'
                    });
                });
            }
        },
        resetUpdownNumber: function (el) {
            let base = this;
            $('.guest-group', el).each(function () {
                let parent = $(this),
                    button = $('.dropdown-toggle', parent);

                $('input[name="num_adults"]', parent).val(1);
                $('input[name="num_children"]', parent).val(0);
                $('input[name="num_infants"]', parent).val(0);
                base.renderText(button, parent);
            });
        },
        renderText: function (button, parent) {
            let html = '';
            let adult = parseInt($('input[name="num_adults"]', parent).val()),
                child = parseInt($('input[name="num_children"]', parent).val()),
                infant = parseInt($('input[name="num_infants"]', parent).val());
            if (adult + child >= 2) {
                html += (adult + child) + ' ' + button.data('text-guests');
            } else {
                html += (adult + child) + ' ' + button.data('text-guest');
            }
            if (infant > 0) {
                if (infant >= 2) {
                    html += ', ' + infant + ' ' + button.data('text-infants');
                } else {
                    html += ', ' + infant + ' ' + button.data('text-infant');
                }
            }
            button.text(html);
        },
        initUpDownNumber: function (el) {
            let base = this;
            $('.guest-group', el).each(function () {
                let parent = $(this),
                    button = $('.dropdown-toggle', parent);
                $('input[type="number"]', el).focus(function () {
                    $(this).blur();
                });

                base.renderText(button, parent);
                $('.decrease', parent).click(function (ev) {
                    ev.preventDefault();
                    ev.stopPropagation();
                    let input = $(this).parent().find('input'),
                        min = parseInt(input.attr('min')),
                        step = parseInt(input.attr('step')),
                        val = parseInt(input.val());
                    if (val - step >= min) {
                        val -= step;
                        input.val(val).change();
                        base.renderText(button, parent);
                    }
                    $('.guest-group', el).trigger('hh_updown_decrease');
                });
                $('.increase', parent).click(function (ev) {
                    ev.preventDefault();
                    ev.stopPropagation();
                    let input = $(this).parent().find('input'),
                        max = parseInt(input.attr('max')),
                        step = parseInt(input.attr('step')),
                        val = parseInt(input.val());
                    if (val + step <= max) {
                        val += step;
                        input.val(val).change();
                        base.renderText(button, parent);
                    }
                    $('.guest-group', el).trigger('hh_updown_increase');
                });
            });
        },
        initDatePicker: function (el) {
            $('.hh-search-form .date', el).each(function (e) {
                let t = $(this),
                    checkIn = $('.check-in-field', t),
                    checkInTime = $('.check-in-time-field', t),
                    checkInRender = $('.check-in-render', t),
                    checkOut = $('.check-out-field', t),
                    checkOutTime = $('.check-out-time-field', t),
                    checkInOutRender = $('.check-out-render', t),
                    input = $('.check-in-out-field', t);

                let singlePicker = (t.hasClass('date-single')),
                    singleClick = typeof t.data('single-click') !== 'undefined' ? t.data('single-click') : true,
                    sameDate = typeof t.data('same-date') !== 'undefined' ? t.data('same-date') : singlePicker;

                let checkInVal = checkIn.val() || moment().format('YYYY-MM-DD'),
                    checkOutVal = checkOut.val() || moment().add('1', 'days').format('YYYY-MM-DD'),
                    checkInTimeVal = checkInTime.val() || '12:00 am',
                    checkOutTimeVal = checkOutTime.val() || '12:00 am';

                let options = {
                    onlyShowCurrentMonth: true,
                    showCalendar: false,
                    alwaysShowCalendars: false,
                    singleDatePicker: singlePicker,
                    sameDate: sameDate,
                    singleClick: singleClick,
                    autoApply: true,
                    disabledPast: true,
                    dateFormat: 'YYYY-MM-DD',
                    enableLoading: true,
                    showEventTooltip: true,
                    customClass: 'hh-search-form-calendar',
                    classNotAvailable: ['disabled', 'off'],
                    disableHighLight: true,
                    autoResponsive: true,
                    startDate: moment(checkInVal + ' ' + checkInTimeVal, 'YYYY-MM-DD hh:mm a'),
                    endDate: moment(checkOutVal + ' ' + checkOutTimeVal, 'YYYY-MM-DD hh:mm a'),
                };
                if (typeof locale_daterangepicker === 'object') {
                    options.locale = locale_daterangepicker;
                }
                input.daterangepicker(options,
                    function (start, end, label) {
                        if (start !== null && end !== null) {
                            checkIn.val(start.format('YYYY-MM-DD'));
                            checkInTime.val(start.format('hh:mm a'));

                            checkInRender.text(start.format(checkInRender.data('date-format')));
                            checkInOutRender.text(end.format(checkInOutRender.data('date-format')));

                            checkOut.val(end.format('YYYY-MM-DD'));
                            checkOutTime.val(end.format('hh:mm a'));
                            input.trigger('daterangepicker_change', [start, end, label]);

                            let formWrapper = t.closest('form'),
                                timeWrapper = $('.date-wrapper.date-time', formWrapper);

                            if (timeWrapper.length) {
                                let checkInTime = $('[name="checkInTime"]', timeWrapper),
                                    checkOutTime = $('[name="checkOutTime"]', timeWrapper),
                                    checkInDate = start.format('YYYY-MM-DD'),
                                    checkOutDate = end.format('YYYY-MM-DD');

                                if (checkInDate === checkOutDate &&
                                    checkInTime.val() === checkOutTime.val() &&
                                    checkInTime.val() !== '' &&
                                    checkOutTime.val() !== '') {
                                    let checkInDropdown = $('.check-in-render .dropdown-time', timeWrapper),
                                        checkOutDropdown = $('.check-out-render .dropdown-time', timeWrapper),
                                        currentTime = checkInDropdown.find('.item.active'),
                                        pos = currentTime.index();

                                    $('.item', checkOutDropdown).removeClass('disable');
                                    $('.item', checkOutDropdown).eq(pos).next().click();
                                    $('.item', checkOutDropdown).eq(pos).addClass('disable').prevAll().addClass('disable');
                                }
                            }
                        }
                    });
                checkInRender.click(function () {
                    input.trigger('click');
                });
                checkInOutRender.click(function () {
                    input.trigger('click');
                });

                let dp = input.data('daterangepicker');
                dp.updateView();
                dp.isShowing = true;
                dp.hide(e, true);
            });

            $('.hh-search-bar-buttons .button-date', body).each(function (e) {
                let t = $(this),
                    checkInOut = $('.check-in-out-field', t),
                    checkIn = $('.check-in-field', t),
                    checkOut = $('.check-out-field', t),
                    checkInTime = $('.check-in-time-field', t),
                    checkOutTime = $('.check-out-time-field', t),
                    render = $('.text', t);

                let singlePicker = (t.hasClass('button-date-single')),
                    singleClick = typeof t.data('single-click') !== 'undefined' ? t.data('single-click') : true,
                    sameDate = typeof t.data('same-date') !== 'undefined' ? t.data('same-date') : singlePicker;

                let checkInVal = checkIn.val() || moment().format('YYYY-MM-DD'),
                    checkOutVal = checkOut.val() || moment().add('1', 'days').format('YYYY-MM-DD'),
                    checkInTimeVal = checkInTime.val() || '12:00 am',
                    checkOutTimeVal = checkOutTime.val() || '12:00 am',
                    hasDate = !!(checkIn.val() && checkOut.val());

                let options = {
                    onlyShowCurrentMonth: true,
                    showCalendar: false,
                    alwaysShowCalendars: false,
                    singleDatePicker: singlePicker,
                    sameDate: sameDate,
                    singleClick: singleClick,
                    autoApply: true,
                    disabledPast: true,
                    dateFormat: 'YYYY-MM-DD',
                    enableLoading: true,
                    showEventTooltip: true,
                    customClass: '',
                    classNotAvailable: ['disabled', 'off'],
                    disableHighLight: true,
                    autoResponsive: true,
                    startDate: moment(checkInVal + ' ' + checkInTimeVal, 'YYYY-MM-DD hh:mm a'),
                    endDate: moment(checkOutVal + ' ' + checkOutTimeVal, 'YYYY-MM-DD hh:mm a'),
                };

                if (typeof locale_daterangepicker === 'object') {
                    options.locale = locale_daterangepicker;
                }

                checkInOut.daterangepicker(options,
                    function (start, end, label) {
                        if (start !== null && end !== null && (hasDate || label == 'clicked_date')) {
                            checkIn.val(start.format('YYYY-MM-DD'));
                            checkOut.val(end.format('YYYY-MM-DD'));
                            checkInTime.val(start.format('hh:mm a'));
                            checkOutTime.val(end.format('hh:mm a'));
                            if (singlePicker) {
                                render.text(start.format(t.data('date-format')));
                            } else {
                                render.text(start.format(t.data('date-format')) + ' - ' + end.format(t.data('date-format')));
                            }
                            checkInOut.trigger('daterangepicker_change', [start, end, label]);
                        }
                    });

                let dp = checkInOut.data('daterangepicker');
                dp.updateView();
                dp.isShowing = true;
                dp.hide(e, true);

                t.click(function () {
                    dp.show();
                });

            });

            $('#show-filter-mobile', body).click(function () {
                $('.filter-mobile-box .button-date', body).each(function (e) {
                    let t = $(this),
                        checkInOut = $('.check-in-out-field', t),
                        checkIn = $('.check-in-field', t),
                        checkOut = $('.check-out-field', t),
                        checkInTime = $('.check-in-time-field', t),
                        checkOutTime = $('.check-out-time-field', t),
                        render = $('.text', t);

                    let singlePicker = (t.hasClass('button-date-single')),
                        singleClick = typeof t.data('single-click') !== 'undefined' ? t.data('single-click') : true,
                        sameDate = typeof t.data('same-date') !== 'undefined' ? t.data('same-date') : (t.hasClass('button-same-date'));

                    let checkInVal = checkIn.val() || moment().format('YYYY-MM-DD'),
                        checkOutVal = checkOut.val() || moment().add('1', 'days').format('YYYY-MM-DD'),
                        checkInTimeVal = checkInTime.val() || '12:00 am',
                        checkOutTimeVal = checkOutTime.val() || '12:00 am',
                        hasDate = !!(checkIn.val() && checkOut.val());

                    let options = {
                        parentEl: t,
                        onlyShowCurrentMonth: true,
                        showCalendar: true,
                        alwaysShowCalendars: true,
                        singleDatePicker: singlePicker,
                        sameDate: sameDate,
                        singleClick: singleClick,
                        autoApply: true,
                        disabledPast: true,
                        dateFormat: 'YYYY-MM-DD',
                        enableLoading: true,
                        showEventTooltip: true,
                        customClass: 'calendar-popup-filter',
                        classNotAvailable: ['disabled', 'off'],
                        disableHighLight: true,
                        autoResponsive: false,
                        startDate: moment(checkInVal + ' ' + checkInTimeVal, 'YYYY-MM-DD hh:mm a'),
                        endDate: moment(checkOutVal + ' ' + checkOutTimeVal, 'YYYY-MM-DD hh:mm a'),
                    };

                    if (typeof locale_daterangepicker === 'object') {
                        options.locale = locale_daterangepicker;
                    }
                    checkInOut.daterangepicker(options,
                        function (start, end, label) {
                            if (start !== null && end !== null && (hasDate || label == 'clicked_date')) {
                                checkIn.val(start.format('YYYY-MM-DD'));
                                checkOut.val(end.format('YYYY-MM-DD'));
                                checkInTime.val(start.format('hh:mm a'));
                                checkOutTime.val(end.format('hh:mm a'));
                                if (singlePicker) {
                                    render.text(start.format(t.data('date-format')));
                                } else {
                                    render.text(start.format(t.data('date-format')) + ' - ' + end.format(t.data('date-format')));
                                }
                                checkInOut.trigger('daterangepicker_change', [start, end, label]);

                                let formWrapper = t.closest('.filter-mobile-box'),
                                    timeWrapper = $('.date-wrapper.date-time', formWrapper);

                                if (timeWrapper.length) {
                                    let checkInTime = $('[name="checkInTime"]', timeWrapper),
                                        checkOutTime = $('[name="checkOutTime"]', timeWrapper),
                                        checkInDate = start.format('YYYY-MM-DD'),
                                        checkOutDate = end.format('YYYY-MM-DD');

                                    if (checkInDate === checkOutDate &&
                                        checkInTime.val() === checkOutTime.val() &&
                                        checkInTime.val() !== '' &&
                                        checkOutTime.val() !== '') {
                                        let checkInDropdown = $('.check-in-render .dropdown-time', timeWrapper),
                                            checkOutDropdown = $('.check-out-render .dropdown-time', timeWrapper),
                                            currentTime = checkInDropdown.find('.item.active'),
                                            pos = currentTime.index();

                                        $('.item', checkOutDropdown).eq(pos).next().click();
                                        $('.item', checkOutDropdown).eq(pos).addClass('disable').prevAll().addClass('disable');
                                    }
                                }
                            }
                        });

                    let dp = checkInOut.data('daterangepicker');

                    dp.updateView();
                    dp.isShowing = true;
                    dp.hide(e, true);
                });
            });

            $('#form-book-home .date-double', body).each(function (e) {
                let t = $(this),
                    form = t.closest('form'),
                    checkIn = $('.check-in-field', t),
                    checkInRender = $('.check-in-render', t),
                    checkOut = $('.check-out-field', t),
                    checkInOutRender = $('.check-out-render', t),
                    input = $('.check-in-out-field', t);
                let options = {
                    parentEl: input,
                    onlyShowCurrentMonth: true,
                    showCalendar: false,
                    alwaysShowCalendars: false,
                    singleDatePicker: false,
                    sameDate: false,
                    autoApply: true,
                    disabledPast: true,
                    dateFormat: 'YYYY-MM-DD',
                    enableLoading: true,
                    showEventTooltip: true,
                    customClass: '',
                    classNotAvailable: ['disabled', 'off'],
                    startDate: moment().startOf('day'),
                    endDate: moment().add(1, 'days').startOf('day'),
                    disableHighLight: true,
                    autoResponsive: true,
                    maybeFixed: '.form-book',
                    fetchEvents: function (start, end, el, callback) {
                        let events = [];
                        if (el.flag_get_events) {
                            return false;
                        }
                        el.flag_get_events = true;
                        el.container.find('.hh-loading').show();
                        let data = {
                            startTime: start.format('YYYY-MM-DD'),
                            endTime: end.format('YYYY-MM-DD'),
                            homeID: input.data('home-id'),
                            homeEncrypt: input.data('home-encrypt'),
                            numberAdult: $('input[name="num_adults"]', form).val(),
                            numberChild: $('input[name="num_children"]', form).val(),
                            numberInfant: $('input[name="num_infants"]', form).val(),
                            _token: $('meta[name="csrf-token"]').attr('content')
                        };
                        $.post(t.attr('data-action'), data, function (respon) {
                            if (typeof respon === 'object') {
                                if (typeof respon.events === 'object') {
                                    events = respon.events;
                                }
                            } else {
                                console.log('Can not get data');
                            }
                            callback(events, el);
                            el.flag_get_events = false;
                            el.container.find('.hh-loading').hide();
                        }, 'json');
                    }
                };
                if (typeof locale_daterangepicker === 'object') {
                    options.locale = locale_daterangepicker;
                }
                input.daterangepicker(options,
                    function (start, end, label) {
                        if (start !== null && end !== null) {
                            checkIn.val(start.format('YYYY-MM-DD'));
                            checkInRender.text(start.format(checkInRender.data('date-format')));
                            checkOut.val(end.format('YYYY-MM-DD'));
                            checkInOutRender.text(end.format(checkInOutRender.data('date-format')));
                            input.trigger('daterangepicker_change', [start, end, label]);
                        }
                    });
                checkInRender.click(function () {
                    input.trigger('click');
                });
                checkInOutRender.click(function () {
                    input.trigger('click');
                });

                let dp = input.data('daterangepicker');

                dp.updateView();
                dp.isShowing = true;
                dp.hide(e, true);
            });

            $('#form-book-home .date-single', body).each(function (e) {
                let t = $(this),
                    form = t.closest('form'),
                    checkIn = $('.check-in-field', t),
                    checkInRender = $('.check-in-render', t),
                    checkOut = $('.check-out-field', t),
                    input = $('.check-in-out-single-field', t);
                let options = {
                    parentEl: input,
                    onlyShowCurrentMonth: true,
                    showCalendar: false,
                    alwaysShowCalendars: false,
                    singleDatePicker: true,
                    sameDate: true,
                    autoApply: true,
                    disabledPast: true,
                    dateFormat: 'YYYY-MM-DD',
                    enableLoading: true,
                    showEventTooltip: true,
                    customClass: '',
                    classNotAvailable: ['disabled', 'off'],
                    startDate: moment().startOf('day'),
                    endDate: moment().startOf('day'),
                    disableHighLight: true,
                    autoResponsive: true,
                    maybeFixed: '.form-book',
                    fetchEvents: function (start, end, el, callback) {
                        let events = [];
                        if (el.flag_get_events) {
                            return false;
                        }
                        el.flag_get_events = true;
                        el.container.find('.hh-loading').show();
                        let data = {
                            startTime: start.format('YYYY-MM-DD'),
                            endTime: end.format('YYYY-MM-DD'),
                            homeID: input.data('home-id'),
                            homeEncrypt: input.data('home-encrypt'),
                            numberAdult: $('input[name="num_adults"]', form).val(),
                            numberChild: $('input[name="num_children"]', form).val(),
                            numberInfant: $('input[name="num_infants"]', form).val(),
                            _token: $('meta[name="csrf-token"]').attr('content')
                        };
                        $.post(t.attr('data-action'), data, function (respon) {
                            if (typeof respon === 'object') {
                                if (typeof respon.events === 'object') {
                                    events = respon.events;
                                }
                            } else {
                                console.log('Can not get data');
                            }
                            callback(events, el);
                            el.flag_get_events = false;
                            el.container.find('.hh-loading').hide();
                        }, 'json');
                    }
                };
                if (typeof locale_daterangepicker === 'object') {
                    options.locale = locale_daterangepicker;
                }
                input.daterangepicker(options,
                    function (start, end, label) {
                        if (start !== null && end !== null) {
                            checkIn.val(start.format('YYYY-MM-DD'));
                            checkInRender.text(start.format(checkInRender.data('date-format')));
                            checkOut.val(end.format('YYYY-MM-DD'));
                            input.trigger('daterangepicker_change', [start, end, label]);
                        }
                    });
                let dp = input.data('daterangepicker');
                dp.updateView();
                dp.isShowing = true;
                dp.hide(e, true);

                checkInRender.click(function () {
                    dp.show();
                });
            });

            $('#form-book-car .date-double', body).each(function (e) {
                let t = $(this),
                    form = t.closest('form'),
                    checkIn = $('.check-in-field', t),
                    checkInRender = $('.check-in-render', t),
                    checkOut = $('.check-out-field', t),
                    checkInOutRender = $('.check-out-render', t),
                    input = $('.check-in-out-field', t);

                let singlePicker = (t.hasClass('date-single')),
                    singleClick = typeof t.data('single-click') !== 'undefined' ? t.data('single-click') : true,
                    sameData = typeof t.data('same-date') !== 'undefined' ? t.data('same-date') : singlePicker;

                let options = {
                    parentEl: input,
                    onlyShowCurrentMonth: true,
                    showCalendar: false,
                    alwaysShowCalendars: false,
                    singleDatePicker: singlePicker,
                    sameDate: sameData,
                    singleClick: singleClick,
                    autoApply: true,
                    disabledPast: true,
                    dateFormat: 'YYYY-MM-DD',
                    enableLoading: true,
                    showEventTooltip: true,
                    customClass: '',
                    classNotAvailable: ['disabled', 'off'],
                    startDate: (checkIn.val() !== '') ? checkIn.val() : moment().startOf('day'),
                    endDate: (checkOut.val() !== '') ? checkOut.val() : moment().startOf('day'),
                    disableHighLight: true,
                    autoResponsive: true,
                    maybeFixed: '.form-book',
                    fetchEvents: function (start, end, el, callback) {
                        let events = [];
                        if (el.flag_get_events) {
                            return false;
                        }
                        el.flag_get_events = true;
                        el.container.find('.hh-loading').show();
                        let data = {
                            startTime: start.format('YYYY-MM-DD'),
                            endTime: end.format('YYYY-MM-DD'),
                            carID: input.data('car-id'),
                            carEncrypt: input.data('car-encrypt'),
                            _token: $('meta[name="csrf-token"]').attr('content')
                        };
                        $.post(t.attr('data-action'), data, function (respon) {
                            if (typeof respon === 'object') {
                                if (typeof respon.events === 'object') {
                                    events = respon.events;
                                }
                            } else {
                                console.log('Can not get data');
                            }
                            callback(events, el);
                            el.flag_get_events = false;
                            el.container.find('.hh-loading').hide();
                        }, 'json');
                    }
                };
                if (typeof locale_daterangepicker === 'object') {
                    options.locale = locale_daterangepicker;
                }
                input.daterangepicker(options,
                    function (start, end, label) {
                        if (start !== null && end !== null) {
                            checkIn.val(start.format('YYYY-MM-DD'));
                            checkOut.val(end.format('YYYY-MM-DD'));
                            checkInRender.text(start.format(checkInRender.data('date-format')));
                            checkInOutRender.text(end.format(checkInOutRender.data('date-format')));
                            input.trigger('daterangepicker_change', [start, end, label]);
                        }
                    });
                checkInRender.click(function () {
                    input.trigger('click');
                });
                checkInOutRender.click(function () {
                    input.trigger('click');
                });

                let dp = input.data('daterangepicker');

                dp.updateView();
                dp.isShowing = true;
                dp.hide(e, true);

            });

            $('#form-book-experience .date-wrapper.date_time', body).each(function (e) {
                let t = $(this),
                    form = t.closest('form'),
                    checkIn = $('.check-in-field', t),
                    checkOut = $('.check-out-field', t),
                    input = $('.check-in-out-field', t);
                let options = {
                    parentEl: input,
                    onlyShowCurrentMonth: true,
                    showCalendar: true,
                    alwaysShowCalendars: true,
                    singleDatePicker: true,
                    sameDate: true,
                    autoApply: true,
                    disabledPast: true,
                    dateFormat: 'YYYY-MM-DD',
                    enableLoading: true,
                    showEventTooltip: true,
                    customClass: '',
                    classNotAvailable: ['disabled', 'off'],
                    startDate: moment().startOf('day'),
                    endDate: moment().startOf('day'),
                    disableHighLight: true,
                    autoResponsive: true,
                    maybeFixed: '.form-book',
                    position: 'static',
                    fetchEvents: function (start, end, el, callback) {
                        let events = [];
                        if (el.flag_get_events) {
                            return false;
                        }
                        el.flag_get_events = true;
                        el.container.find('.hh-loading').show();
                        let data = {
                            startTime: start.format('YYYY-MM-DD'),
                            endTime: end.format('YYYY-MM-DD'),
                            experienceID: input.data('experience-id'),
                            experienceEncrypt: input.data('experience-encrypt'),
                            numberAdult: parseInt($('input[name="num_adults"]', form).val()),
                            numberChild: parseInt($('input[name="num_children"]', form).val()),
                            numberInfant: parseInt($('input[name="num_infants"]', form).val()),
                            bookingType: $('input[name="bookingType"]', form).val(),
                            _token: $('meta[name="csrf-token"]').attr('content')
                        };
                        $.post(t.attr('data-action'), data, function (respon) {
                            if (typeof respon === 'object') {
                                if (typeof respon.events === 'object') {
                                    events = respon.events;
                                }
                            } else {
                                console.log('Can not get data');
                            }
                            callback(events, el);
                            el.flag_get_events = false;
                            el.container.find('.hh-loading').hide();
                        }, 'json');
                    }
                };
                if (typeof locale_daterangepicker === 'object') {
                    options.locale = locale_daterangepicker;
                }
                input.daterangepicker(options,
                    function (start, end, label) {
                        if (start !== null && end !== null) {
                            checkIn.val(start.format('YYYY-MM-DD'));
                            checkOut.val(end.format('YYYY-MM-DD'));

                            input.trigger('daterangepicker_change', [start, end, label]);
                        }
                    });
                let dp = input.data('daterangepicker');
                dp.updateView();
                dp.show();
            });

            $('#form-book-experience .date-wrapper.just_date', body).each(function () {
                let t = $(this),
                    form = t.closest('form'),
                    checkIn = $('.check-in-field', t),
                    checkOut = $('.check-out-field', t),
                    input = $('.check-in-out-field', t);
                let options = {
                    parentEl: input,
                    onlyShowCurrentMonth: true,
                    showCalendar: true,
                    alwaysShowCalendars: true,
                    singleDatePicker: true,
                    sameDate: true,
                    autoApply: true,
                    disabledPast: true,
                    dateFormat: 'YYYY-MM-DD',
                    enableLoading: true,
                    showEventTooltip: true,
                    customClass: '',
                    classNotAvailable: ['disabled', 'off'],
                    startDate: moment().startOf('day'),
                    endDate: moment().startOf('day'),
                    disableHighLight: true,
                    autoResponsive: true,
                    maybeFixed: '.form-book',
                    position: 'static',
                    fetchEvents: function (start, end, el, callback) {
                        let events = [];
                        if (el.flag_get_events) {
                            return false;
                        }
                        el.flag_get_events = true;
                        el.container.find('.hh-loading').show();
                        let data = {
                            startTime: start.format('YYYY-MM-DD'),
                            endTime: end.format('YYYY-MM-DD'),
                            experienceID: input.data('experience-id'),
                            experienceEncrypt: input.data('experience-encrypt'),
                            numberAdult: parseInt($('input[name="num_adults"]', form).val()),
                            numberChild: parseInt($('input[name="num_children"]', form).val()),
                            numberInfant: parseInt($('input[name="num_infants"]', form).val()),
                            bookingType: $('input[name="bookingType"]', form).val(),
                            _token: $('meta[name="csrf-token"]').attr('content')
                        };
                        $.post(t.attr('data-action'), data, function (respon) {
                            if (typeof respon === 'object') {
                                if (typeof respon.events === 'object') {
                                    events = respon.events;
                                }
                            } else {
                                console.log('Can not get data');
                            }
                            callback(events, el);
                            el.flag_get_events = false;
                            el.container.find('.hh-loading').hide();
                        }, 'json');
                    }
                };
                if (typeof locale_daterangepicker === 'object') {
                    options.locale = locale_daterangepicker;
                }
                input.daterangepicker(options,
                    function (start, end, label) {
                        if (start !== null && end !== null) {
                            checkIn.val(start.format('YYYY-MM-DD'));
                            checkOut.val(end.format('YYYY-MM-DD'));

                            input.trigger('daterangepicker_change', [start, end, label]);
                        }
                    });
                let dp = input.data('daterangepicker');
                dp.updateView();
                dp.show();
            });

            $('#form-book-experience .date-wrapper.package', body).each(function () {
                let t = $(this),
                    form = t.closest('form'),
                    checkIn = $('.check-in-field', t),
                    checkOut = $('.check-out-field', t),
                    input = $('.check-in-out-field', t);
                let options = {
                    parentEl: input,
                    onlyShowCurrentMonth: true,
                    showCalendar: true,
                    alwaysShowCalendars: true,
                    singleDatePicker: true,
                    sameDate: true,
                    autoApply: true,
                    disabledPast: true,
                    dateFormat: 'YYYY-MM-DD',
                    enableLoading: true,
                    showEventTooltip: true,
                    customClass: '',
                    classNotAvailable: ['disabled', 'off'],
                    startDate: moment().startOf('day'),
                    endDate: moment().startOf('day'),
                    disableHighLight: true,
                    autoResponsive: true,
                    maybeFixed: '.form-book',
                    position: 'static',
                    fetchEvents: function (start, end, el, callback) {
                        let events = [];
                        if (el.flag_get_events) {
                            return false;
                        }
                        el.flag_get_events = true;
                        el.container.find('.hh-loading').show();
                        let data = {
                            startTime: start.format('YYYY-MM-DD'),
                            endTime: end.format('YYYY-MM-DD'),
                            experienceID: input.data('experience-id'),
                            experienceEncrypt: input.data('experience-encrypt'),
                            numberAdult: parseInt($('input[name="num_adults"]', form).val()),
                            numberChild: parseInt($('input[name="num_children"]', form).val()),
                            numberInfant: parseInt($('input[name="num_infants"]', form).val()),
                            bookingType: $('input[name="bookingType"]', form).val(),
                            _token: $('meta[name="csrf-token"]').attr('content')
                        };
                        $.post(t.attr('data-action'), data, function (respon) {
                            if (typeof respon === 'object') {
                                if (typeof respon.events === 'object') {
                                    events = respon.events;
                                }
                            } else {
                                console.log('Can not get data');
                            }
                            callback(events, el);
                            el.flag_get_events = false;
                            el.container.find('.hh-loading').hide();
                        }, 'json');
                    }
                };
                if (typeof locale_daterangepicker === 'object') {
                    options.locale = locale_daterangepicker;
                }
                input.daterangepicker(options,
                    function (start, end, label) {
                        if (start !== null && end !== null) {
                            checkIn.val(start.format('YYYY-MM-DD'));
                            checkOut.val(end.format('YYYY-MM-DD'));

                            input.trigger('daterangepicker_change', [start, end, label]);
                        }
                    });
                let dp = input.data('daterangepicker');
                dp.updateView();
                dp.show();
            });

        },
        initMapbox: function (el) {
            if (typeof mapboxgl === 'object' && hh_params.mapbox_token) {
                if (hh_params.rtl) {
                    mapboxgl.setRTLTextPlugin(
                        'https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-rtl-text/v0.2.3/mapbox-gl-rtl-text.js',
                        null,
                        true // Lazy load the plugin
                    );
                }
                mapboxgl.accessToken = hh_params.mapbox_token;
                $('.hh-mapbox-single', el).each(function () {
                    let t = $(this),
                        lat = parseFloat(t.data('lat')),
                        lng = parseFloat(t.data('lng')),
                        zoom = parseFloat(t.data('zoom')),
                        type = t.data('type') || 'hotel';

                    let map = new mapboxgl.Map({
                        container: t.get(0),
                        style: 'mapbox://styles/mapbox/light-v10',
                        center: [lng, lat],
                        zoom: zoom,
                        dragPan: false
                    });

                    map.scrollZoom.disable();
                    let el = document.createElement('div');
                    el.className = 'hh-marker ' + type;
                    new mapboxgl.Marker(el)
                        .setLngLat([lng, lat])
                        .addTo(map);
                    map.on('style.load', function () {
                        map.addSource('markers', {
                            "type": "geojson",
                            "data": {
                                "type": "FeatureCollection",
                                "features": [{
                                    "type": "Feature",
                                    "geometry": {
                                        "type": "Point",
                                        "coordinates": [lng, lat]
                                    },
                                    "properties": {
                                        "modelId": 1,
                                    },
                                }]
                            }
                        });
                        map.addLayer({
                            "id": "circles1",
                            "source": "markers",
                            "type": "circle",
                            "paint": {
                                "circle-radius": 100,
                                "circle-color": "#969696",
                                "circle-opacity": 0.2,
                                "circle-stroke-width": 0,
                            },
                            "filter": ["==", "modelId", 1],
                        });
                    });
                });
                $('[data-plugin="mapbox-geocoder"]', body).each(function () {
                    let t = $(this);
                    if (typeof mapboxgl === 'object' && mapboxgl.accessToken != '') {
                        let geocoder = new MapboxGeocoder({
                            accessToken: mapboxgl.accessToken,
                            mapboxgl: mapboxgl,
                            language: t.data('lang'),
                            placeholder: t.data().placeholder
                        });
                        let map = new mapboxgl.Map({
                                style: 'mapbox://styles/mapbox/light-v10',
                                container: t.next('.map').get(0)
                            },
                        );

                        t.get(0).appendChild(geocoder.onAdd(map));

                        let oldVal = t.data().value;
                        if (typeof oldVal === 'string') {
                            geocoder.setInput(oldVal);
                        }
                        geocoder.on('result', function (result) {
                            if (typeof result.result.geometry.coordinates === 'object') {
                                t.closest('.form-group').find('input[name="lng"]').attr('value', result.result.geometry.coordinates[0]).trigger('change');
                                t.closest('.form-group').find('input[name="lat"]').attr('value', result.result.geometry.coordinates[1]).trigger('change');
                                t.closest('.form-group').find('input[name="address"]').attr('value', result.result.place_name).trigger('change');
                            }
                        });
                        map.on('load', function () {
                            if ($('.mapboxgl-ctrl-geocoder--input', t).val() === '' && t.data('current-location') == '1') {
                                if (navigator.geolocation) {
                                    navigator.geolocation.getCurrentPosition(function (position) {
                                        if (typeof position === 'object') {
                                            t.closest('.form-group').find('input[name="lat"]').attr('value', position.coords.latitude).trigger('change');
                                            t.closest('.form-group').find('input[name="lng"]').attr('value', position.coords.longitude).trigger('change');
                                            t.closest('.form-group').find('input[name="address"]').attr('value', t.data('your-location')).trigger('change');
                                            geocoder.setInput(t.data('your-location'));
                                        }
                                    });
                                }
                            }
                            $('.mapboxgl-ctrl-geocoder--input', t).trigger('hh_mapbox_input_load');

                            $('.mapboxgl-ctrl-geocoder--input', t).on('focus', function () {
                                $(this).trigger('hh_mapbox_input_focus');
                            });
                            $('.mapboxgl-ctrl-geocoder--input', t).on('blur', function () {
                                $(this).trigger('hh_mapbox_input_blur');
                            });
                        });
                    }
                });

                if ($('#contact-us-map', 'body').length) {
                    let t = $('#contact-us-map', 'body'),
                        map_content = $('.map-render', t),
                        lat = parseFloat(t.data('lat')),
                        lng = parseFloat(t.data('lng'));

                    let map = new mapboxgl.Map({
                        container: map_content.get(0),
                        style: 'mapbox://styles/mapbox/light-v10',
                        center: [lng - 0.018, lat],
                        zoom: 14,
                        offset: [500, 0],
                        dragPan: false
                    });
                    map.scrollZoom.disable();

                    let el = document.createElement('div');
                    el.className = 'hh-marker-contact';
                    new mapboxgl.Marker(el)
                        .setLngLat([lng, lat])
                        .addTo(map);
                    map.on('style.load', function () {
                        map.addSource('markers', {
                            "type": "geojson",
                            "data": {
                                "type": "FeatureCollection",
                                "features": [{
                                    "type": "Feature",
                                    "geometry": {
                                        "type": "Point",
                                        "coordinates": [lng, lat]
                                    },
                                    "properties": {
                                        "modelId": 1,
                                    },
                                }]
                            }
                        });
                        map.addLayer({
                            "id": "circles1",
                            "source": "markers",
                            "type": "circle",
                            "paint": {
                                "circle-radius": 100,
                                "circle-color": "#969696",
                                "circle-opacity": 0.2,
                                "circle-stroke-width": 0,
                            },
                            "filter": ["==", "modelId", 1],
                        });
                    });
                    let rs;
                    clearTimeout(rs);
                    $(window).on('resize', function () {
                        rs = setTimeout(function () {
                            if (window.matchMedia("(max-width:767px)").matches) {
                                map.setCenter([lng, lat]);
                            } else {
                                if (window.matchMedia("(max-width:991px)").matches) {
                                    map.setCenter([lng - 0.01, lat]);
                                } else {
                                    map.setCenter([lng - 0.018, lat]);
                                }
                            }
                        }, 500);
                    }).resize();
                }
            }
        },
        initMatchHeight: function (el) {
            $.fn.matchHeight && $('[data-plugin="matchHeight"]', el).matchHeight();
        },
        initMobileMenu: function (el) {
            let container = $('#mobile-navigation', el);

            container.click(function (ev) {
                if ($(ev.target).is(container)) {
                    $('.mobile-menu', container).removeClass('open');
                    container.removeClass('open-menu deep');
                    $('.body-wrapper', body).removeClass('open-menu');
                }
            });
            $('#toggle-mobile-menu', body).click(function () {
                container.addClass('open-menu');
                $('.body-wrapper', body).addClass('open-menu');
                let zindex = 9 - $('.mobile-menu', container).find('ul').length;
                $('.mobile-menu', container).addClass('open').css({'z-index': zindex});
            });
            $('.toggle-submenu', container).click(function (ev) {
                ev.preventDefault();
                let menu = $(this).next('ul.sub-menu');
                let parent = $(this).closest('ul');
                parent.addClass('deep');
                let zindex = 9 - menu.find('ul').length;
                menu.addClass('open').css({'z-index': zindex});
            });
            $('.submenu-head', container).click(function (ev) {
                ev.preventDefault();
                let menu = $(this).closest('ul');
                let parent = menu.closest('li').parent('ul');
                menu.removeClass('open').css({'z-index': ''});
                parent.removeClass('deep');
            });
            $('.back-menu', container).click(function (ev) {
                ev.preventDefault();
                container.removeClass('open-menu');
                container.find('.hh-navigation').removeClass('open');
                $('.body-wrapper', body).removeClass('open-menu');
            });
        },
        initSelectAction: function (el) {
            var base = this;
            $('body').on('change', '.hh-select-action', function (ev) {
                var t = $(this),
                    parent = t.closest(t.data('parent'));
                ev.preventDefault();
                var data = JSON.parse(Base64.decode(t.attr('data-params')));
                if (typeof data == 'object') {
                    data['val'] = t.val();
                    data['_token'] = $('meta[name="csrf-token"]').attr('content');
                    if ($(parent).length) {
                        $(parent).addClass('is-doing');
                    }
                    $.post(t.attr('data-action'), data, function (respon) {
                        if (typeof respon == 'object') {
                            base.alert(respon);
                            if (respon.redirect) {
                                setTimeout(function () {
                                    window.location.href = respon.redirect;
                                }, 2000);
                            }

                            if ($(parent).length) {
                                $(parent).removeClass('is-doing');
                            }
                            t.trigger('hh_select_action_completed', [respon]);

                            if (respon.reload) {
                                window.location.reload();
                            }
                        }
                    }, 'json');
                } else {
                    alert('Have a error when parse the data');
                }
            });
        },
        initCheckboxAction: function (el) {
            var base = this;
            $('body').on('change', '.hh-checkbox-action', function (ev) {
                var t = $(this),
                    parent = t.closest(t.data('parent'));
                ev.preventDefault();
                var data = JSON.parse(Base64.decode(t.attr('data-params')));
                if (typeof data == 'object') {
                    data['val'] = (t.is(':checked')) ? t.val() : '';
                    data['_token'] = $('meta[name="csrf-token"]').attr('content');
                    if ($(parent).length) {
                        $(parent).addClass('is-doing');
                    }
                    $.post(t.attr('data-action'), data, function (respon) {
                        if (typeof respon == 'object') {
                            base.alert(respon);
                            if (respon.redirect) {
                                setTimeout(function () {
                                    window.location.href = respon.redirect;
                                }, 2000);
                            }

                            if ($(parent).length) {
                                $(parent).removeClass('is-doing');
                            }
                            t.trigger('hh_checkbox_action_completed', [respon]);

                            if (respon.reload) {
                                window.location.reload();
                            }
                        }
                    }, 'json');
                } else {
                    alert('Have a error when parse the data');
                }
            });
        },
        initTable: function (el) {
            var base = this;
            setTimeout(function () {
                $('table[data-plugin="datatable"]', el).each(function () {
                    var t = $(this),
                        columns = t.data('cols') ? JSON.parse(Base64.decode(t.data('cols'))) : [];
                    t.dataTable({
                        dom: 'frtipB',
                        buttons: [
                            {
                                text: t.data('pdf-name'),
                                extend: 'pdfHtml5',
                                customize: function (doc) {
                                    $.each(doc.content[1].table.body[0], function (index, name) {
                                        doc.content[1].table.body[0][index].text = name;
                                    });
                                    $.each(doc.content[1].table.body[0], function (index, item) {
                                        if (typeof columns[index] != 'undefined') {
                                            doc.content[1].table.body[0][index].text = columns[index];
                                        } else {
                                            delete doc.content[1].table.body[0][index];

                                            doc.content[1].table.body[0].length = columns.length;
                                        }
                                    });
                                    $.each(doc.content[1].table.body, function (index, item) {
                                        if (index != 0) {
                                            $.each(item, function (_index, _item) {
                                                if (typeof columns[_index] == 'undefined') {
                                                    delete doc.content[1].table.body[index][_index];
                                                    doc.content[1].table.body[index].length = columns.length;
                                                }
                                            });
                                        }
                                    });
                                },
                                download: 'open'
                            }
                        ]
                    });
                });
            }, 500);
        },
        initFormAction: function (el) {
            let base = this;
            $(document).on('submit', 'form.form-action', function (ev) {
                ev.stopPropagation();
                ev.preventDefault();

                if (typeof CKEDITOR == 'object' && typeof CKEDITOR.instances == 'object') {
                    $.each(CKEDITOR.instances, function (id, $editor) {
                        document.getElementById(id).value = $editor.getData();
                    });
                }

                let form = $(this),
                    url = form.attr('action'),
                    validation_id = form.attr('data-validation-id'),
                    loading = (typeof form.data('loading-from') == 'string') ? form.closest(form.data('loading-from')).find('.hh-loading') : $('.hh-loading', form),
                    reloadTime = form.data('reload-time'),
                    use_captcha = form.data('google-captcha');

                if (typeof reloadTime == 'undefined') {
                    reloadTime = 0;
                }
                base.initValidation(form, true);
                if (typeof base.isValidated[validation_id] == 'object' && Object.size(base.isValidated[validation_id])) {
                    let $el = $('.has-validation.is-invalid', form).first();
                    if ($el.length) {
                        if (form.closest('.modal').length) {
                            $el.focus();
                        } else {
                            $("html, body").animate({scrollTop: $el.offset().top}, 500);
                            $el.focus();
                        }
                    }
                } else {
                    form.trigger('hh_form_action_before', [form]);

                    if (typeof tinyMCE != 'undefined') {
                        tinyMCE.triggerSave();
                    }
                    let data = form.serializeArray();
                    data.push({
                        name: '_token',
                        value: $('meta[name="csrf-token"]').attr('content'),
                    });

                    loading.show();
                    if ($('.form-message', form).length) {
                        $('.form-message', form).empty();
                    }

                    if (typeof use_captcha == 'string' && use_captcha == 'yes' && hh_params.use_google_captcha === 'on') {
                        grecaptcha.ready(function () {
                            grecaptcha.execute(hh_params.google_captcha_key, {action: 'form_action'}).then(function (token) {
                                data.push({
                                    name: 'g-recaptcha-response',
                                    value: token
                                });
                                $.post(url, data, function (respon) {
                                    if (typeof respon === 'object') {
                                        if (respon.force_redirect) {
                                            window.location.href = respon.force_redirect;
                                        } else {
                                            if ($('.form-message', form).length) {
                                                $('.form-message', form).html(respon.message);
                                            } else {
                                                base.alert(respon);
                                            }

                                            form.trigger('hh_form_action_complete', [respon]);

                                            if (form.hasClass('.has-reset')) {
                                                form.get(0).reset();
                                            }
                                            if (respon.redirect) {
                                                setTimeout(function () {
                                                    window.location.href = respon.redirect;
                                                }, reloadTime);
                                            }

                                            if (respon.reload) {
                                                setTimeout(function () {
                                                    window.location.reload();
                                                }, reloadTime);
                                            }
                                        }
                                    }
                                    loading.hide();
                                }, 'json');
                            });
                        });
                    } else {
                        $.post(url, data, function (respon) {
                            if (typeof respon === 'object') {
                                if (respon.force_redirect) {
                                    window.location.href = respon.force_redirect;
                                } else {
                                    if ($('.form-message', form).length) {
                                        $('.form-message', form).html(respon.message);
                                    } else {
                                        base.alert(respon);
                                    }

                                    form.trigger('hh_form_action_complete', [respon]);

                                    if (form.hasClass('.has-reset')) {
                                        form.get(0).reset();
                                    }
                                    if (respon.redirect) {
                                        setTimeout(function () {
                                            window.location.href = respon.redirect;
                                        }, reloadTime);
                                    }

                                    if (respon.reload) {
                                        setTimeout(function () {
                                            window.location.reload();
                                        }, reloadTime);
                                    }
                                }
                            }
                            loading.hide();
                        }, 'json');
                    }
                }
            });
        },
        initLinkAction: function (el) {
            let base = this;
            $('.hh-link-action', el).each(function () {
                let t = $(this),
                    parent = t.closest(t.data('parent'));

                t.click(function (ev) {
                    ev.preventDefault();

                    let dataConfirm = t.data('confirm');
                    if (dataConfirm === 'yes') {
                        $.confirm({
                            animation: 'none',
                            title: t.data('confirm-title'),
                            content: t.data('confirm-question'),
                            buttons: {
                                ok: {
                                    text: t.data('confirm-button'),
                                    btnClass: 'btn-primary',
                                    action: function () {
                                        let data = JSON.parse(Base64.decode(t.attr('data-params')));
                                        if (typeof data == 'object') {
                                            data['_token'] = $('meta[name="csrf-token"]').attr('content');
                                            if ($(parent).length) {
                                                $(parent).addClass('is-doing');
                                            }
                                            $.post(t.attr('data-action'), data, function (respon) {
                                                if (typeof respon == 'object') {
                                                    base.alert(respon);
                                                    t.trigger('hh_link_action_completed', [respon]);

                                                    if (respon.redirect) {
                                                        setTimeout(function () {
                                                            window.location.href = respon.redirect;
                                                        }, 1500);
                                                    }

                                                    if ($(parent).length) {
                                                        $(parent).removeClass('is-doing');
                                                        if (t.attr('data-is-delete')) {
                                                            $(parent).addClass('is-deleted');
                                                            $(parent).one(whichTransitionEvent(), function () {
                                                                $(parent).hide();
                                                            });
                                                        }
                                                    }
                                                    if (respon.reload) {
                                                        window.location.reload();
                                                    }
                                                }
                                            }, 'json');

                                        } else {
                                            alert('Have a error when parse the data');
                                        }
                                    }
                                },
                                cancel: function () {

                                }
                            }
                        });
                    } else {
                        let data = JSON.parse(Base64.decode(t.attr('data-params')));
                        if (typeof data == 'object') {
                            data['_token'] = $('meta[name="csrf-token"]').attr('content');
                            if ($(parent).length) {
                                $(parent).addClass('is-doing');
                            }
                            $.post(t.attr('data-action'), data, function (respon) {
                                if (typeof respon == 'object') {
                                    base.alert(respon);
                                    t.trigger('hh_link_action_completed', [respon]);

                                    if (respon.redirect) {
                                        setTimeout(function () {
                                            window.location.href = respon.redirect;
                                        }, 1500);
                                    }

                                    if ($(parent).length) {
                                        $(parent).removeClass('is-doing');
                                        if (t.attr('data-is-delete')) {
                                            $(parent).addClass('is-deleted');
                                            $(parent).one(whichTransitionEvent(), function () {
                                                $(parent).hide();
                                            });
                                        }
                                    }
                                    if (respon.reload) {
                                        window.location.reload();
                                    }
                                }
                            }, 'json');

                        } else {
                            alert('Have a error when parse the data');
                        }
                    }
                });
            });
        },

        initValidation: function (el, addEvent) {
            let base = this;
            $('.has-validation', el).each(function () {
                let _id = $(this).attr('id'),
                    validation = $(this).attr('data-validation'),
                    validation_id = $(this).closest('[data-validation-id]').attr('data-validation-id');
                bootstrapValidate('#' + _id, validation, function (isValid) {
                    if (isValid) {
                        if (typeof base.isValidated[validation_id] == 'object' && typeof base.isValidated[validation_id][_id] !== 'undefined') {
                            delete base.isValidated[validation_id][_id];
                        }
                    } else {
                        if (typeof base.isValidated[validation_id] != 'object') {
                            base.isValidated[validation_id] = {};
                        }
                        base.isValidated[validation_id][_id] = 1;
                    }
                });
                if (addEvent) {
                    if ($(this).val() === '') {
                        $(this).get(0).dispatchEvent(new Event("input"))
                    }
                }
            });
        },
        alert: function (respon) {
            if (typeof respon.message != "undefined") {
                if (respon.status === 0) {
                    $.toast({
                        heading: respon.title || '',
                        text: respon.message,
                        icon: 'error',
                        loaderBg: '#bf441d',
                        position: 'bottom-right',
                        allowToastClose: false,
                        hideAfter: 2000
                    });
                } else {
                    if (respon.status === 1) {
                        $.toast({
                            heading: respon.title || '',
                            text: respon.message,
                            icon: 'success',
                            loaderBg: '#5ba035',
                            position: 'bottom-right',
                            allowToastClose: false,
                            hideAfter: 2000
                        });
                    } else {
                        $.toast({
                            heading: respon.title || '',
                            text: respon.message,
                            icon: 'info',
                            loaderBg: '#26afa4',
                            position: 'bottom-right',
                            allowToastClose: false,
                            hideAfter: 2000
                        });
                    }
                }
            }
        },
        initGlobal: function (el) {
            let base = this;
            $('.date-time .date-render', 'form').click(function (ev) {
                let t = $(this);
                $('.dropdown-time', t).stop().toggle();
            });
            $('.date-time .date-render', '.filter-mobile-box').click(function (ev) {
                let t = $(this);
                $('.dropdown-time', t).stop().toggle();
            });

            $('.dropdown-time', 'form').on('click', '.item', function (ev) {
                timeClick($(this));
            });
            $('.dropdown-time', '.filter-mobile-box').on('click', '.item', function (ev) {
                timeClick($(this));
            });

            function timeClick($el) {
                let t = $el,
                    container = t.closest('.date-render'),
                    parent = t.closest('.dropdown-time'),
                    val = t.attr('data-value'),
                    title = t.text(),
                    sameTime = container.data('same-time') || 0;

                $('.item', parent).removeClass('active');
                t.addClass('active');

                $('input', container).attr('value', val).trigger('change');
                $('.render', container).text(title);

                if (t.closest('.date-render.check-in-render').length) {
                    let pos = t.index();

                    let next_time = t.closest('.date-time').find('.date-render.check-out-render').find('.dropdown-time');
                    if (next_time.length) {
                        $('.item', next_time).removeClass('disable');
                        let dateWrapper = $('.date-wrapper.date', t.closest('form')),
                            checkIn = $('.check-in-field', dateWrapper),
                            checkOut = $('.check-out-field', dateWrapper),
                            mobileFilterEl = $('#filter-mobile-box'),
                            carSingleForm = $('#form-book-car');

                        if (mobileFilterEl.is(':visible')) {
                            dateWrapper = mobileFilterEl;
                            checkIn = $('.check-in-field', dateWrapper);
                            checkOut = $('.check-out-field', dateWrapper);
                        }

                        if (carSingleForm.length) {
                            dateWrapper = carSingleForm;
                            checkIn = $('.check-in-field', dateWrapper);
                            checkOut = $('.check-out-field', dateWrapper);
                        }
                        if (sameTime) {
                            if (dateWrapper.length && checkIn.val() !== checkOut.val()) {
                                $('.item', next_time).eq(0).click();
                            } else {
                                $('.item', next_time).eq(pos).click();
                                if (pos !== 0) {
                                    $('.item', next_time).eq(pos - 1).addClass('disable').prevAll().addClass('disable');
                                }
                            }
                        } else {
                            if (dateWrapper.length && checkIn.val() !== checkOut.val()) {
                                $('.item', next_time).eq(0).click();
                            } else {
                                $('.item', next_time).eq(pos).next().click();
                                $('.item', next_time).eq(pos).addClass('disable').prevAll().addClass('disable');
                            }
                        }
                    }

                }
                t.trigger('hh_dropdown_time_item_click', [t]);
            }

            $('body').click(function (ev) {
                if ($(ev.target).closest('.date-time').length === 0) {
                    $('.dropdown-time').hide();
                }
            });

            $('input[name="bookingType"]', '.hh-search-form').on('change', function (ev) {
                let t = $(this),
                    val = $('input[name="bookingType"]:checked', '.hh-search-form').val(),
                    parent = t.closest('form');
                if (val === 'per_hour') {
                    $('.form-group-date-single', parent).removeClass('d-none');
                    $('.form-group-date-time', parent).removeClass('d-none');
                    $('.form-group-date', parent).addClass('d-none');
                } else {
                    $('.form-group-date-single', parent).addClass('d-none');
                    $('.form-group-date-time', parent).addClass('d-none');
                    $('.form-group-date', parent).removeClass('d-none');
                }
            }).change();

            $.fn.flatpickr && $('.date-time .flatpickr').each(function () {
                let t = $(this),
                    wrapper = t.closest('.button-time'),
                    timeFormat = wrapper.data('time-format'),
                    timeType24 = wrapper.data('time-type') == '24' ? true : false,
                    minuteIncrement = typeof wrapper.data('minute-increment') !== 'undefined' ? parseInt(wrapper.data('minute-increment')) : 30;
                let fl = t.flatpickr({
                    minuteIncrement: minuteIncrement,
                    static: true,
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: timeFormat,
                    time_24hr: timeType24,
                    clickOpens: true,
                    onChange: function (selectedDates, dateStr, instance) {
                        selectedDates = new Date(selectedDates);
                        var timeFormat = 'hh:mm A';
                        if(timeType24){
                            timeFormat = 'HH:mm';
                        }
                        if (t.closest('.check-in-render').length) {
                            $('.text.start', wrapper).text(moment(selectedDates).format(timeFormat));
                        }
                        if (t.closest('.check-out-render').length) {
                            $('.text.end', wrapper).text(moment(selectedDates).format(timeFormat));
                        }
                    }
                });
                fl.open();
            });

            $('.hh-search-result', body).on('hh_search_result_loaded', function () {
                base.initLazyLoad($('.hh-search-results-render .render'));
            });

            $('.dropdown-button-more-filter').on('show.bs.dropdown', function () {
                base.initLazyLoad($('.dropdown-menu', this));
            })
        }
    };
    HHActions.init(body);

    let HHFormHomeSingle = {
        init: function () {
            let base = this;
            let formHome = $('#form-book-home', body);

            if (formHome.length) {
                base.responsiveForm(formHome);
                base.addEvent(formHome);
            }
        },
        responsiveForm: function (form) {
            /* Sticky sidebar */
            if (typeof ScrollMagic === 'function') {
                setTimeout(function () {
                    let postDetails = document.querySelector(".single-home .col-content");
                    let postSidebar = document.querySelector("#form-book-home");

                    let duration = $(postDetails).outerHeight() - ($(postSidebar).outerHeight() + parseFloat($(postSidebar).css('margin-top')));
                    let controller = new ScrollMagic.Controller();
                    let scene = new ScrollMagic.Scene({
                        triggerElement: postSidebar,
                        triggerHook: 0,
                        duration: duration
                    }).addTo(controller);

                    if (window.matchMedia("(min-width: 992px)").matches) {
                        scene.setPin(postSidebar, {pushFollowers: false});
                    }
                    window.addEventListener('scroll', () => {
                        if (body.hasClass('header-sticky-run')) {
                            scene.offset($('#header-sticky-wrapper', body).height() * -1);
                        } else {
                            scene.offset(0);
                        }
                    });
                    window.addEventListener("resize", () => {
                        if (window.matchMedia("(min-width: 992px)").matches) {
                            scene.setPin(postSidebar, {pushFollowers: false});
                        } else {
                            scene.removePin(postSidebar, true);
                        }
                    });
                }, 1000);
            }
        },
        addEvent: function (form) {
            let base = this;

            $('.check-in-out-field', form).on('daterangepicker_change', function (e, start, end, label) {
                if (label === 'clicked_date') {
                    base.getRealPrice(form);
                }
            });

            $('.guest-group.has-extra-guest', form).on('hh_updown_decrease hh_updown_increase', function () {
                base.getRealPrice(form);
            });

            $('.dropdown-time', form).on('hh_dropdown_time_item_click', function (ev, el) {
                if (el.closest('.check-out-render').length) {
                    base.getRealPrice(form);
                }
            });
            $('.input-extra', form).on('change', function () {
                base.getRealPrice(form);
            });

            $('.check-in-out-single-field', form).on('daterangepicker_change', function (ev, start, end, label) {
                let t = $(this),
                    parent = t.closest('.date-single'),
                    form = t.closest('form'),
                    container = t.closest('.form-book'),
                    loading = $('.booking-loading', container);
                let data = {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'home_id': t.data('home-id'),
                    'start': start.format('YYYY-MM-DD')
                };
                loading.show();
                $('.form-message', form).empty();
                $('.form-group-date-time', form).addClass('d-none');
                $.post(parent.data('action-time'), data, function (respon) {
                    if (typeof respon == 'object') {
                        if (respon.status === 1) {
                            $('.date-time .dropdown-time', form).html(respon.html);
                            $('.form-group-date-time', form).removeClass('d-none');
                        } else {
                            $('.form-message', form).html(respon.message);
                        }
                    }
                    loading.hide();
                }, 'json');
            });
        },
        getRealPrice: function (form) {
            let data = $('form', form).serializeArray();
            data.push({
                name: '_token',
                value: $('meta[name="csrf-token"]').attr('content')
            });
            let loading = $('.hh-loading', form);
            loading.show();
            $('.form-render', form).empty();

            $.post(form.attr('data-real-price'), data, function (respon) {
                if (typeof respon === 'object') {
                    $('.form-render', form).html(respon.html);
                    if (respon.status === 0) {
                        alert(respon.message);
                    }

                }
                loading.hide();
            }, 'json');
        }
    };
    HHFormHomeSingle.init();

    let HHFormExperienceSingle = {
        init: function () {
            let base = this;
            let formExperience = $('#form-book-experience', body);
            if (formExperience.length) {
                base.responsiveForm(formExperience);
                base.addEvent(formExperience);
            }
        },
        responsiveForm: function (form) {
            /* Sticky sidebar */
            if (typeof ScrollMagic === 'function') {
                setTimeout(function () {
                    let postDetails = document.querySelector(".single-experience .col-content");
                    let postSidebar = document.querySelector("#form-book-experience");

                    let duration = $(postDetails).outerHeight() - ($(postSidebar).outerHeight() + parseFloat($(postSidebar).css('margin-top')));
                    let controller = new ScrollMagic.Controller();
                    let scene = new ScrollMagic.Scene({
                        triggerElement: postSidebar,
                        triggerHook: 0,
                        duration: duration
                    }).addTo(controller);

                    if (window.matchMedia("(min-width: 992px)").matches) {
                        scene.setPin(postSidebar, {pushFollowers: false});
                    }

                    window.addEventListener('scroll', () => {
                        if (body.hasClass('header-sticky-run')) {
                            scene.offset($('#header-sticky-wrapper', body).height() * -1);
                        } else {
                            scene.offset(0);
                        }
                    });

                    window.addEventListener("resize", () => {
                        if (window.matchMedia("(min-width: 992px)").matches) {
                            scene.setPin(postSidebar, {pushFollowers: false});
                        } else {
                            scene.removePin(postSidebar, true);
                        }
                    });
                }, 1000);
            }
        },
        addEvent: function (form) {
            let base = this;

            $('.check-in-out-field.date_time', form).on('daterangepicker_change', function (ev, start, end, label) {
                let t = $(this),
                    parent = t.closest('.date-wrapper'),
                    container = t.closest('.form-book'),
                    loading = $('.booking-loading', container);
                if (label === 'clicked_date') {
                    HHActions.resetUpdownNumber(form);
                    let label_render = $('.label-date-render', form);
                    label_render.html(start.format(label_render.data('date-format')));
                    $('#date-collapse', form).collapse('hide');
                    let data = {
                        '_token': $('meta[name="csrf-token"]').attr('content'),
                        'experience_id': t.data('experience-id'),
                        'experience_encrypt': t.data('experience-encrypt'),
                        'start': start.format('YYYY-MM-DD')
                    };
                    loading.show();
                    $('.form-message', form).empty();
                    $('.date-time-render', form).addClass('d-none');
                    $.post(parent.data('action-time'), data, function (respon) {
                        if (typeof respon == 'object') {
                            if (respon.status === 1) {
                                $('.date-time-render', form).html(respon.html).removeClass('d-none');
                                $('.date-time-render', form).find('select').niceSelect();
                                base.getRealPrice(form);
                            } else {
                                $('.date-time-render', form).addClass('d-none');
                                $('.form-message', form).html(respon.message);
                            }
                        }
                        loading.hide();
                    }, 'json');
                }

            });

            $('.check-in-out-field.just_date', form).on('daterangepicker_change', function (ev, start, end, label) {
                let t = $(this),
                    parent = t.closest('.date-wrapper'),
                    container = t.closest('.form-book'),
                    loading = $('.booking-loading', container);
                if (label === 'clicked_date') {
                    HHActions.resetUpdownNumber(form);
                    let label_render = $('.label-date-render', form);
                    label_render.html(start.format(label_render.data('date-format')));
                    $('#date-collapse', form).collapse('hide');
                    let data = {
                        '_token': $('meta[name="csrf-token"]').attr('content'),
                        'experience_id': t.data('experience-id'),
                        'experience_encrypt': t.data('experience-encrypt'),
                        'start': start.format('YYYY-MM-DD')
                    };
                    loading.show();
                    $('.form-message', form).empty();
                    $.post(parent.data('action-guest'), data, function (respon) {
                        if (typeof respon == 'object') {
                            if (respon.status === 1) {
                                if (!isNaN(respon.max_people)) {
                                    $('input[name="num_adults"]', form).attr('max', respon.max_people);
                                    $('input[name="num_children"]', form).attr('max', respon.max_people);
                                    $('input[name="num_infants"]', form).attr('max', respon.max_people);
                                    HHActions.resetUpdownNumber(form);
                                }
                                base.getRealPrice(form);
                            } else {
                                $('.form-message', form).html(respon.message);
                            }
                        }
                        loading.hide();
                    }, 'json');
                }

            });

            $('.check-in-out-field.package', form).on('daterangepicker_change', function (ev, start, end, label) {
                if (label === 'clicked_date') {
                    let label_render = $('.label-date-render', form);
                    label_render.html(start.format(label_render.data('date-format')));
                    $('#date-collapse', form).collapse('hide');
                    let tour_package = $('input[name="tour_package"]:checked', form).val();

                    if (tour_package !== undefined) {
                        base.getRealPrice(form);
                    }
                }
            });

            $('.input-extra', form).on('change', function () {
                base.getRealPrice(form);
            });
            form.on('change', 'select[name="startTime"]', function (e) {
                let t = $(this),
                    val = t.val(),
                    max_people = parseInt($('option:selected', t).attr('data-max'));

                if (!isNaN(max_people)) {
                    $('input[name="num_adults"]', form).attr('max', max_people);
                    $('input[name="num_children"]', form).attr('max', max_people);
                    $('input[name="num_infants"]', form).attr('max', max_people);
                    HHActions.resetUpdownNumber(form);
                    base.getRealPrice(form);
                }
            });

            $('input[name="num_adults"], input[name="num_children"],input[name="num_infants"]', form).on('change', function (ev) {
                base.getRealPrice(form);
            });

            $('.package-item input:checked', form).closest('.package-item').find('.package-description').show();
            $('.package-item input', form).change(function () {
                let t = $(this);
                $('.package-item .package-description', form).hide();
                if (t.is(':checked')) {
                    base.getRealPrice(form);
                    t.closest('.package-item').find('.package-description').show();
                }
            });
        },
        getRealPrice: function (form) {
            let data = $('form', form).serializeArray();
            data.push({
                name: '_token',
                value: $('meta[name="csrf-token"]').attr('content')
            });
            let loading = $('.booking-loading', form);
            loading.show();
            $('.form-render', form).empty();

            $.post($('form', form).attr('data-get-total'), data, function (respon) {
                if (typeof respon === 'object') {
                    $('.form-render', form).html(respon.html);
                    if (respon.status === 0) {
                        alert(respon.message);
                    }

                }
                loading.hide();
            }, 'json');
        }
    };
    HHFormExperienceSingle.init();

    let HHFormCarSingle = {
        reloadPage: true,
        bookingType: $('#form-book-car', body).data('booking-type'),
        init: function () {
            let base = this;
            let formCar = $('#form-book-car', body);
            if (formCar.length) {
                base.responsiveForm(formCar);
                base.addEvent(formCar);
            }
        },
        responsiveForm: function (form) {
            /* Sticky sidebar */
            if (typeof ScrollMagic === 'function') {
                setTimeout(function () {
                    let postDetails = document.querySelector(".single-car .col-content");
                    let postSidebar = document.querySelector("#form-book-car");

                    let duration = $(postDetails).outerHeight() - ($(postSidebar).outerHeight() + parseFloat($(postSidebar).css('margin-top')));
                    let controller = new ScrollMagic.Controller();
                    let scene = new ScrollMagic.Scene({
                        triggerElement: postSidebar,
                        triggerHook: 0,
                        duration: duration
                    }).addTo(controller);

                    if (window.matchMedia("(min-width: 992px)").matches) {
                        scene.setPin(postSidebar, {pushFollowers: false});
                    }

                    window.addEventListener('scroll', () => {
                        if (body.hasClass('header-sticky-run')) {
                            scene.offset($('#header-sticky-wrapper', body).height() * -1);
                        } else {
                            scene.offset(0);
                        }
                    });

                    window.addEventListener("resize", () => {
                        if (window.matchMedia("(min-width: 992px)").matches) {
                            scene.setPin(postSidebar, {pushFollowers: false});
                        } else {
                            scene.removePin(postSidebar, true);
                        }
                    });
                }, 1000);
            }
        },
        addEvent: function (form) {
            let base = this;

            if (base.bookingType === 'hour') {
                $('.check-in-out-field', form).on('daterangepicker_change', function (e, start, end, label) {
                    if (label === 'clicked_date' || base.reloadPage) {
                        let t = $(this),
                            parent = t.closest('.date-double'),
                            form = t.closest('form'),
                            container = t.closest('.form-book'),
                            loading = $('.booking-loading', container);
                        let data = {
                            '_token': $('meta[name="csrf-token"]').attr('content'),
                            'car_id': t.data('car-id'),
                            'start': start.format('YYYY-MM-DD'),
                            'end': end.format('YYYY-MM-DD')
                        };
                        loading.show();
                        $('.form-message', form).empty();
                        $('.form-group-date-time', form).addClass('d-none');
                        $.post(parent.data('action-time'), data, function (respon) {
                            if (typeof respon == 'object') {
                                if (respon.status === 1) {
                                    $('.date-time .dropdown-time', form).html(respon.html);
                                    let startTime = $('input[name="startTime"]', form).val();
                                    if (typeof startTime != "undefined" && startTime !== '') {
                                        $('.check-in-render .dropdown-time .item[data-value="' + startTime + '"]', form).click();
                                        $('.dropdown-time', form).hide();
                                    }
                                    $('.form-group-date-time', form).removeClass('d-none');
                                } else {
                                    $('.form-message', form).html(respon.message);
                                }
                            }
                            loading.hide();
                        }, 'json');
                        base.reloadPage = false;
                    }
                });
            }

            $('.dropdown-time', form).on('hh_dropdown_time_item_click', function (ev, el) {
                if (el.closest('.check-out-render').length) {
                    base.getRealPrice(form);
                }
            });

            $('.input-extra', form).on('change', function () {
                base.getRealPrice(form);
            });

            $('.car-number', form).on('change', function () {
                base.getRealPrice(form);
            });
            if ($('.check-in-field', form).val() !== '' &&
                $('.check-out-field', form).val() !== '' &&
                $('.date-time input[name="startTime"]', form).val() !== '' &&
                $('.date-time input[name="endTime"]', form).val() !== '') {
                base.getRealPrice(form);
            }
        },
        getRealPrice: function (form) {
            let data = $('form', form).serializeArray();
            data.push({
                name: '_token',
                value: $('meta[name="csrf-token"]').attr('content')
            });
            let loading = $('.hh-loading', form);
            loading.show();
            $('.form-render', form).empty();
            $('.form-message', form).empty();

            $.post(form.attr('data-real-price'), data, function (respon) {
                if (typeof respon === 'object') {
                    $('.form-render', form).html(respon.html);
                    if (respon.status === 0) {
                        alert(respon.message);
                    }

                }
                loading.hide();
            }, 'json');
        }
    };
    HHFormCarSingle.init();

    let HHCheckoutForm = {
        init: function () {
            let container = $('.hh-checkout-page', body);
            this.initEvents(container);
        },
        initEvents: function (container) {
            let base = this;
            $('.btn-next-payment', container).click(function (ev) {
                ev.preventDefault();
                let t = $(this),
                    form = t.closest('form');
                $('.has-validation', form).each(function () {
                    let _id = $(this).attr('id'),
                        validation = $(this).attr('data-validation');
                    bootstrapValidate('#' + _id, validation, function (isValid) {
                        if (isValid) {
                            if (typeof base.isValidated[_id] !== 'undefined') {
                                delete base.isValidated[_id];
                            }
                        } else {
                            base.isValidated[_id] = 1;
                        }
                    });
                    if ($(this).val() === '') {
                        $(this).trigger('focus').trigger('blur');
                    }
                });
                if (Object.size(base.isValidated)) {
                    let $el = $('.has-validation.is-invalid', form).first();
                    if ($el.length) {
                        $("html, body").animate({scrollTop: $el.offset().top}, 500);
                    }
                    return false;
                } else {
                    $('.nav-tabs .nav-item .nav-link[href="#co-payment-selection"]', container).tab('show');
                    $(document.body).trigger('awebooking_activcated_payment_tab');
                }
            });

            $('.checkout-form-payment', container).submit(function (ev) {
                ev.preventDefault();
                let form = $(this),
                    loading = $('.hh-loading', form),
                    use_captcha = form.data('google-captcha');

                let data = [];
                $('.checkout-form', container).each(function () {
                    data = data.concat($(this).serializeArray());
                });
                data.push({
                    name: '_token',
                    value: $('meta[name="csrf-token"]').attr('content')
                });
                loading.show();

                if (typeof use_captcha == 'string' && use_captcha == 'yes' && hh_params.use_google_captcha === 'on') {
                    grecaptcha.ready(function () {
                        grecaptcha.execute(hh_params.google_captcha_key, {action: 'form_action'}).then(function (token) {
                            data.push({
                                name: 'g-recaptcha-response',
                                value: token
                            });

                            $.post(form.attr('action'), data, function (respon) {
                                if (typeof respon === 'object') {

                                    HHActions.alert(respon);

                                    if (respon.need_login) {
                                        $('a[data-target="#hh-login-modal"]', 'body').trigger('click');
                                    }

                                    if (respon.redirect) {
                                        window.location.href = respon.redirect;
                                    }

                                    if (respon.redirect_form) {
                                        $('body').html(respon.redirect_form);
                                    }
                                    if (respon.form_id) {
                                        $(respon.form_id).find("script").each(function () {
                                            eval($(this).text());
                                        });
                                    }
                                }
                                loading.hide();
                            }, 'json');
                        });
                    });
                } else {
                    $.post(form.attr('action'), data, function (respon) {
                        if (typeof respon === 'object') {

                            HHActions.alert(respon);

                            if (respon.need_login) {
                                $('a[data-target="#hh-login-modal"]', 'body').trigger('click');
                            }

                            if (respon.redirect) {
                                window.location.href = respon.redirect;
                            }
                            if (respon.redirect_form) {
                                $('body').append(respon.redirect_form);
                            }
                            if (respon.form_id) {
                                $(respon.form_id).find("script").each(function () {
                                    eval($(this).text());
                                });
                            }
                        }
                        loading.hide();
                    }, 'json');
                }

            });

            $('.btn-prev-customer', container).click(function (ev) {
                ev.preventDefault();
                $('.nav-tabs .nav-item .nav-link[href="#co-customer-information"]', container).tab('show');
            });

            $('.payment-method', container).each(function () {
                let t = $(this);
                if (t.is(':checked')) {
                    t.closest('.payment-item').addClass('active');
                    $(document.body).trigger('awebooking_payment_changed', [t.closest('.payment-item')]);
                }
                t.change(function () {
                    let parent = t.closest('.payment-item');
                    $('.payment-method', container).closest('.payment-item').removeClass('active');
                    parent.removeClass('active');
                    if (t.is(':checked')) {
                        parent.addClass('active');
                        $(document.body).trigger('awebooking_payment_changed', [parent]);
                    }
                });
            });

            $('#last-user-checkout', body).change(function (ev) {
                let t = $(this),
                    val = t.is(':checked') ? t.val() : '',
                    parent = t.closest('.use-last-user-checkout');

                if (val === 'on') {
                    let data = $('input[name="last_user_checkout"]').val();
                    data = JSON.parse(Base64.decode(data));
                    if (typeof data == 'object') {
                        $.each(data, function (index, value) {
                            if (index === 'country') {
                                $('select[name="' + index + '"] option[value="' + value + '"]').prop('selected', true).trigger('change');
                            } else {
                                $('input[name="' + index + '"]').val(value).trigger('change');
                            }

                        });
                        parent.next().hide();
                    }
                } else {
                    $('.checkout-form').each(function () {
                        $(this).get(0).reset();
                    });
                    parent.next().show();
                }
            });
        }
    };

    HHCheckoutForm.init();

    $(".view-gallery", body).click(function () {
        let t = $(this);
        let parent = t.closest('.hh-gallery');
        if ($(".data-gallery", parent).length) {
            let data = $(".data-gallery", parent).attr("data-gallery");
            if (typeof data === "string" && data !== "") {
                data = JSON.parse(Base64.decode(data));
                $(this).lightGallery({dynamic: true, dynamicEl: data})
            }
        }
    });

    $('.hh-search-bar-buttons', body).on('hh_mapbox_input_focus', '.mapboxgl-ctrl-geocoder--input', function () {
        $(this).css({'max-width': '100%'});
        this.selectionStart = this.selectionEnd = 10000;
        let w = $(this).closest('.ots-button-item').innerWidth();
        $(this).parent().find('.suggestions').width(w);
    });

    $('.hh-search-bar-buttons', body).each(function () {
        let t = $(this);

        t.on('hh_mapbox_input_load', '.mapboxgl-ctrl-geocoder--input', function () {
            $(this).attr('data-max-width', $(this).css('max-width'));
        });
        t.on('hh_mapbox_input_blur', '.mapboxgl-ctrl-geocoder--input', function (ev) {
            $(this).css({'max-width': $(this).attr('data-max-width')});
            this.selectionStart = this.selectionEnd = 0;
        });
    });

    $('.reply-box-wrapper .btn-reply').on('click', function (e) {
        e.preventDefault();
        var t = $(this),
            wrapper = t.closest('li'),
            parent = t.closest('.reply-box-wrapper'),
            appendEl = parent.find('.reply-form'),
            commentForm = $('.post-comment.parent-form');

        $('.post-comment.append-form').remove();
        $('.reply-box-wrapper').find('.reply-form').html('');
        $('.reply-box-wrapper').removeClass('active');

        parent.addClass('active');
        commentForm.find('input[name="comment_id"]').val(parent.data('comment_id'));
        appendEl.html(commentForm.clone().removeClass('parent-form').addClass('append-form').show());
        commentForm.hide();
    });

    $('.reply-box-wrapper .btn-cancel-reply').on('click', function (e) {
        e.preventDefault();
        var t = $(this),
            wrapper = t.closest('li'),
            parent = t.closest('.reply-box-wrapper'),
            appendEl = parent.find('.reply-form'),
            commentForm = $('.post-comment.parent-form');

        parent.removeClass('active');
        commentForm.find('input[name="comment_id"]').val('');
        appendEl.html('');
        commentForm.show();
    });

    $('.review-select-rate .fas-star .fa').each(function () {
        var list = $(this).parent(),
            listItems = list.children(),
            itemIndex = $(this).index(),
            parentItem = list.parent();
        $(this).on({
            mouseenter: function () {
                for (var i = 0; i < listItems.length; i++) {
                    if (i <= itemIndex) {
                        $(listItems[i]).addClass('hovered');
                    } else {
                        break;
                    }
                }
                $(this).on('click', function () {
                    for (var i = 0; i < listItems.length; i++) {
                        if (i <= itemIndex) {
                            $(listItems[i]).addClass('selected');
                        } else {
                            $(listItems[i]).removeClass('selected');
                        }
                    }
                    parentItem.children('.review_star').val(itemIndex + 1);
                });
            },
            mouseleave: function () {
                listItems.removeClass('hovered');
            }
        });

    });

    $('.hh-navigation li.current-menu-item').each(function () {
        var t = $(this);
        t.closest('.menu-item-has-children').addClass('current-menu-item');
        t.closest('.menu-item-has-children').attr('is-active', '1');
    });

    function recursiveCheckMenuCurrent(element) {
        if (!element.length)
            return;

        var the_ul = element.parent();
        if (the_ul.hasClass('hh-parent')) {
            element.addClass('current-menu-item');
        } else {
            recursiveCheckMenuCurrent(the_ul.parent());
        }
    }

    $('.current-menu-item').each(function () {
        recursiveCheckMenuCurrent($(this));
    });

    $('#mobile-check-availability').on('click', function () {
        $('.form-book').fadeIn();
    });

    $('.form-book .popup-booking-form-close').on('click', function () {
        $('.form-book').fadeOut();
    });

    $('.mobile-book-action').each(function () {
        var t = $(this);
        $(window).scroll(function () {
            if ($(window).scrollTop() >= 50 && window.matchMedia('(max-width: 991px)').matches) {
                t.css('display', 'flex');
            } else {
                t.css('display', 'none');
            }
        });
    });

    $('#hh-fogot-password-modal', body).on('show.bs.modal', function (ev) {
        $('#hh-register-modal', body).modal('hide');
        $('#hh-login-modal', body).modal('hide');
    });

    $('#hh-register-modal', body).on('show.bs.modal', function (ev) {
        $('#hh-login-modal', body).modal('hide');
    });

    $('#hh-login-modal', body).on('show.bs.modal', function (ev) {
        $('#hh-register-modal', body).modal('hide');
    });

})(jQuery);
