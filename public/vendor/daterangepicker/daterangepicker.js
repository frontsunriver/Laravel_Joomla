(function (root, factory) {
    "use strict";
    if (typeof define === 'function' && define.amd) {
        define(['moment', 'jquery'], function (moment, jquery) {
            return (root.daterangepicker = factory(moment, jquery));
        });
    } else if (typeof module === 'object' && module.exports) {
        var jQuery = (typeof window != 'undefined') ? window.jQuery : undefined;
        if (!jQuery) {
            jQuery = require('jquery');
            if (!jQuery.fn) jQuery.fn = {};
        }
        module.exports = factory(require('moment'), jQuery);
    } else {
        root.daterangepicker = factory(root.moment, root.jQuery);
    }
}(this, function (moment, $) {
    "use strict";
    var DateRangePicker = function (element, options, cb) {
        this.parentEl = 'body';
        this.element = $(element);
        this.startDate = moment().startOf('day');
        this.endDate = moment().endOf('day');
        this.minDate = false;
        this.maxDate = false;
        this.dateFlex = false;
        this.dateLimit = false;
        this.autoApply = false;
        this.singleDatePicker = false;
        this.linkedCalendars = true;
        this.autoUpdateInput = true;
        this.alwaysShowCalendars = false;
        this.ranges = {};
        this.showTodayButton = false;
        this.disabledPast = false;
        this.dateFormat = 'YYYY-MM-DD';
        this.timeFormat = 'hh:mm a';
        this.singleDay = false;
        this.widthCalendar = 650;
        this.widthSingle = 300;
        this.breakEvent = true;
        this.position = '';
        this.maybeFixed = '';
        this.opens = 'right';
        if (this.element.hasClass('pull-right'))
            this.opens = 'left';
        this.drops = 'down';
        if (this.element.hasClass('dropup'))
            this.drops = 'up';
        this.buttonClasses = 'btn btn-small';
        this.applyClass = 'btn-primary ';
        this.cancelClass = 'btn-ghost';
        this.locale = {
            direction: 'ltr',
            format: 'YYYY-MM-DD HH:mm',
            separator: '-',
            applyLabel: 'Apply',
            cancelLabel: 'Cancel',
            weekLabel: 'W',
            customRangeLabel: 'Custom Range',
            daysOfWeek: moment.weekdaysMin(),
            monthNames: moment.monthsShort(),
            firstDay: moment.localeData().firstDayOfWeek(),
            today: 'Today',
            labelStartTime: '<svg height="20px" width="20px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"\n\t viewBox="0 0 24 24" style="enable-background:new 0 0 24 24;" xml:space="preserve">\n\n<g fill="#000000">\n\t<path d="M12,23.25C5.797,23.25,0.75,18.203,0.75,12C0.75,5.797,5.797,0.75,12,0.75c6.203,0,11.25,5.047,11.25,11.25\n\t\tC23.25,18.203,18.203,23.25,12,23.25z M12,2.25c-5.376,0-9.75,4.374-9.75,9.75s4.374,9.75,9.75,9.75s9.75-4.374,9.75-9.75\n\t\tS17.376,2.25,12,2.25z"/>\n\t<path d="M15.75,16.5c-0.2,0-0.389-0.078-0.53-0.22l-2.25-2.25c-0.302,0.145-0.632,0.22-0.969,0.22c-1.241,0-2.25-1.009-2.25-2.25\n\t\tc0-0.96,0.615-1.808,1.5-2.121V5.25c0-0.414,0.336-0.75,0.75-0.75s0.75,0.336,0.75,0.75v4.629c0.885,0.314,1.5,1.162,1.5,2.121\n\t\tc0,0.338-0.075,0.668-0.22,0.969l2.25,2.25c0.292,0.292,0.292,0.768,0,1.061C16.139,16.422,15.95,16.5,15.75,16.5z M12,11.25\n\t\tc-0.414,0-0.75,0.336-0.75,0.75s0.336,0.75,0.75,0.75s0.75-0.336,0.75-0.75S12.414,11.25,12,11.25z"/>\n</g>\n</svg><span>Start at</span>',
            labelEndTime: '<svg height="20px" width="20px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"\n\t viewBox="0 0 24 24" style="enable-background:new 0 0 24 24;" xml:space="preserve">\n\n<g fill="#000000">\n\t<path d="M12,23.25C5.797,23.25,0.75,18.203,0.75,12C0.75,5.797,5.797,0.75,12,0.75c6.203,0,11.25,5.047,11.25,11.25\n\t\tC23.25,18.203,18.203,23.25,12,23.25z M12,2.25c-5.376,0-9.75,4.374-9.75,9.75s4.374,9.75,9.75,9.75s9.75-4.374,9.75-9.75\n\t\tS17.376,2.25,12,2.25z"/>\n\t<path d="M15.75,16.5c-0.2,0-0.389-0.078-0.53-0.22l-2.25-2.25c-0.302,0.145-0.632,0.22-0.969,0.22c-1.241,0-2.25-1.009-2.25-2.25\n\t\tc0-0.96,0.615-1.808,1.5-2.121V5.25c0-0.414,0.336-0.75,0.75-0.75s0.75,0.336,0.75,0.75v4.629c0.885,0.314,1.5,1.162,1.5,2.121\n\t\tc0,0.338-0.075,0.668-0.22,0.969l2.25,2.25c0.292,0.292,0.292,0.768,0,1.061C16.139,16.422,15.95,16.5,15.75,16.5z M12,11.25\n\t\tc-0.414,0-0.75,0.336-0.75,0.75s0.336,0.75,0.75,0.75s0.75-0.336,0.75-0.75S12.414,11.25,12,11.25z"/>\n</g>\n</svg><span>End at</span>',
        };
        this.callback = function () {
        };
        this.isShowing = false;
        this.leftCalendar = {};
        this.rightCalendar = {};
        this.disabledDates = [];
        this.enableLoading = false;
        this.loadingText = '<div class="hh-loading">\n' +
            '    <div class="lds-ellipsis">\n' +
            '        <div></div>\n' +
            '        <div></div>\n' +
            '        <div></div>\n' +
            '        <div></div>\n' +
            '    </div>\n' +
            '</div>';
        this.allEvents = [];
        this.fetchEvents = null;
        this.customClass = '';
        this.sameDate = false;
        this.singleClick = true;
        this.minimumCheckin = 0;
        this.autoResponsive = false;
        this.onlyShowCurrentMonth = false;
        if (typeof options !== 'object' || options === null)
            options = {};
        options = $.extend(this.element.data(), options);
        if (typeof options.disabledDates == 'object') {
            this.disabledDates = options.disabledDates;
        }
        if (typeof options.maybeFixed == 'string') {
            this.maybeFixed = options.maybeFixed;
        }
        if (typeof options.showCalendar == 'boolean') {
            this.showCalendar = options.showCalendar;
        }
        if (typeof options.breakEvent == 'boolean') {
            this.breakEvent = options.breakEvent;
        }
        if (typeof options.onlyShowCurrentMonth == 'boolean') {
            this.onlyShowCurrentMonth = options.onlyShowCurrentMonth;
        }
        if (typeof options.autoResponsive == 'boolean') {
            this.autoResponsive = options.autoResponsive;
        }
        if (typeof options.alwaysShow == 'boolean') {
            this.alwaysShow = options.alwaysShow;
        }
        if (typeof options.showTodayButton == 'boolean') {
            this.showTodayButton = options.showTodayButton;
        }
        if (typeof options.disabledPast == 'boolean') {
            this.disabledPast = options.disabledPast;
        }
        if (typeof options.enableLoading == 'boolean') {
            this.enableLoading = options.enableLoading;
        }
        if (typeof options.loadingText == 'string') {
            this.loadingText = options.loadingText;
        }
        if (typeof options.position == 'string') {
            this.position = options.position;
        }
        if (typeof options.allEvents == 'object') {
            this.allEvents = options.allEvents;
        }
        if (typeof options.fetchEvents == 'function') {
            this.fetchEvents = options.fetchEvents;
        }
        if (typeof options.customClass == 'string') {
            this.customClass = options.customClass;
        }
        if (typeof options.sameDate == 'boolean') {
            this.sameDate = options.sameDate;
        }
        if (typeof options.dateFlex == 'boolean') {
            this.dateFlex = options.dateFlex;
        }
        if (typeof options.singleClick == 'boolean') {
            this.singleClick = options.singleClick;
        }
        if (typeof options.dateFormat == 'string') {
            this.dateFormat = options.dateFormat;
        }
        if (typeof options.minimumCheckin == 'number') {
            this.minimumCheckin = options.minimumCheckin;
        }
        if (typeof options.widthSingle == 'number') {
            this.widthSingle = options.widthSingle;
        }
        if (typeof options.widthCalendar == 'number') {
            this.widthCalendar = options.widthCalendar;
        }
        if (typeof options.classNotAvailable == 'object') {
            this.classNotAvailable = options.classNotAvailable;
        }
        if (typeof options.showEventTooltip == 'boolean') {
            this.showEventTooltip = options.showEventTooltip;
        }
        if (typeof options.singleDay == 'boolean') {
            this.singleDay = options.singleDay;
        }
        if (typeof options.template !== 'string' && !(options.template instanceof $)) {
            options.template = '<div class="daterangepicker dropdown-menu ' + this.customClass + '">' +
                '<div class="calendar left">' +
                    '<div class="calendar-table"></div>' +
                    '<div class="daterangepicker_input">' + '<input class="input-mini form-control" type="text" name="daterangepicker_start" value="" />' + '</div>' +
                '</div>' +
                '<div class="calendar right">' +
                    '<div class="calendar-table"></div>' +
                    '<div class="daterangepicker_input">' + '<input class="input-mini form-control" type="text" name="daterangepicker_enddaterangepicker_end" value="" />' + '</div>' +
                '</div>';
            if (this.enableLoading) {
                options.template += this.loadingText;
            }

            options.template += '</div>';
        }
        this.parentEl = (options.parentEl && $(options.parentEl).length) ? $(options.parentEl) : $(this.parentEl);
        if (this.parentEl.is('body')) {
            this.container = $(options.template).appendTo($('body'));
        } else {
            if (!this.parentEl.is('input')) {
                this.container = $(options.template).appendTo(this.parentEl);
            } else {
                this.container = $(options.template).appendTo(this.parentEl.parent());
            }
            if (this.position) {
                this.container.css({'position': this.position});
            } else {
                this.container.css({'position': 'fixed'});
            }
        }
        if (typeof options.locale === 'object') {
            if (typeof options.locale.direction === 'string')
                this.locale.direction = options.locale.direction;
            if (typeof options.locale.separator === 'string')
                this.locale.separator = options.locale.separator;
            if (typeof options.locale.daysOfWeek === 'object')
                this.locale.daysOfWeek = options.locale.daysOfWeek.slice();
            if (typeof options.locale.monthNames === 'object')
                this.locale.monthNames = options.locale.monthNames.slice();
            if (typeof options.locale.firstDay === 'number')
                this.locale.firstDay = options.locale.firstDay;
            if (typeof options.locale.applyLabel === 'string')
                this.locale.applyLabel = options.locale.applyLabel;
            if (typeof options.locale.cancelLabel === 'string')
                this.locale.cancelLabel = options.locale.cancelLabel;
            if (typeof options.locale.weekLabel === 'string')
                this.locale.weekLabel = options.locale.weekLabel;
            if (typeof options.locale.customRangeLabel === 'string')
                this.locale.customRangeLabel = options.locale.customRangeLabel;
            if (typeof options.locale.labelStartTime === 'string')
                this.locale.labelStartTime = options.locale.labelStartTime;
            if (typeof options.locale.labelEndTime === 'string')
                this.locale.labelEndTime = options.locale.labelEndTime;
        }
        this.container.addClass(this.locale.direction);
        if (typeof options.startDate === 'string') {
            this.startDate = moment(options.startDate, this.locale.format);
        }
        if (typeof options.endDate === 'string')
            this.endDate = moment(options.endDate, this.locale.format);
        if (typeof options.minDate === 'string')
            this.minDate = moment(options.minDate, this.locale.format);
        if (typeof options.maxDate === 'string')
            this.maxDate = moment(options.maxDate, this.locale.format);
        if (typeof options.startDate === 'object')
            this.startDate = moment(options.startDate);
        if (typeof options.endDate === 'object')
            this.endDate = moment(options.endDate);
        if (typeof options.minDate === 'object')
            this.minDate = moment(options.minDate);
        if (typeof options.maxDate === 'object')
            this.maxDate = moment(options.maxDate);
        if (this.minDate && this.startDate.isBefore(this.minDate))
            this.startDate = this.minDate.clone();
        if (this.maxDate && this.endDate.isAfter(this.maxDate))
            this.endDate = this.maxDate.clone();
        if (typeof options.applyClass === 'string')
            this.applyClass = options.applyClass;
        if (typeof options.cancelClass === 'string')
            this.cancelClass = options.cancelClass;
        if (typeof options.dateLimit === 'object')
            this.dateLimit = options.dateLimit;
        if (typeof options.opens === 'string')
            this.opens = options.opens;
        if (typeof options.drops === 'string')
            this.drops = options.drops;
        if (typeof options.buttonClasses === 'string')
            this.buttonClasses = options.buttonClasses;
        if (typeof options.buttonClasses === 'object')
            this.buttonClasses = options.buttonClasses.join(' ');
        if (typeof options.singleDatePicker === 'boolean') {
            this.singleDatePicker = options.singleDatePicker;
            if (this.singleDatePicker && this.sameDate && this.singleClick) {
                this.endDate = this.startDate.clone();            }

        }
        if (typeof options.autoApply === 'boolean')
            this.autoApply = options.autoApply;
        if (typeof options.autoUpdateInput === 'boolean')
            this.autoUpdateInput = options.autoUpdateInput;
        if (typeof options.linkedCalendars === 'boolean')
            this.linkedCalendars = options.linkedCalendars;
        if (typeof options.isInvalidDate === 'function')
            this.isInvalidDate = options.isInvalidDate;
        if (typeof options.isCustomDate === 'function')
            this.isCustomDate = options.isCustomDate;
        if (typeof options.alwaysShowCalendars === 'boolean')
            this.alwaysShowCalendars = options.alwaysShowCalendars;
        if (this.locale.firstDay != 0) {
            var iterator = this.locale.firstDay;
            while (iterator > 0) {
                this.locale.daysOfWeek.push(this.locale.daysOfWeek.shift());
                iterator--;
            }
        }
        this.locale.format = this.dateFormat + ' ' + this.timeFormat;
        var start, end, range;
        if (typeof options.startDate === 'undefined' && typeof options.endDate === 'undefined') {
            if ($(this.element).is('input[type=text]')) {
                var val = $(this.element).val(), split = val.split(this.locale.separator);
                start = end = null;
                if (split.length == 2) {
                    start = moment(split[0], this.locale.format);
                    end = moment(split[1], this.locale.format);
                } else if (this.singleDatePicker && this.sameDate && val !== "") {
                    start = moment(val, this.locale.format);
                    end = moment(val, this.locale.format);
                }
                if (start !== null && end !== null) {
                    this.setStartDate(start);
                    this.setEndDate(end);
                }
            }
        }

        if (typeof cb === 'function') {
            this.callback = cb;
        }
        if (this.autoApply) {
            this.container.find('.applyBtn, .cancelBtn').addClass('hide');
        }
        if (this.singleDatePicker) {
            this.container.addClass('single');
            this.container.find('.calendar.left').addClass('single');
            this.container.find('.calendar.left').show();
            this.container.find('.calendar.right').hide();
            this.container.find('.daterangepicker_input input, .daterangepicker_input > i').hide();
            this.container.find('.ranges').hide();
        }
        this.container.find('.input-mini').hide();

        if (this.alwaysShowCalendars) {
            this.container.addClass('always-calendar');
        }
        this.container.addClass('opens' + this.opens);
        if (typeof options.ranges !== 'undefined' && this.opens == 'right') {
            this.container.find('.ranges').prependTo(this.container.find('.calendar.left').parent());
        }
        this.container.find('.applyBtn, .cancelBtn').addClass(this.buttonClasses);
        if (this.applyClass.length)
            this.container.find('.applyBtn').addClass(this.applyClass);
        if (this.cancelClass.length)
            this.container.find('.cancelBtn').addClass(this.cancelClass);
        this.container.find('.applyBtn').html(this.locale.applyLabel);
        this.container.find('.cancelBtn').html(this.locale.cancelLabel);
        this.container.find('.calendar').on('click.daterangepicker', '.prev', $.proxy(this.clickPrev, this)).on('click.daterangepicker', '.next', $.proxy(this.clickNext, this)).on('click.daterangepicker', '.btn-today', $.proxy(this.clickToday, this)).on('click.daterangepicker', 'td.available .date', $.proxy(this.clickDate, this)).on('mouseenter.daterangepicker', 'td.available .date', $.proxy(this.hoverDate, this)).on('mouseleave.daterangepicker', 'td.available .date', $.proxy(this.updateFormInputs, this)).on('change.daterangepicker', 'select.yearselect', $.proxy(this.monthOrYearChanged, this)).on('change.daterangepicker', 'select.monthselect', $.proxy(this.monthOrYearChanged, this)).on('click.daterangepicker', '.daterangepicker_input input', $.proxy(this.showCalendars, this)).on('focus.daterangepicker', '.daterangepicker_input input', $.proxy(this.formInputsFocused, this)).on('blur.daterangepicker', '.daterangepicker_input input', $.proxy(this.formInputsBlurred, this)).on('change.daterangepicker', '.daterangepicker_input input', $.proxy(this.formInputsChanged, this));
        this.container.find('.ranges').on('click.daterangepicker', 'button.applyBtn', $.proxy(this.clickApply, this)).on('click.daterangepicker', 'button.cancelBtn', $.proxy(this.clickCancel, this)).on('mouseleave.daterangepicker', 'li', $.proxy(this.updateFormInputs, this));
        if (this.element.is('input') || this.element.is('button')) {
            this.element.on({
                'click.daterangepicker': $.proxy(this.show, this),
                'focus.daterangepicker': $.proxy(this.show, this),
                'keyup.daterangepicker': $.proxy(this.elementChanged, this),
                'keydown.daterangepicker': $.proxy(this.keydown, this)
            });
        } else {
            this.element.on('click.daterangepicker', $.proxy(this.toggle, this));
        }

        if (!this.parentEl.is('body')) {
            $(window).on('scroll', $.proxy(this.move, this));
        }

        if (this.element.is('input') && !this.singleDatePicker && this.autoUpdateInput) {
            this.element.val(this.startDate.format(this.locale.format) + this.locale.separator + this.endDate.format(this.locale.format));
            this.element.trigger('change');
        } else if (this.element.is('input') && this.autoUpdateInput) {
            this.element.val(this.startDate.format(this.locale.format));
            this.element.trigger('change');
        }
    };
    DateRangePicker.prototype = {
        constructor: DateRangePicker,
        setStartDate: function (startDate) {
            if (typeof startDate === 'string')
                this.startDate = moment(startDate, this.locale.format);
            if (typeof startDate === 'object')
                this.startDate = moment(startDate);

            this.startDate = this.startDate.startOf('day');
            if (this.minDate && this.startDate.isBefore(this.minDate)) {
                this.startDate = this.minDate;
            }
            if (this.maxDate && this.startDate.isAfter(this.maxDate)) {
                this.startDate = this.maxDate;
            }
            if (!this.isShowing)
                this.updateElement();
            this.updateMonthsInView();
        }, setEndDate: function (endDate) {
            if (typeof endDate === 'string')
                this.endDate = moment(endDate, this.locale.format);
            if (typeof endDate === 'object')
                this.endDate = moment(endDate);
            this.endDate = this.endDate.endOf('day');
            if (this.endDate.isBefore(this.startDate))
                this.endDate = this.startDate.clone();
            if (this.maxDate && this.endDate.isAfter(this.maxDate))
                this.endDate = this.maxDate;
            if (this.dateLimit && this.startDate.clone().add(this.dateLimit).isBefore(this.endDate))
                this.endDate = this.startDate.clone().add(this.dateLimit);
            this.previousRightTime = this.endDate.clone();
            if (!this.isShowing)
                this.updateElement();
            this.updateMonthsInView();
        }, isInvalidDate: function () {
            return false;
        }, isCustomDate: function () {
            return false;
        }, updateView: function () {
            if (this.endDate) {
                this.container.find('input[name="daterangepicker_end"]').removeClass('active');
                this.container.find('input[name="daterangepicker_start"]').addClass('active');
            } else {
                this.container.find('input[name="daterangepicker_end"]').addClass('active');
                this.container.find('input[name="daterangepicker_start"]').removeClass('active');
            }
            this.updateMonthsInView();
            this.updateCalendars();
            this.updateFormInputs();
        }, updateMonthsInView: function () {
            if (this.endDate) {
                if (!this.singleDatePicker && this.leftCalendar.month && this.rightCalendar.month && (this.startDate.format('YYYY-MM') == this.leftCalendar.month.format('YYYY-MM') || this.startDate.format('YYYY-MM') == this.rightCalendar.month.format('YYYY-MM')) && (this.endDate.format('YYYY-MM') == this.leftCalendar.month.format('YYYY-MM') || this.endDate.format('YYYY-MM') == this.rightCalendar.month.format('YYYY-MM'))) {
                    return;
                }
                this.leftCalendar.month = this.startDate.clone().date(2);
                if (!this.linkedCalendars && (this.endDate.month() != this.startDate.month() || this.endDate.year() != this.startDate.year())) {
                    this.rightCalendar.month = this.endDate.clone().date(2);
                } else {
                    this.rightCalendar.month = this.startDate.clone().date(2).add(1, 'month');
                }
            } else {
                if (this.leftCalendar.month.format('YYYY-MM') != this.startDate.format('YYYY-MM') && this.rightCalendar.month.format('YYYY-MM') != this.startDate.format('YYYY-MM')) {
                    this.leftCalendar.month = this.startDate.clone().date(2);
                    this.rightCalendar.month = this.startDate.clone().date(2).add(1, 'month');
                }
            }
            if (this.maxDate && this.linkedCalendars && !this.singleDatePicker && this.rightCalendar.month > this.maxDate) {
                this.rightCalendar.month = this.maxDate.clone().date(2);
                this.leftCalendar.month = this.maxDate.clone().date(2).subtract(1, 'month');
            }
        }, updateCalendars: function () {
            this.createCalendarData('left');
            if (this.clickedDate) {
                this.renderCalendar('left');
                if (!this.singleDatePicker) {
                    this.createCalendarData('right');
                    this.renderCalendar('right');
                }
                this.clickedDate = false;
            } else {
                if (typeof this.fetchEvents == 'function' && this.setEvents) {
                    if (!this.singleDatePicker) {
                        this.createCalendarData('right');
                    }
                    if (this.singleDatePicker) {
                        this.fetchEvents(this.leftCalendar.calendar[0][0], this.leftCalendar.calendar[5][6], this, this.setEvents);
                    } else {
                        this.fetchEvents(this.leftCalendar.calendar[0][0], this.rightCalendar.calendar[5][6], this, this.setEvents);
                    }
                } else {
                    this.renderCalendar('left');
                    if (!this.singleDatePicker) {
                        this.renderCalendar('right');
                    }
                }
            }
            this.container.find('.ranges li').removeClass('active');
            if (this.endDate == null) return;
            this.showCalendars();
        }, createCalendarData: function (side) {
            var calendar = side == 'left' ? this.leftCalendar : this.rightCalendar;
            var month = calendar.month.month();
            var year = calendar.month.year();
            var hour = calendar.month.hour();
            var minute = calendar.month.minute();
            var second = calendar.month.second();
            var daysInMonth = moment([year, month]).daysInMonth();
            var firstDay = moment([year, month, 1]);
            var lastDay = moment([year, month, daysInMonth]);
            var lastMonth = moment(firstDay).subtract(1, 'month').month();
            var lastYear = moment(firstDay).subtract(1, 'month').year();
            var daysInLastMonth = moment([lastYear, lastMonth]).daysInMonth();
            var dayOfWeek = firstDay.day();
            var calendar = [];
            calendar.firstDay = firstDay;
            calendar.lastDay = lastDay;
            for (var i = 0; i < 6; i++) {
                calendar[i] = [];
            }
            var startDay = daysInLastMonth - dayOfWeek + this.locale.firstDay + 1;
            if (startDay > daysInLastMonth)
                startDay -= 7;
            if (dayOfWeek == this.locale.firstDay)
                startDay = daysInLastMonth - 6;
            var curDate = moment([lastYear, lastMonth, startDay, 12, minute, second]);
            var col, row;
            for (var i = 0, col = 0, row = 0; i < 42; i++, col++, curDate = moment(curDate).add(24, 'hour')) {
                if (i > 0 && col % 7 === 0) {
                    col = 0;
                    row++;
                }
                calendar[row][col] = curDate.clone().hour(hour).minute(minute).second(second);
                curDate.hour(12);
                if (this.minDate && calendar[row][col].format('YYYY-MM-DD') == this.minDate.format('YYYY-MM-DD') && calendar[row][col].isBefore(this.minDate) && side == 'left') {
                    calendar[row][col] = this.minDate.clone();
                }
                if (this.maxDate && calendar[row][col].format('YYYY-MM-DD') == this.maxDate.format('YYYY-MM-DD') && calendar[row][col].isAfter(this.maxDate) && side == 'right') {
                    calendar[row][col] = this.maxDate.clone();
                }
            }
            if (side == 'left') {
                this.leftCalendar.calendar = calendar;
            } else {
                if (side == 'right' && !this.singleDatePicker) {
                    this.rightCalendar.calendar = calendar;
                }
            }
        }, setEvents: function (events, el) {
            el.allEvents = events;
            el.renderCalendar('left');
            if (!el.singleDatePicker) {
                el.renderCalendar('right');
            }
        }, renderCalendar: function (side) {
            this.createCalendarData(side);
            var calendar = {};
            if (side == 'left') {
                calendar = this.leftCalendar.calendar;
            } else {
                calendar = this.rightCalendar.calendar;
            }
            var minDate = side == 'left' ? this.minDate : this.startDate;
            var maxDate = this.maxDate;
            var selected = side == 'left' ? this.startDate : this.endDate;
            var arrow = this.locale.direction == 'ltr' ? {
                left: 'chevron-left',
                right: 'chevron-right'
            } : {left: 'chevron-right', right: 'chevron-left'};
            var dateHtml = '<div class="calendar-header">' + this.locale.monthNames[calendar[1][1].month()] + calendar[1][1].format(" YYYY") + '</div>';
            var currentMonth = calendar[1][1].month();
            var html = '<div class="table-header">';
            if ((!minDate || minDate.isBefore(calendar.firstDay)) && (!this.linkedCalendars || side == 'left')) {
                html += '<div class="prev available"></div>';
            } else {
                html += '<div></div>';
            }
            var dateHtml = this.locale.monthNames[calendar[1][1].month()] + calendar[1][1].format(" YYYY");
            html += '<div class="month">' + dateHtml + '</div>';
            if ((!maxDate || maxDate.isAfter(calendar.lastDay)) && (!this.linkedCalendars || side == 'right' || this.singleDatePicker)) {
                html += '<div class="next available"></div>';
            } else {
                html += '<div class="next available"></div>';
            }
            html += '</div>';
            html += '<div class="table-sub-header">';
            $.each(this.locale.daysOfWeek, function (index, dayOfWeek) {
                html += '<div class="day-of-week">' + dayOfWeek + '</div>';
            });
            html += '</div>';
            html += '</div>';
            html += '<table class="table-condensed body">';
            html += '<tbody>';
            if (this.endDate == null && this.dateLimit) {
                var maxLimit = this.startDate.clone().add(this.dateLimit).endOf('day');
                if (!maxDate || maxLimit.isBefore(maxDate)) {
                    maxDate = maxLimit;
                }
            }
            for (var row = 0; row < 6; row++) {
                html += '<tr>';
                for (var col = 0; col < 7; col++) {
                    var classes = [];
                    if (calendar[row][col].isSame(new Date(), "day"))
                        classes.push('today');
                    if (this.minimumCheckin > 0) {
                        for (var _i = 0; _i < this.minimumCheckin; _i++) {
                            var _today = new Date(new Date().getTime() + (_i * 24 * 60 * 60 * 1000));
                            if (calendar[row][col].isSame(_today, "day")) {
                                classes.push('off', 'disabled');
                            }
                        }
                    }
                    if (calendar[row][col].isoWeekday() == 1)
                        classes.push('monday');
                    if (calendar[row][col].isoWeekday() > 5)
                        classes.push('weekend');
                    if (calendar[row][col].isoWeekday() == 7)
                        classes.push('sunday');
                    if (calendar[row][col].month() != calendar[1][1].month())
                        classes.push('off');
                    if (this.minDate && calendar[row][col].isBefore(this.minDate, 'day'))
                        classes.push('off', 'disabled');
                    if (maxDate && calendar[row][col].isAfter(maxDate, 'day'))
                        classes.push('off', 'disabled');
                    if (this.isInvalidDate(calendar[row][col]))
                        classes.push('off', 'disabled');
                    if (calendar[row][col].format('YYYY-MM-DD') == this.startDate.format('YYYY-MM-DD')) {
                        classes.push('active', 'start-date');
                    }
                    if (this.endDate != null && calendar[row][col].format('YYYY-MM-DD') == this.endDate.format('YYYY-MM-DD'))
                        classes.push('active', 'end-date');
                    if (this.endDate != null && calendar[row][col] > this.startDate && calendar[row][col] < this.endDate)
                        classes.push('in-range');
                    if (this.disabledDates != null) {
                        var currentDate = calendar[row][col].format('YYYY-MM-DD');
                        if (this.disabledDates.indexOf(currentDate) != -1) {
                            classes.push('off', 'disabled');
                        }
                    }
                    var in_pass = false;
                    if (this.disabledPast) {
                        var currentDate = calendar[row][col].format('YYYY-MM-DD');
                        var today = moment().format('YYYY-MM-DD');
                        if (currentDate < today) {
                            classes.push('off', 'disabled');
                            in_pass = true;
                        }
                    }
                    var event_html = '', event_class = '', date_class = '', has_starttime = '';
                    for (i = 0; i < this.allEvents.length; i++) {
                        var currentDate = calendar[row][col], start = moment(this.allEvents[i].start, 'YYYY-MM-DD'),
                            end = moment(this.allEvents[i].end, 'YYYY-MM-DD');
                        if (start.isSame(currentDate, 'day') || end.isSame(currentDate, 'day') || (currentDate.isAfter(start) && currentDate.isBefore(end))) {
                            if (start.isSame(currentDate, 'day') && this.allEvents[i].group) {
                                date_class += 'start-group ';
                            }
                            if (end.isSame(currentDate, 'day') && this.allEvents[i].group) {
                                date_class += 'end-group ';
                                classes.push('off', 'disabled');
                            }
                            if (currentDate.isAfter(start) && currentDate.isBefore(end) && this.allEvents[i].group) {
                                date_class += 'in-group ';
                                classes.push('off', 'disabled');
                            }
                            event_class += (typeof this.allEvents[i].extra_class != 'undefined') ? ' ' + this.allEvents[i].extra_class + ' ' : '';
                            if (this.showEventTooltip) {
                                event_class += 'event-tooltip';
                                event_html = '<div class="event-tooltip-wrap"><div class="' + event_class + ' event event-' + row + '-' + col + '" data-date-group="' + this.allEvents[i].end + '">' + this.allEvents[i].event + '</div>';
                                if (typeof this.allEvents[i].has_starttime !== 'undefined') {
                                    if (this.allEvents[i].has_starttime) {
                                        has_starttime = ' has_starttime ';
                                    }
                                }
                            } else {
                                event_html = '<div class="event event-' + row + '-' + col + ' ' + event_class + '" data-date-group="' + this.allEvents[i].end + '">' + this.allEvents[i].event + '</div>';
                            }
                            if (typeof this.allEvents[i].status == 'string') {
                                if (this.allEvents[i].status === 'not_available' || this.allEvents[i].status === 'booked') {
                                    if (typeof this.classNotAvailable == 'object') {
                                        $.each(this.classNotAvailable, function (index, val) {
                                            classes.push(val);
                                        });
                                    } else {
                                        classes.push('not-available');
                                    }
                                    if (this.disabledDates.indexOf(start.format('YYYY-MM-DD')) === -1) {
                                        this.disabledDates.push(start.format('YYYY-MM-DD'));
                                    }
                                }
                                if (this.allEvents[i].status === 'booked') {
                                    classes.push('booked', 'disabled');
                                }
                                if (this.allEvents[i].status === 'toggle_available') {
                                    classes.push('toggle_available');
                                }
                            } else {
                                if (in_pass) {
                                    classes.push('not-available', 'disabled');
                                }
                            }

                            if (this.breakEvent) {
                                break;
                            }
                        }
                    }
                    var isCustom = this.isCustomDate(calendar[row][col]);
                    if (isCustom !== false) {
                        if (typeof isCustom === 'string')
                            classes.push(isCustom); else
                            Array.prototype.push.apply(classes, isCustom);
                    }
                    var cname = '', disabled = false;
                    for (var i = 0; i < classes.length; i++) {
                        cname += classes[i] + ' ';
                        if (classes[i] == 'disabled')
                            disabled = true;
                    }
                    if (!disabled)
                        cname += ' available';
                    if (event_html) {
                        cname += ' has-event';
                    }
                    if (this.showEventTooltip) {
                        cname += ' has-tooltip';
                    }
                    var has_starttime_date = '';
                    if (has_starttime)
                        has_starttime_date = 'has_starttime';
                    if (this.onlyShowCurrentMonth && calendar[row][col].month() != calendar[1][1].month()) {
                        if(this.dateFlex){
                            html += '<td class="td-date td-no-show ' + has_starttime + date_class + cname.replace(/^\s+|\s+$/g, '') + '"' + ' data-title="' + 'r' + row + 'c' + col + '">' + '<div class="date-outer"><div class="date no-show"></div></div>' + event_html + '</td>';
                        }else{
                            html += '<td class="td-date td-no-show ' + has_starttime + date_class + cname.replace(/^\s+|\s+$/g, '') + '"' + ' data-title="' + 'r' + row + 'c' + col + '">' + '<div class="date no-show"></div>' + event_html + '</td>';
                        }
                    } else {
                        if(this.dateFlex){
                            html += '<td class="td-date ' + has_starttime + date_class + cname.replace(/^\s+|\s+$/g, '') + '"' + ' data-title="' + 'r' + row + 'c' + col + '">' + '<div class="date-outer"><div class="date ' + has_starttime_date + '">' + calendar[row][col].date() + '</div></div>' + event_html + '</td>';
                        }else{
                            html += '<td class="td-date ' + has_starttime + date_class + cname.replace(/^\s+|\s+$/g, '') + '"' + ' data-title="' + 'r' + row + 'c' + col + '">' + '<div class="date ' + has_starttime_date + '">' + calendar[row][col].date() + '</div>' + event_html + '</td>';
                        }
                    }
                }
                html += '</tr>';
            }
            html += '</tbody>';
            html += '</table>';
            if (this.showTodayButton && side == 'left') {
                html += '<a href="javascript: void(0);" class="button button-default btn btn-success btn-small btn-today">' + this.locale.today + '</a>';
            }
            if (side == 'left') {
                this.container.find('.calendar.left .calendar-table').html(html);
            }
            if (!this.singleDatePicker && side == 'right') {
                this.container.find('.calendar.right .calendar-table').html(html);
            }
        }, updateFormInputs: function () {
            if (this.container.find('input[name=daterangepicker_start]').is(":focus") || this.container.find('input[name=daterangepicker_end]').is(":focus"))
                return;
            this.container.find('input[name=daterangepicker_start]').val(this.startDate.format(this.locale.format));
            if (this.endDate)
                this.container.find('input[name=daterangepicker_end]').val(this.endDate.format(this.locale.format));
            if (this.singleDatePicker || (this.endDate && (this.startDate.isBefore(this.endDate) || this.startDate.isSame(this.endDate)))) {
                this.container.find('button.applyBtn').removeAttr('disabled');
            } else {
                this.container.find('button.applyBtn').attr('disabled', 'disabled');
            }
        }, move: function () {

            let parentOffset = {
                top: (this.element.offset().top + this.element.outerHeight()),
                left: this.element.offset().left - this.parentEl.scrollLeft()
            };

            if(this.container.css('position') === 'fixed'){
                parentOffset.top = (this.element.offset().top + this.element.outerHeight()) - $(window).scrollTop();
            }else{
                if(!this.parentEl.is('body')){
                    parentOffset.top = this.element.outerHeight();
                    parentOffset.left = 0;
                }
            }

            let $el = this.element.closest(this.maybeFixed);
            if ($el.length) {
                if ($el.css('position') === 'fixed') {
                    parentOffset.top = (this.element.offset().top - $el.offset().top + this.element.outerHeight());
                }
            }


            this.container[this.drops == 'up' ? 'addClass' : 'removeClass']('dropup');

            if (this.opens == 'left') {
                this.container.css({
                    top: parentOffset.top,
                    right: $(window).width() - (this.element.offset().left + this.element.outerWidth()),
                    left: 'auto'
                });
            } else {
                this.container.css({top: parentOffset.top, left: parentOffset.left, right: 'auto'});
                this.container.removeClass('but-move-left');
            }
        }, show: function (e) {
            if (this.isShowing) return;
            this._outsideClickProxy = $.proxy(function (e) {
                this.outsideClick(e);
            }, this);
            $(document).on('mousedown.daterangepicker', this._outsideClickProxy).on('touchend.daterangepicker', this._outsideClickProxy).on('click.daterangepicker', '[data-toggle=dropdown]', this._outsideClickProxy).on('focusin.daterangepicker', this._outsideClickProxy);
            this.respontosingle(e);
            $(window).on('resize.daterangepicker', $.proxy(function (e) {
                this.move(e);
                this.respontosingle(e);
            }, this));
            this.oldStartDate = this.startDate.clone();
            this.oldEndDate = this.endDate.clone();
            this.previousRightTime = this.endDate.clone();
            this.updateView();
            this.container.show();
            this.move();
            this.element.trigger('show.daterangepicker', this);
            this.isShowing = true;
        }, respontosingle: function (e) {
            if (this.showCalendar || !this.autoResponsive) {
                return;
            }
            var _width_single = this.widthSingle;
            var parent = this.parentEl;
            if (this.parentEl.is('body')) {
                parent = this.element;
            }
            if ($(window).width() - parent.offset().left < this.widthCalendar && parent.offset().left < this.widthCalendar) {
                this.container.addClass('respon-single');
            } else {
                this.container.removeClass('respon-single');
            }
            if (!this.container.hasClass('respon-single')) {
                _width_single = this.widthCalendar;
            }
            if (($(window).width() - parent.offset().left < _width_single) && (parent.offset().left >= _width_single)) {
                this.container.removeClass('opensleft opensright moveleft moveright openscenter').addClass('moveleft opensleft');
                this.opens = 'left';
            } else {
                this.opens = 'right';
                this.container.removeClass('moveleft opensleft opensright moveright openscenter').addClass('moveright opensright');
            }
            if (this.alwaysShowCalendars) {
                if (parent.width() < this.widthCalendar) {
                    this.container.addClass('respon-single');
                } else {
                    this.container.removeClass('respon-single');
                }
            }
            this.move(e);
            this.updateView();
        }, hide: function (e, applyCallback) {
            if (!this.isShowing) return;
            if (!this.endDate) {
                this.startDate = this.oldStartDate.clone();
                this.endDate = this.oldEndDate.clone();
                if (this.sameDate && this.singleClick) {
                    this.endDate = this.startDate;
                } else {
                    var start = moment(this.startDate.format('YYYY-MM-DD'));
                    var end = moment(this.endDate.format('YYYY-MM-DD'));
                    if (end.isSame(start)) {
                        this.endDate = this.endDate.add(1, 'days');
                    }
                }
            } else {
                var start = moment(this.startDate.format('YYYY-MM-DD'));
                var end = moment(this.endDate.format('YYYY-MM-DD'));
                if (end.isSame(start) && !this.sameDate && !this.showCalendar) {
                    this.endDate = this.endDate.add(1, 'days');
                }
            }
            if(applyCallback){
                this.callback(this.startDate, this.endDate, this.chosenLabel, this.elmDate, this);
            }
            this.updateElement(e);
            $(document).off('.daterangepicker');
            $(window).off('.daterangepicker');
            this.container.hide();
            this.element.trigger('hide.daterangepicker', this);
            this.isShowing = false;
        }, toggle: function (e) {
            if (this.isShowing) {
                this.hide();
            } else {
                this.show();
            }
        }, outsideClick: function (e) {
            var target = $(e.target);
            if (e.type == "focusin" || target.closest(this.element).length || target.closest(this.container).length || target.closest('.calendar-table').length) return;
            this.hide();
            this.element.trigger('outsideClick.daterangepicker', this);
        }, showCalendars: function () {
            this.container.addClass('show-calendar');
            this.move();
            this.element.trigger('showCalendar.daterangepicker', this);
        }, hideCalendars: function () {
            this.container.removeClass('show-calendar');
            this.element.trigger('hideCalendar.daterangepicker', this);
        },clickPrev: function (e) {
            var cal = $(e.target).parents('.calendar');
            if (cal.hasClass('left')) {
                this.leftCalendar.month.subtract(1, 'month');
                if (this.linkedCalendars)
                    this.rightCalendar.month.subtract(1, 'month');
            } else {
                this.rightCalendar.month.subtract(1, 'month');
                if( typeof this.leftCalendar == 'object'){
                    this.leftCalendar.month.subtract(1, 'month');
                }
            }
            this.updateCalendars();
        },

        clickNext: function (e) {
            var cal = $(e.target).parents('.calendar');
            if (cal.hasClass('left')) {
                this.leftCalendar.month.add(1, 'month');
                if( typeof this.rightCalendar == 'object'){
                    this.rightCalendar.month.add(1, 'month');
                }
            } else {
                this.rightCalendar.month.add(1, 'month');
                if (this.linkedCalendars)
                    this.leftCalendar.month.add(1, 'month');
            }
            this.updateCalendars();
        },
        clickToday: function (e) {
            this.leftCalendar.month = moment();
            if (!this.singleDatePicker) {
                this.rightCalendar.month = moment().add(1, 'month');
            }
            this.updateCalendars();
        }, hoverDate: function (e) {
            if (!$(e.target).parent().hasClass('available')) return;
            var title = $(e.target).parent().attr('data-title');
            var row = title.substr(1, 1);
            var col = title.substr(3, 1);
            var cal = $(e.target).parents('.calendar');
            var date = cal.hasClass('left') ? this.leftCalendar.calendar[row][col] : this.rightCalendar.calendar[row][col];
            if (this.endDate && !this.container.find('input[name=daterangepicker_start]').is(":focus")) {
                this.container.find('input[name=daterangepicker_start]').val(date.format(this.locale.format));
            } else if (!this.endDate && !this.container.find('input[name=daterangepicker_end]').is(":focus")) {
                this.container.find('input[name=daterangepicker_end]').val(date.format(this.locale.format));
            }
            var leftCalendar = this.leftCalendar;
            var rightCalendar = this.rightCalendar;
            var startDate = this.startDate;
            if (!this.endDate) {
                this.container.find('.calendar td').each(function (index, el) {
                    if ($(el).hasClass('week')) return;
                    var title = $(el).attr('data-title');
                    var row = title.substr(1, 1);
                    var col = title.substr(3, 1);
                    var cal = $(el).parents('.calendar');
                    var dt = cal.hasClass('left') ? leftCalendar.calendar[row][col] : rightCalendar.calendar[row][col];
                    if ((dt.isAfter(startDate) && dt.isBefore(date)) || dt.isSame(date, 'day')) {
                        $(el).addClass('in-range');
                    } else {
                        $(el).removeClass('in-range');
                    }
                });
            }
        }, clickDate: function (e) {
            this.elmDate = e;
            if (!$(e.target).parent().hasClass('available')) return;
            var title = $(e.target).parent().attr('data-title');
            var row = title.substr(1, 1);
            var col = title.substr(3, 1);
            var cal = $(e.target).parents('.calendar');
            var date = cal.hasClass('left') ? this.leftCalendar.calendar[row][col] : this.rightCalendar.calendar[row][col];
            if (this.endDate || date.isBefore(this.startDate, 'day')) {

                this.endDate = null;
                if (this.sameDate && this.singleClick) {
                    this.isShowing = true;
                    this.chosenLabel = 'clicked_date';
                    this.setStartDate(date.clone());
                    this.setEndDate(this.startDate);
                    this.clickApply(e, true);
                } else {
                    this.setStartDate(date.clone());
                }
            } else if (!this.endDate && date.isBefore(this.startDate)) {
                this.setEndDate(this.startDate.clone());
                this.applyCallBack(this);
            } else {
                this.chosenLabel = 'clicked_date';
                this.setEndDate(date.clone());
                if (this.disabledDates) {
                    var start = this.startDate, end = this.endDate, inRange = false;
                    for (var i = 0; i < this.disabledDates.length; i++) {
                        var val = moment(this.disabledDates[i], 'YYYY-MM-DD');
                        if (val.isSame(start) || val.isSame(end) || (val.isAfter(start)) && val.isBefore(end)) {
                            inRange = true;
                            break;
                        }
                    }
                    if (inRange && !this.sameDate) {
                        this.startDate = start;
                        this.setStartDate(this.startDate);
                        this.endDate = start.add('1', 'days');
                        this.setEndDate(this.endDate);
                    }
                }
                if (this.autoApply) {
                    this.clickApply(e, false);
                    this.applyCallBack(this);

                }
            }

            this.clickedDate = true;
            this.updateView();
            e.stopPropagation();
        }, clickApply: function (e, applyCallback) {
            this.hide(e, applyCallback);
            this.element.trigger('apply.daterangepicker', [this, e.target]);
        }, clickCancel: function (e) {
            this.startDate = this.oldStartDate;
            this.endDate = this.oldEndDate;
            this.hide();
            this.element.trigger('cancel.daterangepicker', this);
        }, monthOrYearChanged: function (e) {
            var isLeft = $(e.target).closest('.calendar').hasClass('left'), leftOrRight = isLeft ? 'left' : 'right',
                cal = this.container.find('.calendar.' + leftOrRight);
            var month = parseInt(cal.find('.monthselect').val(), 10);
            var year = cal.find('.yearselect').val();
            if (!isLeft) {
                if (year < this.startDate.year() || (year == this.startDate.year() && month < this.startDate.month())) {
                    month = this.startDate.month();
                    year = this.startDate.year();
                }
            }
            if (this.minDate) {
                if (year < this.minDate.year() || (year == this.minDate.year() && month < this.minDate.month())) {
                    month = this.minDate.month();
                    year = this.minDate.year();
                }
            }
            if (this.maxDate) {
                if (year > this.maxDate.year() || (year == this.maxDate.year() && month > this.maxDate.month())) {
                    month = this.maxDate.month();
                    year = this.maxDate.year();
                }
            }
            if (isLeft) {
                this.leftCalendar.month.month(month).year(year);
                if (this.linkedCalendars)
                    this.rightCalendar.month = this.leftCalendar.month.clone().add(1, 'month');
            } else {
                this.rightCalendar.month.month(month).year(year);
                if (this.linkedCalendars)
                    this.leftCalendar.month = this.rightCalendar.month.clone().subtract(1, 'month');
            }
            this.updateCalendars();
        }, formInputsChanged: function (e) {
            var isRight = $(e.target).closest('.calendar').hasClass('right');
            var start = moment(this.container.find('input[name="daterangepicker_start"]').val(), this.locale.format);
            var end = moment(this.container.find('input[name="daterangepicker_end"]').val(), this.locale.format);
            if (start.isValid() && end.isValid()) {
                if (isRight && end.isBefore(start))
                    start = end.clone();
                this.setStartDate(start);
                this.setEndDate(end);
                if (isRight) {
                    this.container.find('input[name="daterangepicker_start"]').val(this.startDate.format(this.locale.format));
                } else {
                    this.container.find('input[name="daterangepicker_end"]').val(this.endDate.format(this.locale.format));
                }
            }
            this.updateView();
        }, formInputsFocused: function (e) {
            this.container.find('input[name="daterangepicker_start"], input[name="daterangepicker_end"]').removeClass('active');
            $(e.target).addClass('active');
            var isRight = $(e.target).closest('.calendar').hasClass('right');
            if (isRight) {
                this.endDate = null;
                this.setStartDate(this.startDate.clone());
                this.updateView();
            }
        }, formInputsBlurred: function (e) {
            if (!this.endDate) {
                var val = this.container.find('input[name="daterangepicker_end"]').val();
                var end = moment(val, this.locale.format);
                if (end.isValid()) {
                    this.setEndDate(end);
                    this.updateView();
                }
            }
        }, elementChanged: function () {
            if (!this.element.is('input')) return;
            if (!this.element.val().length) return;
            if (this.element.val().length < this.locale.format.length) return;
            var dateString = this.element.val().split(this.locale.separator), start = null, end = null;
            if (dateString.length === 2) {
                start = moment(dateString[0], this.locale.format);
                end = moment(dateString[1], this.locale.format);
            }
            if (this.singleDatePicker || start === null || end === null) {
                start = moment(this.element.val(), this.locale.format);
                end = start;
            }
            if (!start.isValid() || !end.isValid()) return;
            if (moment(end.format('YYYY-MM-DD')).isSame(moment(start.format('YYYY-MM-DD'))) && !this.singleDay && !this.sameDate) {
                end = end.add(1, 'days');
            }
            this.setStartDate(start);
            this.setEndDate(end);
            this.updateView();
        }, keydown: function (e) {
            if ((e.keyCode === 9) || (e.keyCode === 13)) {
                this.hide();
            }
        }, updateElement: function () {
            if (this.element.is('input') && !this.singleDatePicker && this.autoUpdateInput) {
                this.element.val(this.startDate.format(this.locale.format) + this.locale.separator + this.endDate.format(this.locale.format));
                this.element.trigger('change', this);
            } else if (this.element.is('input') && this.autoUpdateInput) {
                if (this.showCalendar) {
                    if (this.startDate && this.endDate) {
                        this.element.val(this.startDate.format(this.locale.format) + this.locale.separator + this.endDate.format(this.locale.format));
                    }
                } else {
                    if (this.startDate) {
                        this.element.val(this.startDate.format(this.locale.format));
                    }
                }
                this.element.trigger('change', this);
            }
        }, remove: function () {
            this.container.remove();
            this.element.off('.daterangepicker');
            this.element.removeData();
        }, applyCallBack: function (e) {
            this.callback(this.startDate, this.endDate, this.chosenLabel, this.elmDate, e);
        }
    };
    $.fn.daterangepicker = function (options, callback) {
        this.each(function () {
            var el = $(this);
            if (el.data('daterangepicker'))
                el.data('daterangepicker').remove();
            el.data('daterangepicker', new DateRangePicker(el, options, callback));
        });
        return this;
    };
    return DateRangePicker;
}));
