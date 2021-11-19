'use strict';

function selectText(id){
    let sel, range;
    let el = document.getElementById(id); //get element id
    if (window.getSelection && document.createRange) { //Browser compatibility
        sel = window.getSelection();
        if(sel.toString() === ''){ //no text selection
            window.setTimeout(function(){
                range = document.createRange(); //range object
                range.selectNodeContents(el); //sets Range
                sel.removeAllRanges(); //remove all ranges from selection
                sel.addRange(range);//add Range to a Selection.
                document.execCommand("copy");
            },1);
        }
    }else if (document.selection) { //older ie
        sel = document.selection.createRange();
        if(sel.text === ''){ //no text selection
            range = document.body.createTextRange();//Creates TextRange object
            range.moveToElementText(el);//sets Range
            range.select().createTextRange();
            document.execCommand("copy");
        }
    }
}
(function ($) {
    $("#hh-dropdown-notification").on('show.bs.dropdown', function () {
        let t = $(this),
            data = JSON.parse(Base64.decode(t.data('params')));
        data['_token'] = $('meta[name="csrf-token"]').attr('content');

        $.post(t.data('action'), data, function (respon) {
            if (typeof respon == 'object') {
                if (respon.status === 1) {
                    $('.badge', t).remove();
                    $('.notification-render', t).html(respon.notifications);
                    $(".slimscroll", t).slimScroll({
                        height: "auto",
                        position: "right",
                        size: "8px",
                        touchScrollStep: 20,
                        color: "#9ea5ab"
                    })
                } else {
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
                }
            }
        }, 'json');
    });
})(jQuery)
