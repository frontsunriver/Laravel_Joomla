(function ($) {
    'use strict';

    moment.tz.setDefault(hh_params.timezone);

    var isReload = true;

    //After page loaded
    $(document).ready(function () {

        let Search_Result_Page = {
            search_container: '',
            xhr: null,
            dataFilter: [],
            currentURL: window.location,
            mapObject: undefined,
            checkHover: -1,
            allPopups: [],
            bounds: new mapboxgl.LngLatBounds(),
            init: function (el) {
                let base = this;
                base.search_container = $(el);
                base.dataFilter = base.urlToArray();
                base._eventsSearch();
                base._events();
                base._searchCallBack();
            },
            _setHeightRender: function (set) {
                let base = this;
                let body = $('body');
                let search_wrapper = $('.hh-search-content-wrapper', base.search_container);
                if (set) {
                    search_wrapper.css({
                        'height': $(window).height() - $('#header', body).outerHeight() - $('.hh-search-bar-wrapper', base.search_container).outerHeight()
                    });
                } else {
                    search_wrapper.css({
                        'height': ''
                    });
                }
            },
            _events: function () {
                let base = this;
                let body = $('body');
                // stop event when click the dropdown menu
                $('.hh-search-bar-buttons .dropdown-menu', base.search_container).on('click', function (event) {
                    event.stopPropagation();
                });


                // Click to show filter box on mobile
                $('#show-filter-mobile', base.search_container).on('click', function () {
                    $('#filter-mobile-box', base.search_container).fadeIn();
                    $('body').css({'overflow': 'hidden'});
                });

                // Click to hide filter box on mobile
                $('#filter-mobile-box .popup-filter-close, #filter-mobile-box .view-result', base.search_container).on('click', function () {
                    $('#filter-mobile-box', base.search_container).fadeOut();
                    $('body').css({'overflow': ''});
                });

                // Resize window to apply searching
                $(window).on('resize', function () {
                    if (window.matchMedia("(min-width: 992px)").matches) {
                        let search_wrapper = $('.hh-search-content-wrapper', base.search_container);
                        if (!search_wrapper.hasClass('no-map')) {
                            base._setHeightRender(true);
                            if ($('.hh-search-results-map', search_wrapper).length && !$('.hh-search-results-map', search_wrapper).is(':visible') && !search_wrapper.hasClass('no-map')) {
                                $('.hh-search-results-map', search_wrapper).show();
                            }
                            search_wrapper.trigger('hh_resize_map_search_container');
                            setTimeout(function () {
                                $.fn.slimScroll && $('.hh-search-results-render .render', base.search_container).slimScroll({
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
                            }, 150);
                            $('body').addClass('hide-footer');
                        }

                    } else {
                        let search_wrapper = $('.hh-search-content-wrapper', base.search_container);
                        base._setHeightRender(false);
                        if ($('.hh-search-results-map', base.search_container).length && $('.hh-search-results-map', base.search_container).is(':visible') && search_wrapper.hasClass('no-map')) {
                            $('.hh-search-results-map', base.search_container).hide();
                        }
                        $.fn.slimScroll && $('.hh-search-results-render .render', base.search_container).slimScroll({destroy: true});
                        $('body').removeClass('hide-footer');
                    }
                }).resize();


                let allowMoveMap = $('#chk-map-move', base.search_container).is(':checked');
                $('#show-map-mobile', base.search_container).click(function () {
                    $('.hh-search-results-map', base.search_container).addClass('map-popup');
                    base.mapObject.resize();
                    if (!allowMoveMap) {
                        base.mapObject.fitBounds(base.bounds, {
                            padding: 70
                        });
                    }
                    $('body').css({'overflow': 'hidden'});
                });


                $('#hide-map-mobile', base.search_container).click(function () {
                    $('.hh-search-results-map', base.search_container).removeClass('map-popup').fadeOut();
                    $('body').css({'overflow': ''});
                });
            },
            _eventsSearch: function () {
                let base = this;

                //Layout
                base.search_container.on('click', '.hh-search-results-render .item-layout span', function (ev) {
                    ev.preventDefault();
                    if (!$(this).hasClass('active')) {
                        $('.hh-search-results-render .item-layout span').removeClass('active');
                        $(this).addClass('active');
                        let layoutSelected = $(this).data('layout');
                        if (layoutSelected !== 'grid' && layoutSelected !== 'list') {
                            layoutSelected = 'grid';
                        }
                        base.dataFilter['layout'] = layoutSelected;
                        base._searchCallBack(true, true);
                        base.pushStateToFilter('layout', layoutSelected);
                    }
                });

                //Pagination
                base.search_container.on('click', '.pagination li a', function (ev) {
                    ev.preventDefault();
                    if (!$(this).parent().hasClass('active') && $(this).data('pagination') !== undefined) {
                        let pageSelected = parseInt($(this).data('pagination'));
                        if (pageSelected <= 0) {
                            pageSelected = 1;
                        }
                        base.dataFilter['page'] = pageSelected;
                        base._searchCallBack(true, true);
                        base.pushStateToFilter('page', pageSelected);
                    }
                });

                //FilerPrice
                $('input[name="price_filter"]', base.search_container).on('hh_ranger_changed', function () {
                    let value = $(this).val();
                    if (base.dataFilter['price_filter'] !== value) {
                        base.dataFilter['price_filter'] = value;
                        base._resetPage();
                        base._searchCallBack();
                        base.pushStateToFilter('price_filter', value);
                    }
                });

                //DateRangePicker
                $('input[name="checkInOut"]', base.search_container).on('daterangepicker_change', function (e, start, end) {
                    let checkIn = start.format('YYYY-MM-DD'),
                        checkOut = end.format('YYYY-MM-DD'),
                        checkInOut = checkIn + '+12:00+am-' + checkOut + '+11:45+pm';
                    if (base.dataFilter['checkIn'] !== checkIn || base.dataFilter['checkOut'] !== checkOut) {
                        base.dataFilter['checkIn'] = checkIn;
                        base.dataFilter['checkOut'] = checkOut;
                        base.dataFilter['checkInOut'] = checkInOut;
                        base._resetPage();
                        base._searchCallBack();
                        base.pushStateToFilter('checkIn', checkIn);
                        base.pushStateToFilter('checkOut', checkOut);
                        base.pushStateToFilter('checkInOut', checkInOut);
                    }
                });

                $('.button-time .dropdown-menu a.apply-time-filter', base.search_container).on('click', function (e) {
                    e.preventDefault();
                    let t = $(this),
                        parent = t.closest('.dropdown-menu');

                    let start_time = $('input[name="checkInTime"]', parent).val(),
                        end_time = $('input[name="checkOutTime"]', parent).val();

                    if (typeof start_time == 'string') {
                        base.dataFilter['checkInTime'] = start_time;
                        base.pushStateToFilter('checkInTime', start_time);
                    } else {
                        base.dataFilter['checkInTime'] = '';
                        base.pushStateToFilter('checkInTime', '', true);
                    }
                    if (typeof end_time == 'string') {
                        base.dataFilter['checkOutTime'] = end_time;
                        base.pushStateToFilter('checkOutTime', end_time);
                    } else {
                        base.dataFilter['checkOutTime'] = '';
                        base.pushStateToFilter('checkOutTime', '', true);
                    }

                    base._resetPage();
                    base._searchCallBack();
                    parent.removeClass('show');
                });

                //Location
                $('input[name="address"]', base.search_container).on('change', function () {
                    let address = $(this),
                        lng = $('input[name="lng"]', base.search_container),
                        lat = $('input[name="lat"]', base.search_container);

                    base.dataFilter['lat'] = parseFloat(lat.val());
                    base.dataFilter['lng'] = parseFloat(lng.val());
                    base.dataFilter['address'] = address.val();
                    base._resetPage();
                    base._searchCallBack();
                    base.pushStateToFilter('lat', parseFloat(lat.val()));
                    base.pushStateToFilter('lng', parseFloat(lng.val()));
                    base.pushStateToFilter('address', address.val());
                });

                $('.filter-mobile-box .dropdown-time .item', base.search_container).on('hh_dropdown_time_item_click', function (ev, el) {
                    if (el.closest('.check-out-render').length) {
                        let container = el.closest('.date-time');
                        let start_time = $('input[name="checkInTime"]', container).val(),
                            end_time = $('input[name="checkOutTime"]', container).val();

                        if (typeof start_time == 'string') {
                            base.dataFilter['checkInTime'] = start_time;
                            base.pushStateToFilter('checkInTime', start_time);
                        } else {
                            base.dataFilter['checkInTime'] = '';
                            base.pushStateToFilter('checkInTime', '', true);
                        }
                        if (typeof end_time == 'string') {
                            base.dataFilter['checkOutTime'] = end_time;
                            base.pushStateToFilter('checkOutTime', end_time);
                        } else {
                            base.dataFilter['checkOutTime'] = '';
                            base.pushStateToFilter('checkOutTime', '', true);
                        }

                        base._resetPage();
                        base._searchCallBack();
                    }
                });

                //Taxonomy
                $('.button-more-filter .dropdown-menu a.apply-more-filter', base.search_container).on('click', function (e) {
                    e.preventDefault();
                    let t = $(this),
                        parent = t.closest('.dropdown-menu');

                    $('.item-filter-wrapper', parent).each(function () {
                        let inputData = $(this).find('input[type="hidden"]');
                        let resTemp = [];
                        $(this).find('input[type="checkbox"]').each(function () {
                            if ($(this).is(':checked')) {
                                resTemp.push($(this).val());
                            }
                        });
                        if (resTemp.length > 0) {
                            inputData.val(resTemp.toString());
                            base.dataFilter[$(this).data('type')] = resTemp.toString();
                            base.pushStateToFilter($(this).data('type'), resTemp.toString());
                        } else {
                            inputData.val('');
                            base.dataFilter[$(this).data('type')] = '';
                            base.pushStateToFilter($(this).data('type'), '', true);
                        }
                    });
                    base._resetPage();
                    base._searchCallBack();
                    parent.removeClass('show');
                });

                $('.filter-mobile-box .dropdown-time .item', base.search_container).on('hh_dropdown_time_item_click', function (ev, el) {
                    if (el.closest('.check-out-render').length) {
                        let container = el.closest('.date-time');
                        let start_time = $('input[name="startTime"]', container).val(),
                            end_time = $('input[name="endTime"]', container).val();

                        if (typeof start_time == 'string') {
                            base.dataFilter['startTime'] = start_time;
                            base.pushStateToFilter('startTime', start_time);
                        } else {
                            base.dataFilter['startTime'] = '';
                            base.pushStateToFilter('startTime', '', true);
                        }
                        if (typeof end_time == 'string') {
                            base.dataFilter['endTime'] = end_time;
                            base.pushStateToFilter('endTime', end_time);
                        } else {
                            base.dataFilter['endTime'] = '';
                            base.pushStateToFilter('endTime', '', true);
                        }

                        base._resetPage();
                        base._searchCallBack();
                    }
                });

                $('.button-time .dropdown-menu a.apply-time-filter', base.search_container).on('click', function (e) {
                    e.preventDefault();
                    let t = $(this),
                        parent = t.closest('.dropdown-menu');

                    let start_time = $('input[name="startTime"]', parent).val(),
                        end_time = $('input[name="endTime"]', parent).val();

                    if (typeof start_time == 'string') {
                        base.dataFilter['startTime'] = start_time;
                        base.pushStateToFilter('startTime', start_time);
                    } else {
                        base.dataFilter['startTime'] = '';
                        base.pushStateToFilter('startTime', '', true);
                    }
                    if (typeof end_time == 'string') {
                        base.dataFilter['endTime'] = end_time;
                        base.pushStateToFilter('endTime', end_time);
                    } else {
                        base.dataFilter['endTime'] = '';
                        base.pushStateToFilter('endTime', '', true);
                    }

                    base._resetPage();
                    base._searchCallBack();
                    parent.removeClass('show');
                });

                //Taxonomy Popup
                $('.popup-tax-filter input[type="checkbox"]', base.search_container).on('change', function (e) {
                    e.preventDefault();
                    let t = $(this),
                        parent = t.closest('.popup-filter-content');
                    $('.popup-tax-filter', parent).each(function () {
                        let inputData = $(this).find('input[type="hidden"]');
                        let resTemp = [];
                        $(this).find('input[type="checkbox"]').each(function () {
                            if ($(this).is(':checked')) {
                                resTemp.push($(this).val());
                            }
                        });
                        if (resTemp.length > 0) {
                            inputData.val(resTemp.toString());
                            base.dataFilter[$(this).data('type')] = resTemp.toString();
                            base.pushStateToFilter($(this).data('type'), resTemp.toString());
                        } else {
                            inputData.val('');
                            base.dataFilter[$(this).data('type')] = '';
                            base.pushStateToFilter($(this).data('type'), '', true);
                        }
                    });
                    base._resetPage();
                    base._searchCallBack();
                    parent.removeClass('show');
                });

                // Toggle map
                $('#hh-map-toggle-search', base.search_container).on('change', function () {
                    base._resetPage();
                    if ($(this).is(':checked')) {
                        base._setHeightRender(true);
                        $('.hh-search-content-wrapper', base.search_container).removeClass('no-map');
                        $('body').addClass('hide-footer');
                        $.fn.slimScroll && $('.hh-search-results-render .render', base.search_container).slimScroll({
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
                        base.dataFilter['number'] = 6;
                        base.dataFilter['showmap'] = 'yes';
                        base.pushStateToFilter('number', 6);
                        base.pushStateToFilter('showmap', 'yes');
                    } else {
                        base._setHeightRender(false);
                        $('.hh-search-content-wrapper', base.search_container).addClass('no-map');
                        $('body').removeClass('hide-footer');
                        setTimeout(function () {
                            $.fn.slimScroll && $('.hh-search-results-render .render', base.search_container).slimScroll({destroy: true});
                        }, 300);

                        base.dataFilter['number'] = 8;
                        base.dataFilter['showmap'] = 'no';
                        base.pushStateToFilter('number', 8);
                        base.pushStateToFilter('showmap', 'no');
                    }
                    setTimeout(function(){
                        base._searchCallBack();
                    }, 75);
                });
            },

            _resetPage: function () {
                let base = this;
                base.dataFilter['page'] = 1;
                base.pushStateToFilter('page', 1);
            },

            _searchCallBack: function (applyMove = true) {
                let base = this;
                if (base.xhr != null) {
                    base.xhr.abort();
                }
                base.dataFilter['_token'] = $('meta[name="csrf-token"]').attr('content');

                let loader = $('.hh-map-tooltip .hh-loading-map', base.search_container);
                if (applyMove) {
                    loader = $('.hh-loading', base.search_container);
                }

                if (!isReload) {
                    loader.show();
                }

                base.xhr = $.post(base.search_container.data('url'), base.dataFilter, function (res) {
                    if (typeof res == 'object') {
                        let renderWrapper = $('.hh-search-results-render', base.search_container),
                            renderString = $('.hh-search-results-string', renderWrapper),
                            renderHtml = $('.hh-search-content', renderWrapper),
                            renderPag = $('.hh-search-pagination', renderWrapper);
                        if (res.status) {
                            renderString.hide().html(res.search_string).fadeIn(300);
                            renderHtml.hide().html(res.html).fadeIn(300);
                            renderPag.hide().html(res.pag).fadeIn(300);

                            setTimeout(function () {
                                if (Object.keys(res.locations).length > 0) {
                                    let locations = res.locations;

                                    if (typeof mapboxgl === 'object' && hh_params.mapbox_token !== '') {
                                        mapboxgl.accessToken = hh_params.mapbox_token;

                                        let centerLocation = [locations[0].lng, locations[0].lat];

                                        let allowMoveMap = $('#chk-map-move', base.search_container).is(':checked');

                                        $('.hh-mapbox-search', base.search_container).each(function () {
                                            if (typeof base.mapObject == 'undefined') {
                                                let t = $(this),
                                                    zoom = 13;

                                                base.mapObject = new mapboxgl.Map({
                                                    container: t.get(0),
                                                    style: 'mapbox://styles/mapbox/light-v10',
                                                    center: centerLocation,
                                                    zoom: zoom
                                                });
                                            }

                                            if (base.allPopups.length) {
                                                base.allPopups.forEach(function (item) {
                                                    item.remove();
                                                });
                                            }

                                            if (res.total > 0) {
                                                locations.forEach(function (item) {
                                                    if ($('.hh-map-price[data-id="' + item.post_id + '"]', base.search_container).length === 0) {
                                                        let popup = new mapboxgl.Popup({
                                                            closeOnClick: false,
                                                            closeButton: false
                                                        }).setLngLat([item.lng, item.lat])
                                                            .setHTML('<div class="hh-map-popup">' +
                                                                '<div class="thumb"><a href="'+ item.url +'"><img src="' +item.thumbnail+ '" alt="'+ item.title +'"/></a></div><div class="content"><a href="'+ item.url +'">'+ item.title +'</a><p class="add">'+ item.address +'</p><p class="pr">'+ item.price +'</p></div>' +
                                                                '</div><div class="hh-map-price" data-id="' + item.post_id + '"><span class="price-innner">' + item.price + '</span></div>')
                                                            .addTo(base.mapObject);
                                                        base.allPopups.push(popup);
                                                    }
                                                    if (!allowMoveMap) {
                                                        base.bounds.extend([item.lng, item.lat]);
                                                    }
                                                });

                                                if (!allowMoveMap) {
                                                    base.mapObject.fitBounds(base.bounds, {
                                                        padding: 70
                                                    });
                                                }
                                            }
                                            $('.hh-service-item', base.search_container).on('mouseover', function () {
                                                if (base.checkHover !== $(this).data('id')) {
                                                    let dataID = $(this).data('id');
                                                    $('.mapboxgl-popup', base.search_container).removeClass('active');
                                                    $('.hh-map-price[data-id="' + dataID + '"]', base.search_container).closest('.mapboxgl-popup').addClass('active');
                                                    let selectedLng = $(this).data('lng');
                                                    let selectedLat = $(this).data('lat');
                                                    base.mapObject.flyTo({
                                                        center: [selectedLng, selectedLat]
                                                    });
                                                    base.checkHover = $(this).data('id');
                                                }
                                            });

                                            base.mapObject.on('dragstart', function () {
                                                base.checkHover = -1;
                                            });

                                            if (applyMove) {
                                                base.mapObject.on('moveend', function () {
                                                    if ($('#chk-map-move').is(':checked') && base.checkHover === -1) {
                                                        let moveLngLat = base.mapObject.getCenter();
                                                        base.dataFilter['lat'] = moveLngLat.lat;
                                                        base.dataFilter['lng'] = moveLngLat.lng;
                                                        base.pushStateToFilter('lat', moveLngLat.lat);
                                                        base.pushStateToFilter('lng', moveLngLat.lng);
                                                        base._resetPage();
                                                        base._searchCallBack(false);
                                                    }
                                                });
                                            }


                                        });
                                    }
                                }

                                $('.hh-map-price').click(function () {
                                    var tt = $(this);
                                    $('.hh-map-price').parent().find('.hh-map-popup').hide();
                                    $('.hh-map-price').closest('.mapboxgl-popup').removeClass('max-zindex');
                                    tt.closest('.mapboxgl-popup').addClass('max-zindex');
                                    tt.parent().find('.hh-map-popup').css({'display' : 'flex'});
                                });

                                $('.hh-search-results-map').on('click', function(ee){
                                    var target = ee.target;
                                    if (!$(target).is('.hh-map-price')) {
                                        $('.hh-map-price').closest('.mapboxgl-popup').removeClass('max-zindex');
                                        $('.hh-map-price').parent().find('.hh-map-popup').hide();
                                    }
                                });


                                $('[data-toggle="tooltip"]').tooltip();
                                $.fn.matchHeight && $('[data-plugin="matchHeight"]').matchHeight();

                                base.search_container.trigger('hh_search_result_loaded');
                            }, 300);
                        }
                        loader.hide();
                    }
                }, 'json');
                isReload = false;
            },
            urlToArray: function () {
                let base = this;
                let res = {};

                if ($('.pagination li.active a', base.search_container).length) {
                    let pagination = parseInt($('.pagination li.active a', base.search_container).data('pagination'));
                    res['page'] = pagination <= 0 ? 1 : pagination;
                } else {
                    res['page'] = 1;
                }

                if ($('.item-layout', '.hh-search-results-render').length) {
                    res['layout'] = $('.item-layout span.active', '.hh-search-results-render').data('layout');
                } else {
                    res['layout'] = 'grid';
                }

                res['current_url'] = $('.hh-search-results-render', base.search_container).data('url');

                let sPageURL = window.location.search.substring(1);
                if (sPageURL !== '') {
                    let sURLVariables = sPageURL.split('&');
                    if (sURLVariables.length) {
                        for (let i = 0; i < sURLVariables.length; i++) {
                            let sParameterName = sURLVariables[i].split('=');
                            if (sParameterName.length) {
                                let val = decodeURIComponent(sParameterName[1]);
                                res[decodeURIComponent(sParameterName[0])] = (val === 'undefined') ? '' : val;
                            }
                        }
                    }
                }

                if(typeof res['showmap'] === 'undefined'){
                    res['showmap'] = 'yes';
                }
                return res;
            },
            pushStateToFilter: function (key, value, del = false) {
                let base = this;
                let url = new URL(base.currentURL);
                let query_string = url.search;
                let search_params = new URLSearchParams(query_string);

                if (del) {
                    if (search_params.has(key)) {
                        search_params.delete(key);
                    }
                } else {
                    if (search_params.has(key)) {
                        search_params.set(key, value);
                    } else {
                        search_params.append(key, value);
                    }
                }

                url.search = search_params.toString();
                base.currentURL = url.toString();
                window.history.pushState({path: base.currentURL}, '', base.currentURL);
            }
        };

        Search_Result_Page.init('.hh-search-result');
    });

})(jQuery);
