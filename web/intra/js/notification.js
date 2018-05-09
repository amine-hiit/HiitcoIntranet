$(document).ready(function(){

    load_unseen_notification();

    "use strict";
    function load_unseen_notification(view = '')
    {
        $.ajax({
            url:"/intranet/unseen-notifications",
            method:"GET",
            dataType:"json",
            success:function(data)
            {
                if(data.unseen_notification > 0)
                {
                    $('.count').html(data.unseen_notification);
                    $('.notifications').html(data.unseen_notification);
                }
            },


        });
    }


    setInterval(function(){
        load_unseen_notification();
    }, 5000);

});

