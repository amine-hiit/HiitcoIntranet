"use strict";
$().ready(function(){

    var _index = 0;
    load_unseen_notification_number();
    load_teen_notifications(_index);


    function load_teen_notifications( _index)
    {
        var index = {
            'index': _index,
        };
        $.ajax({
            url:"/intranet/teen-notifications",
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
                    var message = notifications[indice].notification.message;
                    var url = notifications[indice].notification.url;

                    htmlNotificationList.push(`
                        <a id = "${ notificationId }" href="${ url }" class="list-group-item ${ seen }">
                        ${ message }
                        </a>`
                    );
                }
                if (_index == 0){
                    $('.notifications').html(
                        htmlNotificationList
                    );
                }
                $('#notifications-menu').html(
                    htmlNotificationList
                );

            },
            data:index

        });
    }


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
        load_teen_notifications(0);
    });

    $('.notifications').on('click','a',function () {
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

/*
<li id = "${ notificationId }"class="${ seen }">
<a href="#"><i class="fa fa-users text-aqua">
</i>${notifications[indice].notification.message} </a></li>
*/

