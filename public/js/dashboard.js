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

Object.size = function (obj) {
    let size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
};


function esc_html(unsafe) {
    if (typeof unsafe == 'string') {
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    } else {
        return unsafe;
    }
}

(function ($) {

    moment.tz.setDefault(hh_params.timezone);
    /*==== Tabs calculation ====*/
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
                        let html = '<li class="nav-item dropdown-item" data-tabs-item><a href="#' + $(this).attr('id') + '" class="nav-link">' + $(this).html() + '</a></li>';
                        $('.hh-tabs-toggle .dropdown-menu', $container).append(html);
                        $(this).hide();
                    }
                });
                $container.find('.hh-tabs-toggle .nav-link').click(function (event) {
                    event.preventDefault();
                    let href = $(this).attr('href');

                    if ($(href).length) {
                        $(href).trigger('click');
                    }
                });
            }
        });
    };

    $(document).ready(function () {
        $('[data-tabs-calculation]').hhTabsCalculation();
    });

    var timeoutResizeTab = false;
    $(window).resize(function () {
        clearTimeout(timeoutResizeTab);
        timeoutResizeTab = setTimeout(function () {
            $('[data-tabs-calculation]').hhTabsCalculation();
        }, 100);
    });
})(jQuery);

jQuery(document).ready(function ($) {
    setTimeout(function () {
        $('.page-loading').hide();
    }, 500);

    $('#hh-choose-langs').on('change', function () {
        var url = $(this).data('url');
        url += '?lang=' + $(this).val();
        window.location.href = url;
    });

    function hhRenderMenuItem(data) {
        var menuBox = $('.hh-list-menu-box .sortable'),
            numberMenuItems = menuBox.find('li').length,
            newID = 'hh-mn-' + (numberMenuItems + 1);

        var typeName = data['type'].replace('_', ' ');
        var timeStp = $.now();

        var te = '<li id="' + newID + '" data-type="' + esc_html(data['type']) + '" data-post_id="' + esc_html(data['id']) + '" data-post_title="' + esc_html(data['name']) + '">';
        te += '<div class="item type-' + esc_html(data['type']) + '">';
        te += '<div class="item-header d-flex align-items-center justify-content-between">';
        te += '<span class="name">' + esc_html(data['name']) + '</span>';
        te += '<span class="hh-delete-menu-item ml-3"><i class="fe-trash-2"></i></span>';
        te += '</div>';
        te += '<div class="item-content-wrapper">';
        te += '<div class="item-content">';
        te += '<div class="form-group name">';
        te += '<label>' + menuBox.parent().data('menu-name') + '</label>';
        te += '<input type="text" class="form-control form-control-sm menu_name" value="' + esc_html(data['name']) + '">';
        te += '</div>';
        te += '<div class="form-group url">';
        te += '<label>' + menuBox.parent().data('menu-url') + '</label>';
        te += '<input type="text" class="form-control form-control-sm menu_url" value="' + esc_html(data['url']) + '">';
        te += '</div>';
        te += '<div class="form-group target">\n' +
            '<div class="checkbox checkbox-success">\n' +
            '<input type="checkbox" class="menu_target" value="1" id="target-checkbox-' + timeStp + '">\n' +
            '<label for="target-checkbox-' + timeStp + '">' + menuBox.parent().data('menu-target') + '</label>\n' +
            '</div>\n' +
            '</div>';
        te += '<div class="menu-info">';
        te += '<p class="menu-type">' + menuBox.parent().data('menu-type') + ' ' + typeName + '</p>';
        te += '<p class="menu-origin-link">' + menuBox.parent().data('menu-origin') + ' <a href="' + esc_html(data['url']) + '">' + esc_html(data['name']) + '</a></p>';
        te += '</div>';
        te += '</div>';
        te += '</div>';
        te += '</div>';
        te += '</li>';

        menuBox.append(te);
    }

    if ($('.hh-list-menu-box .sortable').length) {

        var menuNested = $('.hh-list-menu-box .sortable').nestedSortable({
            handle: 'div',
            items: 'li',
            toleranceElement: '> div'
        });

        $('.hh-menu-form').on('hh_form_action_before', function (el) {
            var currentEl = $(el.currentTarget);

            var i = 1;
            $('.hh-list-menu-box .sortable li').each(function () {
                $(this).attr('id', 'hh-mn-' + i);
                i++;
            });

            var menuStruture = $('.hh-list-menu-box .sortable').nestedSortable("toArray");
            currentEl.find('input[name="menu_structure"]').attr('value', JSON.stringify(menuStruture));
        });

        $(document).on('click', '.hh-list-menu-box .sortable .item .item-header', function (e) {
            if (e.target.className !== 'fe-trash-2') {
                var t = $(this),
                    parent = t.parent(),
                    contentWrapper = parent.find('.item-content-wrapper');

                t.closest('.hh-list-menu-box').find('.item .item-content-wrapper').not(contentWrapper).removeClass('active');
                contentWrapper.toggleClass('active');
            }
        });

        $(document).on('click', '.hh-add-menu-box h5.title', function () {
            var t = $(this),
                parent = t.parent();

            t.closest('.hh-add-menu-box-wrapper').find('.hh-add-menu-box').not(parent).removeClass('active');
            parent.toggleClass('active');
        });

        $('.hh-btn-add-menu-item').on('click', function (e) {
            e.preventDefault();
            var t = $(this),
                parent = t.closest('.hh-add-menu-box');

            if (t.hasClass('custom-link')) {
                var menuName = parent.find('.menu-name');
                var menuURL = parent.find('.menu-url');
                var menuData = {
                    name: menuName.val() != '' ? esc_html(menuName.val()) : 'Menu title',
                    type: 'custom',
                    id: '0',
                    url: menuURL.val() != '' ? esc_html(menuURL.val()) : '#'
                };
                hhRenderMenuItem(menuData);
                menuName.val('');
                menuURL.val('');
            } else {
                var menuItem = $('.hh-add-menu-item', parent);
                menuItem.each(function () {
                    if ($(this).is(':checked')) {
                        var dataMenuItem = $(this).data();
                        hhRenderMenuItem(dataMenuItem);
                    }
                });
                menuItem.prop('checked', false);
            }

        });

        $(document).on('keyup', '.hh-list-menu-box .sortable .item .item-content input.menu_name', function () {
            $(this).closest('.item').find('.item-header .name').text($(this).val());
        });
    }
    $(document).on('click', '.hh-delete-menu-item', function (e) {
        e.preventDefault();
        var conf = confirm('Are you sure want to delete it?');
        if (conf) {
            var t = $(this),
                liEl = t.closest('li'),
                liElPrev = liEl.prev(),
                olEl = t.closest('ol'),
                liChild = liEl.find('ol').first().clone();

            if (liElPrev.length > 0) {
                if (liChild.length > 0) {
                    $(liChild[0].innerHTML).insertAfter(liElPrev);
                }

            } else {
                if (liChild.length > 0) {
                    olEl.prepend(liChild[0].innerHTML);
                }
            }
            liEl.remove();
        }
    });

    var translationEl = $('#input-search-translation');

    function filterTableTranslation(val) {
        var value = val.toLowerCase();
        $('.table-translations tbody tr').filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    }

    if (translationEl.length) {

        translationEl.keypress(function (event) {
            if (event.which == '13') {
                event.preventDefault();
            }
        });

        translationEl.on('keyup', function () {
            var t = $(this),
                val = t.val();
            filterTableTranslation(val);
        });

        translationEl.parent().find('button').on('click', function () {
            filterTableTranslation(translationEl.val());
        });
    }

    var importFontElWrapper = $('.awe-import-fonts-progress');
    $('input[type="file"]', importFontElWrapper).change(function () {
        var t = $(this),
            h3El = t.parent().find('h3'),
            h3Origin = h3El.data('text-origin'),
            h3Uploaded = h3El.data('text-uploaded');

        $('.form-message', importFontElWrapper).empty();
        if (t.val() !== '') {
            var filePath = t.val();
            filePath = filePath.split('\\');
            var fileName = filePath[filePath.length - 1];
            importFontElWrapper.addClass('uploaded');
            h3El.text(h3Uploaded.replace('#_#', fileName));
        } else {
            importFontElWrapper.removeClass('uploaded');
            h3El.text(h3Origin);
        }
    });
});

