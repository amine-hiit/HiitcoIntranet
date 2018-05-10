$().ready(function(){


    "use strict";
    function load_unseen_notification()
    {

        $.ajax({
            url:"/intranet/get-notif",
            method:"GET",
            dataType:"json",
            success:function(data)
            {
                var notifications = JSON.parse(data);
                if(notifications.unseen_notification_number > 0) {
                    $('.count').html(notifications.unseen_notification_number);
                    for (var indice in  notifications.all_notifications) {
                                $('.notifications').html(
                                    '<li> <a href="'+
                                    notifications.all_notifications[indice].notification.url +
                                    '"><i class="fa fa-users text-aqua"></i>'+
                                    notifications.all_notifications[indice].notification.message+
                                    '</a></li>'
                                );

                    }
                }
            }
        });
    }






    load_unseen_notification();

    setInterval(function(){
        load_unseen_notification();
    }, 5000);

});

