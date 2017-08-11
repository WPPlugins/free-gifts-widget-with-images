jQuery(document).ready(function () {

    var activeDiv = 1;
    showDiv(activeDiv);
    var timer = setInterval(changeDiv, 3600);


    function changeDiv() {
        activeDiv++;
        if (activeDiv == 7) {
            activeDiv = 1;
        }
        showDiv(activeDiv);
    }

    function showDiv(num) {
        jQuery('div.gift').hide();
        jQuery('#gift_' + num).fadeIn();
    }


});