(function ($) {
    'use strict';

    let body = $('body');

    let HHActions = {
        tmp_data: {},
        is_running: {
            run: true
        },
        interval: {
            interval: true
        },
        isValidated: {},
        init: function (el) {
            this.initCheckboxAction(el);
            this.initLinkAction(el);
            this.initFormAction(el);
            this.initSelectAction(el);
            this.initUploadMedia(el);
            this.listMedia(el);
            this.mediaEvents(el);
            this.initTable(el);
            this.initValidation(el);
            this.initBindData(el);
            this.initMatchHeigth(el);
            this.initContextMenu(el);
            this.initScroll(el);
            this.initLoadDelay(el);
            this.initNeedMove(el);
            this.initMultiLanguages(el);
            this.initTableSort(el);
            this.initFormFile(el);
            this.initBulkAction(el);
            this.initCheckAll(el);
            this.initGlobal(el);
        },
        initCheckAll: function (el) {
            $('.hh-check-all', el).each(function () {
                let t = $(this),
                    checkAll = $('input', t),
                    parent = t.closest('table'),
                    checkboxItem = $('.hh-check-all-item', parent),
                    checkboxCheckedItem = $('.hh-check-all-item:checked', parent);

                if (checkboxItem.length > 0 && checkboxItem.length === checkboxCheckedItem.length) {
                    checkAll.prop('checked', true);
                }
                checkAll.on('change', function () {
                    if ($(this).is(':checked')) {
                        checkboxItem.prop('checked', true);
                    } else {
                        checkboxItem.prop('checked', false);
                    }
                });

                checkboxItem.on('change', function () {
                    let checked = $('.hh-check-all-item:checked', parent).length,
                        total = checkboxItem.length;

                    if (checked === total) {
                        checkAll.prop('checked', true);
                    } else {
                        checkAll.prop('checked', false);
                    }
                });
            });
        },
        initBulkAction: function (el) {
            let base = this;

            $('.action-toolbar form', el).submit(function (e) {
                e.preventDefault();
                let form = $(this),
                    action = form.attr('action'),
                    target = form.data('target'),
                    checkboxItem = $('.hh-check-all-item:checked', target),
                    loader = $('.page-loading', el);

                let selectAction = $('select', form).val();
                if (checkboxItem.length && selectAction !== 'none') {
                    let ids = [];
                    checkboxItem.each(function () {
                        ids.push($(this).val());
                    });
                    ids = ids.join(',');
                    let data = {
                        '_token': $('meta[name="csrf-token"]').attr('content'),
                        'action': selectAction,
                        'post_id': ids
                    };
                    loader.show();
                    $.post(action, data, function (respon) {
                        if (typeof respon == 'object') {
                            base.alert(respon);
                            setTimeout(function () {
                                window.location.reload();
                            }, 1000);
                        }
                        loader.hide();
                    }, 'json');
                }
            });
        },

        initFormFile: function (el) {
            $(".form-file-action").on('submit', (function (e) {
                e.preventDefault();

                var form = $(this),
                    formData = new FormData(this),
                    url = form.attr('action'),
                    loading = $('.hh-loading', form);

                formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

                $.ajax({
                    url: url,
                    type: "POST",
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    beforeSend: function () {
                        loading.show();
                    },
                    success: function (respon) {
                        if ($('.form-message', form).length) {
                            $('.form-message', form).html(respon.message);
                        }
                        loading.hide();
                    },
                    error: function (e) {
                        loading.hide();
                    }
                });
            }));
        },

        initTableSort: function (el) {
            let base = this;
            $.fn.sortable && $('[data-sort="true"]', el).each(function () {
                var t = $(this);
                t.find('tbody').sortable({
                    update: function (event, ui) {
                        var data = {},
                            order = {};
                        t.find('tbody tr').each(function () {
                            order[$(this).data(t.data('sort-field'))] = $(this).index() + 1;
                        });
                        data['_token'] = $('meta[name="csrf-token"]').attr('content');
                        data['data'] = order;
                        $.post(t.attr('data-sort-action'), data, function (respon) {
                            if (typeof respon == 'object') {
                                base.alert(respon);
                            }
                        }, 'json');
                    }
                });
            });
        },

        initMultiLanguages: function (el) {
            let $language_switch = $('#hh-language-action');

            if ($language_switch.length === 0) {
                return false;
            }

            let current_code = $('.item.active', $language_switch).attr('data-code');
            $('input[name="current_language_switcher"]').val(current_code);

            let $topbar = $('.navbar-custom'),
                topbar_height = $topbar.height();

            $(window).on('scroll', function () {
                if ($(window).scrollTop() > topbar_height) {
                    $language_switch.addClass('has-fixed');
                    $language_switch.css('top', topbar_height);
                } else {
                    $language_switch.removeClass('has-fixed');
                    $language_switch.css('top', '');
                }
            });

            $(document.body).off('click', '#hh-language-action .item').on('click', '#hh-language-action .item', function (ev) {
                ev.preventDefault();

                let $item = $(this),
                    $language_switch_el = $item.closest('#hh-language-action');
                let code = $item.attr('data-code');

                $('.item', $language_switch_el).removeClass('active');
                $item.addClass('active');

                $('.has-translation[data-lang]').addClass('hidden');
                $('.has-translation[data-lang="' + code + '"]').removeClass('hidden');
                $('input[name="current_language_switcher"]').val(code);

                let $service_preview = $('#hh-dashboard-service-preview');
                if ($service_preview.length) {
                    $('.has-translation[data-lang]', $service_preview).addClass('hidden');
                    $('.has-translation[data-lang="' + code + '"]', $service_preview).removeClass('hidden');
                }

                $(window).trigger('awebooking_changed_language_js', [$item]);
            });
        },
        initNeedMove: function (el) {
            let base = this;
            $('.hh-need-move', el).each(function () {
                let t = $(this),
                    to = t.data('to'),
                    reinit = t.data('reinit');

                if ($(to).length) {
                    let html = t.html();
                    t.remove();
                    $(to).append(html);
                    if (typeof reinit == 'string') {
                        base[reinit](el);
                    }
                }
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
        initLoadDelay: function (el) {
            let base = this;

            $('.hh-get-home-near-by').on('hh_load_delay', function (ev, data, el, base) {
                let t = $(this),
                    unique = t.data('unique');
                if (t.is(el)) {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(function (position) {
                            if (typeof position === 'object') {
                                let lat = position.coords.latitude;
                                let lng = position.coords.longitude;

                                if (lat && lng) {
                                    let data = {
                                        _token: $('meta[name="csrf-token"]').attr('content'),
                                        lat: lat,
                                        lng: lng,
                                        radius: t.data('radius')
                                    };
                                    base.tmp_data[unique] = data;
                                    delete base.is_running[unique];
                                }
                            }
                        });
                    }
                }
            });

            $('[data-toggle="hh-reload"]', el).click(function () {
                let t = $(this),
                    target = $(t.data('target'));
                if (target.length) {
                    load_delay(target);
                }
            });

            $('.hh-load-delay', el).each(function () {
                load_delay($(this));
            });

            function load_delay(el) {
                let render = $('.render', el),
                    loading = $('.hh-loading', el),
                    from = el.data('from') || '',
                    unique = el.data('unique'),
                    url = el.data('action');

                base.tmp_data[unique] = JSON.parse(Base64.decode(el.attr('data-data')));
                base.tmp_data[unique].push({
                    name: '_token',
                    value: $('meta[name="csrf-token"]').attr('content')
                });
                el.trigger('hh_load_delay', [base.tmp_data, el, base]);
                if (from == 'js') {
                    base.is_running[unique] = true;
                }
                loading.show();
                if (base.interval[unique]) {
                    clearInterval(base.interval[unique]);
                }
                base.interval[unique] = setInterval(function () {
                    if (typeof base.is_running[unique] == 'undefined') {
                        $.post(url, base.tmp_data[unique], function (respon) {
                            if (typeof respon == 'object') {
                                render.html(respon.html);
                            }
                            loading.hide();
                        }, 'json');
                        clearInterval(base.interval[unique]);
                        delete base.interval[unique];
                    }
                }, 500);
            }
        },
        initContextMenu: function (el) {
            let base = this;
            $.fn.contextMenu && $.contextMenu({
                selector: '.hh-all-media .hh-media-item',
                callback: function (key, options) {
                    let t = options.$trigger;
                    if (key === 'delete') {
                        $.confirm({
                            animation: 'none',
                            title: 'System Alert',
                            content: 'Are you sure to delete this attachment?',
                            buttons: {
                                ok: {
                                    text: 'Delete',
                                    btnClass: 'btn-primary',
                                    action: function () {
                                        let data = JSON.parse(Base64.decode(t.attr('data-params')));
                                        if (typeof data == 'object') {
                                            data['_token'] = $('meta[name="csrf-token"]').attr('content');
                                            $.post(t.attr('data-delete-url'), data, function (respon) {
                                                if (typeof respon == 'object') {
                                                    base.alert(respon);
                                                    if (respon.status == 1) {
                                                        t.parent('li').remove();
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
                    }
                },
                items: {
                    "delete": {name: "Delete", icon: "delete"},
                    "quit": {
                        name: "Quit", icon: function () {
                            return 'context-menu-icon context-menu-icon-quit';
                        }
                    }
                }
            });
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
        initMatchHeigth: function (el) {
            $('.has-matchHeight', body).matchHeight();

            setTimeout(function () {
                $('.has-maxHeight').each(function () {
                    $(this).css('max-height', t.css('height'));
                });

                $('[data-max-height]', el).each(function () {
                    $(this).css({
                        'max-height': $(this).attr('data-max-height')
                    });
                });

                $('[data-min-height]', el).each(function () {
                    $(this).css({
                        'min-height': $(this).attr('data-min-height')
                    });
                });

                $('[data-min-width]', el).each(function () {
                    $(this).css({
                        'min-width': $(this).attr('data-min-width')
                    });
                });
            }, 500);
        },
        initAccounting: function (number) {
            number = accounting.formatMoney(number, {
                symbol: hh_params.currency.symbol,
                decimal: hh_params.currency.decimal_separator,
                thousand: hh_params.currency.thousand_separator,
                precision: hh_params.currency.currency_decimal,
                format: (hh_params.currency.possition == 'left')? "%s%v": "%v%s"
            });
            return number;
        },
        initBindData: function (el) {
            let base = this;
            $('[data-hh-bind-from]', el).each(function () {
                let t = $(this);
                let target = t.data('hh-bind-from');
                let accounting = t.is('[data-hh-accounting]');
                if ($(target).length) {
                    let val = $(target).val() || t.data('hh-bind-default');
                    if (accounting) {
                        val = base.initAccounting(val);
                    }
                    if (t.is('[data-hh-bind-default-input]')) {
                        let input = $(t.data('hh-bind-default-input'));
                        if ($(input).length) {
                            val = $(input).val();
                            if (accounting) {
                                val = base.initAccounting(val);
                            }
                        }
                    }
                    t.text(val);
                    el.on('change keyup', target, function () {
                        let val = $(this).val() || t.data('hh-bind-default');
                        if (accounting) {
                            val = base.initAccounting(val);
                        }
                        t.text(val);
                    });
                }
            });

            $('[data-hh-bind-value-from]', el).each(function () {
                let t = $(this);
                let target = t.data('hh-bind-value-from');
                if ($(target).length) {
                    let val = $(target).val() || t.data('hh-bind-default');
                    t.val(val);
                    el.on('change keyup', target, function () {
                        let val = $(this).val() || t.data('hh-bind-default');
                        t.val(val);
                    });
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
            let base = this;
            setTimeout(function () {
                $.fn.dataTable && $('table[data-plugin="datatable"]', el).each(function () {
                    var t = $(this),
                        columns = t.data('cols') ? JSON.parse(Base64.decode(t.data('cols'))) : [],
                        exp = typeof t.data('export') == 'undefined' ? 'off' : t.data('export');
                    if (!t.hasClass('no-data')) {
                        let dtable = t.dataTable({
                            dom: (exp === 'on') ? 'frtipB' : 'frtip',
                            buttons: [
                                {
                                    extend: (exp === 'on') ? 'csvHtml5' : '',
                                    text: (exp === 'on') ? t.data('csv-name') : '',
                                    exportOptions: {
                                        orthogonal: "exportcsv",
                                        format: {
                                            header: function (data, columnIdx) {
                                                let el = $($.parseHTML(data));
                                                let val = data;
                                                val = (el.hasClass('exp')) ? el.text() : (el.find('.exp').length ? el.find('.exp').text() : val);
                                                return val.trim();
                                            },
                                            body: function (data) {
                                                let el = $($.parseHTML(data));
                                                let val = data;
                                                val = (el.hasClass('exp')) ? el.text() : (el.find('.exp').length ? el.find('.exp').text() : val);
                                                return val.trim();
                                            }
                                        },
                                        columns: (typeof columns === 'object') ? columns : ''
                                    }
                                },
                                {
                                    extend: (exp === 'on') ? 'pdfHtml5' : '',
                                    text: (exp === 'on') ? t.data('pdf-name') : '',
                                    exportOptions: {
                                        format: {
                                            header: function (data, columnIdx) {
                                                let el = $($.parseHTML(data));
                                                let val = data;
                                                val = (el.hasClass('exp')) ? el.text() : el.find('.exp').length ? el.find('.exp').text() : val;
                                                return val.trim();
                                            },
                                            body: function (data) {
                                                let el = $($.parseHTML(data));
                                                let val = data;
                                                val = (el.hasClass('exp')) ? el.text() : el.find('.exp').length ? el.find('.exp').text() : val;
                                                return val.trim();
                                            }
                                        },
                                        columns: (typeof columns === 'object') ? columns : ''
                                    }
                                }
                            ],
                        });
                    }
                });
            }, 500);
        },

        initFormAction: function (el) {
            let base = this;
            $('form.form-action', el).each(function () {
                let form = $(this),
                    url = form.attr('action'),
                    loading = $('.hh-loading', form),
                    reloadTime = form.data('reload-time'),
                    needEncode = form.data('encode');

                if (typeof reloadTime == 'undefined') {
                    reloadTime = 0;
                }

                form.submit(function (ev) {
                    ev.stopPropagation();
                    ev.preventDefault();

                    if (typeof CKEDITOR == 'object' && typeof CKEDITOR.instances == 'object') {
                        $.each(CKEDITOR.instances, function (id, $editor) {
                            document.getElementById(id).value = $editor.getData();
                        });
                    }

                    base.initValidation(form, true);
                    if (Object.size(base.isValidated)) {
                        let $el = $('.has-validation.is-invalid', form).first();
                        if ($el.length) {
                            $("html, body").animate({scrollTop: $el.offset().top}, 500);
                            $el.focus();
                        }
                    } else {
                        form.trigger('hh_form_action_before', [form]);

                        if (typeof ClassicEditor === 'function') {
                            if (typeof window.editor === 'object' && Object.size(window.editor)) {
                                $.each(window.editor, function (index, editor) {
                                    editor.updateSourceElement();
                                });
                            }
                        }
                        let data = [];
                        if (needEncode) {
                            let postFields = form.serializeArray();
                            data.push({
                                name: 'fields',
                                value: JSON.stringify(postFields)
                            });
                        } else {
                            data = form.serializeArray();
                        }

                        data.push({
                            name: '_token',
                            value: $('meta[name="csrf-token"]').attr('content'),
                        });

                        loading.show();
                        if ($('.form-message', form).length) {
                            $('.form-message', form).empty();
                        }
                        $.post(url, data, function (respon) {
                            if (typeof respon === 'object') {
                                if ($('.form-message', form).length) {
                                    $('.form-message', form).html(respon.message);
                                } else {
                                    base.alert(respon);
                                }

                                form.trigger('hh_form_action_complete', [respon]);

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
                            loading.hide();
                        }, 'json');
                    }
                });
            });
        },
        initLinkAction: function (el) {
            let base = this;
            $('.hh-link-action', el).each(function () {
                let t = $(this),
                    parent = t.closest(t.data('parent')),
                    hasLoading = t.data('page-loading');

                t.click(function (ev) {
                    ev.preventDefault();

                    let dataConfirm = t.data('confirm');
                    if (dataConfirm == 'yes') {
                        if (hasLoading && typeof hasLoading !== 'undefined') {
                            $('.hh-loading.page-loading').show();
                        }
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
                                                        if (t.attr('data-is-delete') == 'true') {
                                                            $(parent).addClass('is-deleted');
                                                            $(parent).one(whichTransitionEvent(), function () {
                                                                $(parent).hide();
                                                            });
                                                        }
                                                    }
                                                    if (hasLoading && typeof hasLoading !== 'undefined') {
                                                        $('.hh-loading.page-loading').hide();
                                                    }
                                                    if (respon.reload) {
                                                        window.location.reload();
                                                    }
                                                }
                                            }, 'json');

                                        } else {
                                            if (hasLoading && typeof hasLoading !== 'undefined') {
                                                $('.hh-loading.page-loading').hide();
                                            }
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
                            if (hasLoading && typeof hasLoading !== 'undefined') {
                                $('.hh-loading.page-loading').show();
                            }

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
                                        if (t.attr('data-is-delete') == 'true') {
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
                                if (hasLoading && typeof hasLoading !== 'undefined') {
                                    $('.hh-loading.page-loading').hide();
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
                if (addEvent) {
                    if ($(this).val() === '') {
                        $(this).trigger('focus').trigger('blur');
                    }
                }
            });
        },
        initUploadMedia: function (el) {
            let base = this;
            $('.hh-dropzone', el).each(function () {
                let form = $(this),
                    container = form.parent();

                let previewNode = $('#hh-dropzone-template', container);

                previewNode.remove();
                var hhDropzone = new Dropzone(form.get(0), {
                    url: form.attr('action'), // Check that our form has an action attr and if not, set one here
                    maxFilesize: hh_params.media.media_upload_size,
                    acceptedFiles: hh_params.media.media_upload_permission.join(','),
                    previewTemplate: '<div></div>',
                    previewsContainer: $('#previews', container).get(0),
                    clickable: true,
                    createImageThumbnails: false,
                    dictDefaultMessage: "Drop files here to upload.", // Default: Drop files here to upload
                    dictFallbackMessage: "Your browser does not support drag'n'drop file uploads.", // Default: Your browser does not support drag'n'drop file uploads.
                    dictFileTooBig: "File is too big ({{filesize}}MiB). Max filesize: {{maxFilesize}}MiB.", // Default: File is too big ({{filesize}}MiB). Max filesize: {{maxFilesize}}MiB.
                    dictInvalidFileType: "You can't upload files of this type.", // Default: You can't upload files of this type.
                    dictResponseError: "Server responded with {{statusCode}} code.", // Default: Server responded with {{statusCode}} code.
                    dictCancelUpload: "Cancel upload.", // Default: Cancel upload
                    dictUploadCanceled: "Upload canceled.", // Default: Upload canceled.
                    dictCancelUploadConfirmation: "Are you sure you want to cancel this upload?", // Default: Are you sure you want to cancel this upload?
                    dictRemoveFile: "Remove file", // Default: Remove file
                    dictRemoveFileConfirmation: null, // Default: null
                    dictMaxFilesExceeded: "You can not upload any more files.", // Default: You can not upload any more files.
                    dictFileSizeUnits: {tb: "TB", gb: "GB", mb: "MB", kb: "KB", b: "b"},
                    sending: function (file, xhr, formData) {
                        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
                    },
                });

                Dropzone.autoDiscover = false;

                hhDropzone.on('dragenter', function () {
                    form.addClass("hover");
                });
                hhDropzone.on('dragleave', function () {
                    form.removeClass("hover");
                });
                hhDropzone.on('drop', function () {
                    form.removeClass("hover");
                });

                hhDropzone.on("success", function (file, response) {
                    $('.hh-loading', form).hide();
                    response = JSON.parse(response);
                    if (typeof response === 'object') {
                        base.alert(response);
                    }
                });
                hhDropzone.on("queuecomplete", function () {
                    base.listMedia(el);
                });
                hhDropzone.on("addedfile", function () {
                    $('.hh-loading', form).show();
                });
                hhDropzone.on('error', function (file, response) {
                    if (typeof response === 'string') {
                        $(file.previewElement).find('.dz-error-message').text(response);
                        base.alert({
                            status: 0,
                            message: response
                        });
                    } else {
                        base.alert(response);
                    }
                    $('.hh-loading', form).hide();
                });
            });
        },
        listMedia: function (el) {
            let base = this;
            let media_wrapper = $('.hh-all-media', el);
            if (media_wrapper.length === 0) {
                return false;
            }
            let form = $('form.form-all-media', media_wrapper),
                url = form.attr('action'),
                loading = $('.hh-loading', media_wrapper);
            let data = form.serializeArray();
            data.push({
                name: '_token',
                value: $('meta[name="csrf-token"]').attr('content')
            });
            loading.show();
            $.post(url, data, function (respon) {
                if (typeof respon === 'object') {
                    if (respon.status === 0) {
                        base.alert(respon);
                    }
                    if (respon.html !== '') {
                        $('.hh-all-media-render .render', media_wrapper).html(respon.html);
                        base.initCheckAll(el);
                    }
                }
                loading.hide();
            }, 'json');
        },
        mediaEvents: function () {
            $('#hh-media-item-modal').off('show.bs.modal').on('show.bs.modal', function (e) {
                let target = $(e.relatedTarget),
                    t = $(this),
                    loading = $('.hh-loading ', t);

                let data = {
                    attachment_id: target.attr('data-attachment-id'),
                    _token: $('meta[name="csrf-token"]').attr('content')
                };
                $('.modal-body', t).empty();
                loading.show();
                $.post(target.attr('data-url'), data, function (respon) {
                    if (typeof respon === 'object') {
                        if (respon.status === 0) {
                            base.alert(respon);
                        }
                        $('.modal-body', t).html(respon.html);
                    }
                    loading.hide();
                }, 'json');
            });
        },
        getFileType: function (fileName) {
            var fileType = (/[.]/.exec(fileName)) ? /[^.]+$/.exec(fileName) : undefined;
            return fileType[0];
        },
        alert: function (respon) {
            if (respon.status === 0) {
                $.toast({
                    heading: respon.title,
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
                        heading: respon.title,
                        text: respon.message,
                        icon: 'success',
                        loaderBg: '#5ba035',
                        position: 'bottom-right',
                        allowToastClose: false,
                        hideAfter: 2000
                    });
                } else {
                    $.toast({
                        heading: respon.title,
                        text: respon.message,
                        icon: 'info',
                        loaderBg: '#26afa4',
                        position: 'bottom-right',
                        allowToastClose: false,
                        hideAfter: 2000
                    });
                }
            }
        },
        initGlobal: function (el) {
            let base = this;

            $('.hh-get-modal-content', el).on('show.bs.modal', function (ev) {
                var t = $(this),
                    loader = $('.hh-loading', t),
                    target = $(ev.relatedTarget);

                el.addClass('hh-modal-open');
                let params = target.attr('data-params');
                if (typeof params === 'string' && params !== '') {
                    var data = JSON.parse(Base64.decode(target.attr('data-params')));
                } else {
                    var data = {};
                }
                if (typeof data == 'object') {
                    data['_token'] = $('meta[name="csrf-token"]').attr('content');

                    loader.show();
                    $('.modal-body', t).empty();

                    $.post(t.attr('data-url'), data, function (respon) {
                        if (typeof respon == 'object') {
                            if (respon.status === 1) {
                                $('.modal-body', t).html(respon.html);
                                $('body').trigger('hh_modal_render_content', [t]);
                                setTimeout(function () {
                                    base.initMultiLanguages();
                                }, 200);
                            } else {
                                t.modal('hide');
                                base.alert(respon);
                            }
                        }
                    }, 'json').always(function () {
                        loader.hide();
                    });
                } else {
                    alert('Have a error when parse the data');
                }
            });

            $('.hh-get-modal-content', el).on('hide.bs.modal', function (ev) {
                el.removeClass('hh-modal-open');
            });

            $(document).on('change', '.hh-delete-user-option input[type="radio"]', function () {
                $(this).closest('.hh-delete-user-option').toggleClass('need-assign');
            });


        }
    };
    HHActions.init(body);

    $('.booking-analytics', body).each(function () {
        let t = $(this);
        let _label = JSON.parse(Base64.decode(t.data('label')));
        let _data = JSON.parse(Base64.decode(t.data('total')));
        let _date = JSON.parse(Base64.decode(t.data('date')));
        let content = hh_params.currency.position == 'left' ? hh_params.currency.symbol + '%y' : '%y' + hh_params.currency.symbol;
        setTimeout(function () {
            $.plot(t.get(0),
                [
                    {label: _label[0], data: _data['completed'], lines: {show: !0, fill: !0}, points: {show: !0}},
                    {label: _label[1], data: _data['incomplete'], lines: {show: !0}, points: {show: !0}},
                    {label: _label[2], data: _data['canceled'], lines: {show: !0}, points: {show: !0}},
                    {label: _label[3], data: _data['refunded'], lines: {show: !0}, points: {show: !0}},
                ],
                {
                    series: {
                        shadowSize: 0
                    },
                    grid: {
                        hoverable: !0,
                        clickable: !0,
                        tickColor: "#f9f9f9",
                        borderWidth: 1,
                        borderColor: "#eeeeee"
                    },
                    colors: ["#1abc9c", "#f7b84b", "#FF5042", "#516a77"],
                    tooltip: !0,
                    tooltipOpts: {
                        content: content,
                        shifts: {
                            x: -60,
                            y: 25
                        },
                        defaultTheme: !1
                    },
                    legend: {
                        position: "ne",
                        margin: [0, -32],
                        noColumns: 0,
                        labelBoxBorderColor: null,
                        labelFormatter: function (o, t) {
                            return o + "&nbsp;&nbsp;"
                        },
                        width: 30,
                        height: 2
                    },
                    yaxis: {
                        axisLabel: "Point Value (1000)",
                        tickColor: "#f5f5f5",
                        font: {
                            color: "#bdbdbd"
                        }
                    },
                    xaxis: {
                        axisLabel: "Daily Hours",
                        ticks: _date,
                        tickColor: "#f5f5f5",
                        font: {
                            color: "#bdbdbd"
                        }
                    }
                }
            );
        }, 600);
    });

    $('.avatar-preview .change-avatar').click(function (ev) {
        let t = $(this),
            parent = t.closest('.profile-preview');
        $('.add-attachment', parent).trigger('click');

        parent.on('input_upload_changed', 'input.input-upload', function (ev, url, val) {
            let t = $(this),
                parent = t.closest('.profile-preview');
            $('.avatar', parent).attr('src', url[0]);

            $('form', parent).submit();
        });
    });

    let ajax_run = false;
    $('.form-quick-settings', body).on('keyup change', 'input[type="text"], input[type="number"],input[type="checkbox"]', function (ev) {
        let t = $(this),
            form = t.closest('form'),
            data = form.serializeArray(),
            loading = $('.hh-loading', form);

        data.push({
            name: '_token',
            value: $('meta[name="csrf-token"]').attr('content'),
        });
        if (ajax_run) {
            ajax_run.abort();
        }
        loading.show();
        ajax_run = $.post(form.attr('action'), data, function (respon) {
            if (typeof respon == 'object') {
                console.log(respon.message);
            }
            loading.hide();
        }, 'json');
    });

    $('select[name="show_analytics_by"]').change(function (ev) {
        let t = $(this),
            val = t.val(),
            url = $('option:selected', t).attr('data-url');
        if (url) {
            window.location.href = url;
        }
    });

    let AweBookingSeo = {
        init: function () {
            let $seo_wrapper = $('.card-seo-options');

            if (hh_params.enable_seo === 'off' || (hh_params.enable_seo === 'on' && $seo_wrapper.length === 0)) {
                return false;
            }

            this.events();
            this.detectSeo();
        },
        events: function () {
            let base = this;

            let $seo_wrapper = $('.card-seo-options'),
                $form = $('form', $seo_wrapper);

            base._form($seo_wrapper, $form, 0);

            $form.on('submit', function (ev) {
                ev.preventDefault();

                base._form($seo_wrapper, $form);
            })
        },
        detectSeo: function ($se) {
            let base = this;

            let $seo_wrapper = $('.card-seo-options');
            let ajax_running = false;
            let timeout = false;

            $seo_wrapper.on('keyup', '.seo-detect', function (ev) {
                ev.preventDefault();

                let $input = $(this),
                    $form = $input.closest('form');

                clearTimeout(timeout);

                timeout = setTimeout(function () {
                    if (ajax_running) {
                        ajax_running.abort();
                    }

                    base._form($seo_wrapper, $form, false);

                }, 300);
            });

            $seo_wrapper.on('change', '.seo-detect.upload_value', function (ev) {
                ev.preventDefault();

                let $input = $(this),
                    $form = $input.closest('form');

                clearTimeout(timeout);

                timeout = setTimeout(function () {
                    if (ajax_running) {
                        ajax_running.abort();
                    }

                    base._form($seo_wrapper, $form, false);

                }, 300);
            })
        },
        _form: function ($seo_wrapper, $form, loading = 1) {
            let base = this;

            let data = $form.serializeArray();
            data.push({
                name: '_token',
                value: $('meta[name="csrf-token"]').attr('content'),
            });

            if (loading) {
                $('.hh-loading', $form).show();
            }

            $.post($form.attr('action'), data, function (respon) {
                if (typeof respon == 'object') {
                    if (respon.status === 1) {
                        if (typeof respon.render == 'object') {
                            base.renderSeo(respon.render);
                        }
                    } else {
                        HHActions.alert(respon);
                    }
                }
            }, 'json').always(function () {
                if (loading) {
                    $('.hh-loading', $form).hide();
                }
            });
        },
        renderSeo: function (data) {
            $.each(data, function (seo_key, seo_value) {
                let target = $('#' + seo_key);
                if (target.length && target.is('[data-seo-detect]')) {
                    let seo_target = target.attr('data-seo-detect');
                    if (seo_target !== '' && $('[' + seo_target + ']').length) {
                        $('[' + seo_target + ']').html(seo_value);
                    }
                }
            })
        }
    };
    AweBookingSeo.init();

})(jQuery);
