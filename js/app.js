$.mobile.pushStateEnabled = false;
$.mobile.ajaxEnabled = false;
$.mobile.defaultPageTransition = 'none';

function handle_days($on_days) {
    var i, v = Number($on_days.val()) || 0;
    var $day;

    function handle_day_click() {
        var $this = $(this);
        var day = $this.data('day');

        if($this.prop('checked')) {
            v = v | (1 << day);
        } else {
            v = v & ((1 << day) ^ 0x7f);
        }

        $on_days.val(v);
    }

    for(i=0; i<7; i++) {
        $day = $('#day-' + i);
        $day.data('day', i);

        if(v & (1 << i)) {
            $day.attr('checked', true).checkboxradio("refresh");;
        } else {
            $day.attr('checked', false).checkboxradio("refresh");;
        }

        $day.click(handle_day_click);
    }
}

$(document).ready(function(){
    window.setTimeout(function() {
        $('div.alert').slideUp();
    }, 1000*10);

    var $on_days = $('#on_days');
    if($on_days.length === 1) {
        handle_days($on_days);
    }
});
