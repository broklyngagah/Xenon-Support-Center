$(document).ready(function(){

    $('#change_status').on('change',function(){

        var checked = $(this).is('checked');

        $.ajax({
            url: "/api/change_status/"+checked,
            success: function () {
            },
            error: function (response) {

            }
        });

    });


});