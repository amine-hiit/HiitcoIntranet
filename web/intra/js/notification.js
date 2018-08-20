"use strict";
$().ready(function(){

    let _index = 0;
    load_unseen_notification_number();
    load_menu_notifications();
    load_next_teen_notifications();

    function load_next_teen_notifications()
    {
        $.ajax({
            url:"/intranet/api/notifications?page="+_index,
            method:"GET",
            dataType:"json",
            success:function(data)
            {
                let notifications = data ;
                if (data.length > 0){
                    let htmlNotificationList = [];
                    for (let indice in  notifications) {
                        let isSeen = notifications[indice].seen;
                        let seen = isSeen?'':'unseen';
                        let notificationId = notifications[indice].notification.id;
                        let message = notifications[indice].notification.message;
                        let url = notifications[indice].notification.url;

                        htmlNotificationList.push(`
                        <a id = "${ notificationId }" href="${ url }" class="list-group-item ${ seen }">
                        ${ message }
                        </a>`
                        );
                    }

                    $('.notifications').append(
                        htmlNotificationList
                    );
                    _index++;
                } else {
                    $('#see-more').remove();
                }
            }
        });
    }



    function load_menu_notifications()
    {
        $.ajax({
            url:"/intranet/api/notifications?page="+_index,
            method:"GET",
            dataType:"json",
            success:function(data)
            {
                let notifications = data;
                let htmlNotificationList = [];
                for (let indice in  notifications) {
                    let isSeen = notifications[indice].seen;
                    let seen = isSeen?'':'unseen';
                    let notificationId = notifications[indice].notification.id;
                    let message = notifications[indice].notification.message;
                    let url = notifications[indice].notification.url;

                    htmlNotificationList.push(`
                        <a id = "${ notificationId }" href="${ url }" class="list-group-item ${ seen }">
                        ${ message }
                        </a>`
                    );
                }
                $('#notifications-menu').html(htmlNotificationList);
            }
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

    $('#notification-link').click(function () {
        load_menu_notifications();
    });

    $('#see-more').click(function (e) {
        load_next_teen_notifications();
    })

    $('.notifications').on('click','a',function () {
        let notification = {
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
                alert('error');
            },
            data: notification
        });
    });

    setInterval(function(){
        load_unseen_notification_number();
    }, 5000);

});

