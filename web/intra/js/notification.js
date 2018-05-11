$().ready(function(){


    "use strict";
    function load_unseen_notification_number()
    {

        $.ajax({
            url:"/intranet/unseen-notifications",
            method:"GET",
            dataType:"json",
            success:function(data)
            {
                if(data.unseen_notification_number > 0) {
                    $('.count').html(data.unseen_notification_number);
                }
                else if(data.unseen_notification_number == 0) {
                    $('.count').empty();
                }
            }

        });
    }
    load_unseen_notification_number();


    $('#notification-link').click(function () {
        function load_last_teen_notification()
        {

            $.ajax({
                url:"/intranet/last-teen-notifications",
                method:"GET",
                dataType:"json",
                success:function(data)
                {
                    var notifications = JSON.parse(data);
                    var htmlNotificationList = [];
                    for (var indice in  notifications) {

                        var isSeen = notifications[indice].seen;
                        var seen = isSeen?'':'unseen';
                        var notificationId = notifications[indice].notification.id;

                        htmlNotificationList.push(
                            '<li id = "'+notificationId+'"class="'+seen+'">' +
                            '<a href="#"><i class="fa fa-users text-aqua"></i>'+
                            notifications[indice].notification.message+
                            '</a></li>');
                    }
                    $('#notifications').html(
                        htmlNotificationList
                    );

                }
            });
        }
        load_last_teen_notification();
    });





    $('#notifications').on('click','li',function () {

        var notification = {
            'id': $(this).attr('id'),
        };
        $.ajax({
            url:"/intranet/see-notification",
            method:"POST",
            dataType:"json",
            success:function(data)
            {
                load_unseen_notification_number();
            },
            error:function ()
            {
                alert('not cool man');
            },
            data: notification
        });
    });

    setInterval(function(){
        load_unseen_notification_number();
    }, 5000);

});



